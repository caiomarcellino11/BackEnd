<?php
$usuarioNome   = $_SESSION['usuario_nome'] ?? null;
$usuarioPerfil = $_SESSION['usuario_perfil'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'SENAI Supply MVC') ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; color: #f8fafc; }
        header { background: #1e293b; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; }
        header h1 { font-size: 1.15rem; color: #38bdf8; margin: 0; }
        nav { display: flex; align-items: center; gap: 15px; }
        nav a { color: #94a3b8; text-decoration: none; font-weight: 600; font-size: 0.88rem; }
        nav a:hover { color: #38bdf8; }
        .user-tag { background: #0f172a; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #334155; color: #cbd5e1; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .card { background: #1e293b; border-radius: 8px; padding: 25px; border: 1px solid #334155; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); }
        .grid-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-top: 20px; }
        .stat-box { background: #0f172a; padding: 18px; border-radius: 6px; text-align: center; border: 1px solid #334155; }
        .stat-box span { font-size: 0.78rem; color: #94a3b8; text-transform: uppercase; font-weight: bold; }
        .stat-box strong { display: block; font-size: 1.8rem; color: #38bdf8; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.88rem; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #334155; }
        th { color: #94a3b8; font-weight: 600; background: #0f172a; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 8px 14px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.85rem; border: none; cursor: pointer; }
        .btn:hover { background: #0369a1; }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .badge-entrada { background: #166534; color: #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .badge-saida { background: #991b1b; color: #fecaca; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 0.85rem; margin-bottom: 6px; color: #cbd5e1; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #475569; background: #0f172a; color: #f8fafc; box-sizing: border-box; }
        .alerta-erro { background: #7f1d1d; border: 1px solid #dc2626; color: #fecaca; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.85rem; }
    </style>
</head>
<body>
<header>
    <h1>SENAI Supply — Manufatura 4.0</h1>
    <nav>
        <?php if ($usuarioNome): ?>
            <a href="/">Painel</a>
            <a href="/produtos">Catálogo</a>
            <a href="/movimentacoes">Movimentações</a>
            <?php if (in_array($usuarioPerfil, ['almoxarife', 'admin'])): ?>
                <a href="/produtos/novo">+ Novo Insumo</a>
            <?php endif; ?>
            <span class="user-tag">👤 <?= htmlspecialchars($usuarioNome) ?> (<strong><?= htmlspecialchars((string)$usuarioPerfil) ?></strong>)</span>
            <form action="/logout" method="POST" style="display:inline;margin:0;">
                <button type="submit" class="btn btn-danger" style="padding:5px 10px;font-size:0.75rem;">Sair</button>
            </form>
        <?php else: ?>
            <a href="/login" class="btn">Fazer Login</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container"></div>