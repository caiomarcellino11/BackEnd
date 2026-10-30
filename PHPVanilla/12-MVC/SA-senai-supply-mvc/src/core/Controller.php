<?php
declare(strict_types=1);

//classe base para os controller (superclasse Controller)

abstract class Controller {
    //classe abstrata com elementos de segurança e renderização de layout

    /**
     * Renderiza uma view injetando dados e aplicando o layout padrao.
     */
    protected function renderizar(string $view, array $dados = []): void {
        extract($dados, EXTR_SKIP);
        $arquivoView = dirname(__DIR__) . "/Views/{$view}.php";
        if (!file_exists($arquivoView)) {
            throw new RuntimeException("A view nao foi localizada: {$arquivoView}");
        }
        require_once dirname(__DIR__) . "/Views/layout/header.php";
        require_once $arquivoView;
        require_once dirname(__DIR__) . "/Views/layout/footer.php";
    }

    /**
     * Redireciona o navegador para uma rota interna do sistema.
     */
    protected function redirecionar(string $rota): void {
        header("Location: {$rota}");
        exit;
    }

    /**
     * Gera ou recupera o token CSRF da sessao ativa.
     */
    protected function obterCsrfToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf_token'];
    }

    /**
     * Valida o token CSRF recebido na requisicao POST.
     */
    protected function validarCsrfToken(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $sessaoToken = $_SESSION['csrf_token'] ?? '';
        return !empty($token) && hash_equals((string)$sessaoToken, (string)$token);
    }
}