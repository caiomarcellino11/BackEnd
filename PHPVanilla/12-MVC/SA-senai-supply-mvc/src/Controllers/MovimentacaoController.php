<?php
declare(strict_types=1);

final class MovimentacaoController extends Controller {

    //formulario para registrar movimentacao
 public function formulario(): void {
        $produtoId = (int)($_GET['produto_id'] ?? 0);
        $produto = Produto::buscarPorId($produtoId);
        if (!$produto) {
            $this->redirecionar('/produtos');
        }
        $this->renderizar('movimentacoes/novo', [
            'titulo'    => "Movimentar Estoque: {$produto['nome']}",
            'produto'   => $produto,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
    //salvar a movimentação
   public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirecionar('/produtos');
        }
        $prodId = (int)($_POST['produto_id'] ?? 0);
        $tipo   = (string)($_POST['tipo'] ?? '');
        $qtd    = (int)($_POST['quantidade'] ?? 0);
        $motivo = trim($_POST['motivo'] ?? '');
        $userId = AuthGuard::obterUsuarioId();

        try {
            Movimentacao::registrar($prodId, (int)$userId, $tipo, $qtd, $motivo);
            $this->redirecionar('/movimentacoes');
        } catch (DomainException $e) {
            $produto = Produto::buscarPorId($prodId);
            $this->renderizar('movimentacoes/novo', [
                'titulo'    => "Movimentar Estoque",
                'produto'   => $produto,
                'erro'      => $e->getMessage(),
                'csrfToken' => $this->obterCsrfToken()
            ]);
        }
    }


    //listar os historico de movimentações
        public function historico(): void {
        $movimentacoes = Movimentacao::listarHistorico(50);
        $this->renderizar('movimentacoes/historico', [
            'titulo'        => 'Histórico e Auditoria de Movimentações',
            'movimentacoes' => $movimentacoes
        ]);
    }

}