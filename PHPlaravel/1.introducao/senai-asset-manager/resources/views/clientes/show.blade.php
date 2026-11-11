<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; color: #f8fafc; }
        .container { max-width: 650px; margin: 40px auto; padding: 0 20px; }
        .card { background: #1e293b; padding: 30px; border-radius: 10px; border: 1px solid #334155; }
        h1 { font-size: 1.4rem; color: #38bdf8; margin-top: 0; }
        .item-info { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #334155; }
        .item-info span { color: #94a3b8; font-size: 0.9rem; }
        .item-info strong { color: #f1f5f9; font-size: 0.95rem; }
        .botoes { display: flex; justify-content: space-between; margin-top: 25px; }
        .btn { padding: 9px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.85rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>📋 {{ $titulo }}</h1>

        <div class="item-info"><span>Identificador:</span><strong>#{{ $cliente['id'] }}</strong></div>
        <div class="item-info"><span>Razão Social:</span><strong>{{ $cliente['razao_social'] }}</strong></div>
        <div class="item-info"><span>CNPJ Corporativo:</span><strong>{{ $cliente['cnpj'] }}</strong></div>
        <div class="item-info"><span>Segmento Industrial:</span><strong>{{ $cliente['setor'] }}</strong></div>
        <div class="item-info"><span>Status Operacional:</span><strong style="color:#4ade80;">{{ $cliente['status'] }}</strong></div>

        <div class="botoes">
            <a href="{{ route('clientes.index') }}" style="color:#94a3b8; text-decoration:none;">← Voltar à Lista</a>
            <a href="{{ route('clientes.edit', $cliente['id']) }}" class="btn" style="background:#0284c7; color:white;">Editar Cadastro</a>
        </div>
    </div>
</div>
</body>
</html>