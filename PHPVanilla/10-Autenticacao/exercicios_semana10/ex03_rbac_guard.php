<?php
declare(strict_types=1);

// Chaves de $_SESSION gravadas no login (ajuste se o seu login.php usar outras)
const CHAVE_USUARIO = 'usuario_id';
const CHAVE_PERFIL  = 'perfil';

// Hierarquia de perfis: quanto maior o número, maior o poder de acesso
const NIVEIS_PERFIL = ['OPERADOR' => 1, 'ADMIN' => 2];

/** Converte o nome do perfil em nível numérico (0 = desconhecido). */
function nivelDoPerfil(string $perfil): int
{
    return NIVEIS_PERFIL[strtoupper($perfil)] ?? 0;
}

/** Garante que a sessão esteja ativa (o guard.php já deve tê-la iniciado). */
function garantirSessaoAtiva(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/** Indica se existe um usuário autenticado na sessão. */
function usuarioLogado(): bool
{
    return !empty($_SESSION[CHAVE_USUARIO]);
}

/** Verifica se o perfil da sessão atinge o nível mínimo exigido. */
function temPermissao(string $perfilMinimo): bool
{
    $exigido = nivelDoPerfil($perfilMinimo);
    $atual   = nivelDoPerfil((string) ($_SESSION[CHAVE_PERFIL] ?? ''));
    // Falha fechada: perfil exigido inválido ou usuário sem perfil = negado
    return $exigido > 0 && $atual >= $exigido;
}

/** Monta o HTML amigável da tela de permissão negada. */
function paginaNegada(string $perfilAtual, string $perfilMinimo): string
{
    $atual  = htmlspecialchars($perfilAtual !== '' ? $perfilAtual : 'desconhecido', ENT_QUOTES, 'UTF-8');
    $minimo = htmlspecialchars($perfilMinimo, ENT_QUOTES, 'UTF-8');
    return <<<HTML
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head><meta charset="UTF-8"><title>Acesso negado</title>
    <style>body{font-family:sans-serif;text-align:center;margin-top:12vh}h1{color:#b00020}</style></head>
    <body>
        <h1>Acesso negado (403)</h1>
        <p>Seu perfil (<strong>{$atual}</strong>) não tem permissão para acessar esta página.</p>
        <p>Ela exige o perfil <strong>{$minimo}</strong>.</p>
        <p><a href="dashboard.php">Voltar ao painel</a></p>
    </body>
    </html>
    HTML;
}

/** Responde 403 Forbidden com a tela de aviso e encerra a requisição. */
function negarAcesso(string $perfilMinimo): void
{
    http_response_code(403);
    header('Content-Type: text/html; charset=UTF-8');
    echo paginaNegada((string) ($_SESSION[CHAVE_PERFIL] ?? ''), $perfilMinimo);
    exit;
}

/** Middleware RBAC: exige login e perfil mínimo; senão redireciona ou nega. */
function verificarPermissao(string $perfilMinimo): void
{
    garantirSessaoAtiva();
    if (!usuarioLogado()) {
        header('Location: login.php');
        exit;
    }
    if (!temPermissao($perfilMinimo)) {
        negarAcesso($perfilMinimo);
    }
}