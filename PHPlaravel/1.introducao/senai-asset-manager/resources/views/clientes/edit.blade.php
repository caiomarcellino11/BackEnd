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
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 0.85rem; margin-bottom: 6px; color: #cbd5e1; font-weight: 600; }
        input, select { width: 100%; padding: 10px; background: #0f172a; border: 1px solid #475569; border-radius: 6px; color: #f8fafc; box-sizing: border-box; }
        .btn-salvar { background: #0284c7; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .btn-salvar:hover { background: #0369a1; }
        .link-voltar { color: #94a3b8; text-decoration: none; font-size: 0.9rem; margin-right: 15px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>⚙️ {{ $titulo }}</h1>

        <!-- Formulario POST com simulacao Method Spoofing para PUT -->
        <form action="{{ route('clientes.update', $cliente['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="razao_social">Razão Social</label>
                <input type="text" id="razao_social" name="razao_social" value="{{ $cliente['razao_social'] }}" required>
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ Corporativo</label>
                <input type="text" id="cnpj" name="cnpj" value="{{ $cliente['cnpj'] }}" readonly style="opacity: 0.6;">
            </div>

            <div class="form-group">
                <label for="setor">Setor Industrial</label>
                <select id="setor" name="setor">
                    <option value="Autopeças" @selected($cliente['setor'] === 'Autopeças')>Autopeças</option>
                    <option value="Usinagem" @selected($cliente['setor'] === 'Usinagem')>Usinagem</option>
                    <option value="Robótica" @selected($cliente['setor'] === 'Robótica')>Robótica</option>
                    <option value="Siderurgia" @selected($cliente['setor'] === 'Siderurgia')>Siderurgia</option>
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; align-items:center; margin-top:20px;">
                <a href="{{ route('clientes.show', $cliente['id']) }}" class="link-voltar">← Cancelar</a>
                <button type="submit" class="btn-salvar">Salvar Alterações (PUT)</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>