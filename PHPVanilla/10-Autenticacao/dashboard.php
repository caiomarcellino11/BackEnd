<?php
// dashboard.php
declare(strict_types=1);

// Protecao automatica: se nao estiver logado, e redirecionado para login.php
require_once __DIR__ . '/SAAutenticacao/src/guard.php';

$nomeUsuario   = (string)$_SESSION['usuario_nome'];
$emailUsuario  = (string)$_SESSION['usuario_email'];
$perfilUsuario = (string)$_SESSION['usuario_perfil'];
$tempoAcesso   = date('d/m/Y H:i:s', $_SESSION['ultimo_acesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle — SENAI TechPortal</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; margin: 0; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 1.25rem; color: #38bdf8; }
        .user-nav { display: flex; align-items: center; gap: 15px; }
        .btn-logout { background: #e11d48; color: white; text-decoration: none; padding: 8px 14px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
        .btn-logout:hover { background: #be123c; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .badge-perfil { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 0.8rem; }
        .grid-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 20px; }
        .info-box { background: #f1f5f9; padding: 15px; border-radius: 6px; text-align: center; }
        .info-box span { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
        .info-box strong { display: block; font-size: 1.1rem; color: #0284c7; margin-top: 5px; }
    </style>
</head>
<body>
<header>
    <h1>🏭 SENAI TechPortal — Manufatura 4.0</h1>
    <div class="user-nav">
        <span>Olá, <strong><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></strong></span>
        <span class="badge-perfil"><?= htmlspecialchars($perfilUsuario, ENT_QUOTES, 'UTF-8') ?></span>
        <a href="logout.php" class="btn-logout">Sair do Sistema</a>
    </div>
</header>
<div class="container">
    <div class="card">
        <h2>Área Restrita Homologada com Sucesso</h2>
        <p>Você acessou uma página restrita protegida por <strong>Middleware de Sessão</strong> e <strong>Cookies HttpOnly</strong>.</p>
        
        <div class="grid-info">
            <div class="info-box">
                <span>E-mail Corporativo</span>
                <strong><?= htmlspecialchars($emailUsuario, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
            <div class="info-box">
                <span>Último Acesso</span>
                <strong><?= $tempoAcesso ?></strong>
            </div>
            <div class="info-box">
                <span>Status da Sessão</span>
                <strong style="color: #16a34a;">🟢 Autenticado</strong>
            </div>
        </div>
    </div>
</div>
</body>
</html>