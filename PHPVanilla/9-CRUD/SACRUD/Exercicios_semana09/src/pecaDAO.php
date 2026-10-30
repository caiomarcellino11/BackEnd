<?php

declare(strict_types=1);

// Camada de Acesso a Dados (DAO) para o almoxarifado de peças industriais.
// Toda regra de persistência fica isolada nesta classe.

final class PecaDAO
{
    // Atributo que guarda a conexão com o banco de dados
    private PDO $pdo;

    // Construtor: exige uma conexão já criada (ex.: pela classe ConexaoBanco)
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ---------- READ ----------

    // Lista somente as peças ativas (Ex. 5: exclusão lógica)
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM pecas_industriais WHERE ativo = TRUE ORDER BY id DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista as peças "excluídas" (continuam no banco para auditoria contábil)
    public function listarInativas(): array
    {
        $sql = "SELECT * FROM pecas_industriais WHERE ativo = FALSE ORDER BY id DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca uma peça pelo ID; devolve null se não existir
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pecas_industriais WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado === false ? null : $resultado;
    }

    // Busca por termo (SKU, descrição ou categoria) com marcadores distintos
    public function buscarPorTermo(string $termo): array
    {
        $sql = "SELECT * FROM pecas_industriais
                WHERE ativo = TRUE
                  AND (codigo_sku ILIKE :termo_sku
                   OR descricao ILIKE :termo_desc
                   OR categoria ILIKE :termo_cat)
                ORDER BY descricao ASC";
        $like = "%" . trim($termo) . "%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":termo_sku" => $like, ":termo_desc" => $like, ":termo_cat" => $like]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------- Ex. 3: paginação ----------

    // Conta as peças ativas (consulta constante, sem dados externos)
    public function contarTodas(): int
    {
        $sql = "SELECT COUNT(*) FROM pecas_industriais WHERE ativo = TRUE";

        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    // Lista uma página do catálogo; LIMIT e OFFSET vinculados como inteiros
    public function listarPagina(int $limite, int $offset): array
    {
        $sql = "SELECT * FROM pecas_industriais WHERE ativo = TRUE
                ORDER BY id LIMIT :limite OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------- CREATE / UPDATE ----------

    // Cadastra uma nova peça no estoque
    public function salvar(array $dados): bool
    {
        $sql = "INSERT INTO pecas_industriais
                (codigo_sku, descricao, categoria, quantidade, preco_unitario)
                VALUES (:sku, :descricao, :categoria, :quantidade, :preco)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":sku"        => strtoupper(trim($dados["codigo_sku"])),
            ":descricao"  => trim($dados["descricao"]),
            ":categoria"  => trim($dados["categoria"]),
            ":quantidade" => (int) $dados["quantidade"],
            ":preco"      => (float) $dados["preco_unitario"],
        ]);
    }

    // Atualiza uma peça; o WHERE estrito por ID evita alterar outras linhas
    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE pecas_industriais
                SET codigo_sku = :sku, descricao = :descricao, categoria = :categoria,
                    quantidade = :quantidade, preco_unitario = :preco
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id"         => $id,
            ":sku"        => strtoupper(trim($dados["codigo_sku"])),
            ":descricao"  => trim($dados["descricao"]),
            ":categoria"  => trim($dados["categoria"]),
            ":quantidade" => (int) $dados["quantidade"],
            ":preco"      => (float) $dados["preco_unitario"],
        ]);
    }

    // ---------- Ex. 5: exclusão lógica (substitui o DELETE físico) ----------

    // Marca a peça como inativa em vez de apagar o registro
    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE pecas_industriais SET ativo = FALSE WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // ---------- Ex. 4: transações ACID ----------

    // Registra entrada/saída de estoque; false se falhar ou se o saldo ficar negativo
    public function registrarMovimentacao(int $pecaId, int $quantidade, string $tipo): bool
    {
        if ($quantidade <= 0 || !in_array($tipo, ["ENTRADA", "SAIDA"], true)) {
            return false;
        }
        try {
            $this->pdo->beginTransaction();
            $saldo = $this->buscarSaldoTravado($pecaId);
            $novoSaldo = $tipo === "ENTRADA" ? $saldo + $quantidade : $saldo - $quantidade;
            if ($novoSaldo < 0) {
                $this->pdo->rollBack();
                return false;
            }
            $this->atualizarSaldo($pecaId, $novoSaldo);
            return $this->pdo->commit();
        } catch (Throwable $erro) {
            return $this->desfazer();
        }
    }

    // Lê o saldo bloqueando a linha (evita corrida entre saídas simultâneas)
    private function buscarSaldoTravado(int $pecaId): int
    {
        $sql = "SELECT quantidade FROM pecas_industriais WHERE id = :id FOR UPDATE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $pecaId, PDO::PARAM_INT);
        $stmt->execute();
        $saldo = $stmt->fetchColumn();
        if ($saldo === false) {
            throw new RuntimeException("Peça não encontrada.");
        }

        return (int) $saldo;
    }

    // Grava o novo saldo da peça
    private function atualizarSaldo(int $pecaId, int $novoSaldo): void
    {
        $sql = "UPDATE pecas_industriais SET quantidade = :saldo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":saldo", $novoSaldo, PDO::PARAM_INT);
        $stmt->bindValue(":id", $pecaId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Cancela a transação pendente (se houver) e sinaliza falha
    private function desfazer(): bool
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }

        return false;
    }
}