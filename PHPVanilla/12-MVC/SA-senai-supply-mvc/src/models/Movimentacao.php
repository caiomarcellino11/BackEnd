<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\ConexaoBanco;
use PDO;
use App\Core\Logger;
use Throwable;
use DomainException;



final class Movimentacao {
    //criar o PDO
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__, 2) . '/config/database.ini';
        return ConexaoBanco::obterConexao($cfg);
    }

    // Registrar uma Movimentacao (entrada / saída)
    public static function registrar(int $prodId, int $userId, string $tipo, int $qtd, string $motivo): void {
        $pdo = self::pdo();
        $pdo->beginTransaction();
        try {
            if ($tipo === "SAÍDA"){
                self::validarSaldo($pdo, $prodId, $qtd);
            }
            self::atualizarEstoque($pdo, $prodId, $tipo, $qtd);
            self::inserirHistorico($pdo, $prodId, $userId, $tipo, $qtd, $motivo);
            $pdo->commit();
            Logger::info("Movimentação de estoque concluida com sucesso", [
                "produto_id"=> $prodId, 
                "usuario_id"=> $userId,
                "tipo" => $tipo,
                "quantidade" => $qtd]);
        } catch (Throwable $th) {
            $pdo->rollBack(); //não executa a transação
            throw $th;
        }
    }

    //validarr se saldo suficiente para retirada
    private static function validarSaldo(PDO $pdo, int $prodId, int $qtd): void {
        $sql = "SELECT estoque FROM produtos WHERE id = :id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $prodId]);
        $estoqueAtual = (int)$stmt->fetchColumn();
        if ($estoqueAtual < $qtd) {
            Logger::aviso("Tentativa de retirada rejeitada por saldo insuficiente", [
                'produto_id' => $prodId, 'estoque_atual' => $estoqueAtual, 'solicitado' => $qtd
            ]);
            throw new DomainException("Saldo insuficiente. Estoque atual: {$estoqueAtual} un.");
        }
    }

    //Atualizar Estoque
    private static function atualizarEstoque(PDO $pdo, int $prodId, string $tipo, int $qtd): void {
        $operacao = ($tipo === 'ENTRADA') ? '+' : '-'; //operador ternário (if/else encurtado)
        $sql = "UPDATE produtos SET estoque = estoque {$operacao} :qtd WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':qtd' => $qtd, ':id' => $prodId]);
    }

    //Gravar o registro da movimentação no banco de dados fazendo auditoria de log
    private static function inserirHistorico(PDO $pdo, int $prodId, int $userId, string $tipo, int $qtd, string $motivo): void {
        $sql = "INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, motivo) 
                VALUES (:prod, :usr, :tipo, :qtd, :motivo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':prod'   => $prodId,
            ':usr'    => $userId,
            ':tipo'   => $tipo,
            ':qtd'    => $qtd,
            ':motivo' => trim($motivo)
        ]);
        //chamar o registro da auditoria de log
        Logger::info(
            "Movimentação de estoque",[   
                "produto_id"=> $prodId, 
                "usuario_id"=> $userId,
                "tipo" => $tipo,
                "quantidade" => $qtd,
                "motivo"=> $motivo]
        );
    }

    //listar todas as movimentações
    public static function listarHistorico(int $limite = 20): array {
        $sql = "SELECT m.id, m.tipo, m.quantidade, m.motivo, m.criado_em,
                       p.nome AS produto_nome, u.nome AS usuario_nome
                FROM movimentacoes_estoque m
                JOIN produtos p ON p.id = m.produto_id
                JOIN usuarios u ON u.id = m.usuario_id
                ORDER BY m.criado_em DESC LIMIT :limite";
        $stmt = self::pdo()->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}