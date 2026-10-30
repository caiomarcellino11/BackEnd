<?php
declare(strict_types=1);

namespace App\Core\Guard;

use App\Core\Logger;

final class AuthGuard {
    //Guard responsável por validar autenticação de sessão, perfil e auditar acessos

    //método para verificar o perfil e autenticação
    public static function proteger(array $perfisPermitidos = []): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!self::usuarioLogado()) {
            Logger::aviso("Acesso anônimo redirecionado para login", ['uri' => $_SERVER['REQUEST_URI'] ?? '/']);
            header("Location: /login");
            exit;
        }
        $perfilAtual = self::obterPerfil();
        if (!empty($perfisPermitidos) && !in_array($perfilAtual, $perfisPermitidos, true)) {
            Logger::aviso("Acesso 403 negado por perfil incompatível", [
                'usuario_id' => self::obterUsuarioId(),
                'perfil'     => $perfilAtual,
                'uri'        => $_SERVER['REQUEST_URI'] ?? '/'
            ]);
            self::negarAcesso();
        }
    }

    //verifica se existe um usuario autenticado
    public static function usuarioLogado(): bool {
        return !empty($_SESSION['usuario_id']);
    }

    // Retorna o perfil do usuário
    public static function obterPerfil(): string {
        return (string)($_SESSION['usuario_perfil'] ?? '');
    }

    //Obter o Id do Usuário
    public static function obterUsuarioId(): ?int {
        return isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;
    }

    //negar acesso ao Usuário
    private static function negarAcesso(): void {
        http_response_code(403);
        echo "<h1 style='font-family:sans-serif;color:#dc2626;text-align:center;margin-top:60px;'>403 — Acesso Proibido</h1>";
        echo "<p style='font-family:sans-serif;text-align:center;'>Seu perfil não tem permissão para acessar este recurso.</p>";
        echo "<p style='font-family:sans-serif;text-align:center;'><a href='/'>Voltar para o Painel</a></p>";
        exit;
    }
}