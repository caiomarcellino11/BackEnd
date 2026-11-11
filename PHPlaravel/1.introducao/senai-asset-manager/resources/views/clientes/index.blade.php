<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; color: #f8fafc; }
        header { background: #1e293b; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; }
        header h1 { font-size: 1.25rem; color: #38bdf8; margin: 0; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .card { background: #1e293b; border-radius: 8px; padding: 25px; border: 1px solid #334155; }
        .topo-acoes { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-novo { background: #0284c7; color: white; padding: 9px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.85rem; }
        .btn-novo:hover { background: #0369a1; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #334155; font-size: 0.9rem; }
        th { color: #94a3b8; background: #0f172a; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; background: #0369a1; color: white; }
        .acoes-col { display: flex; gap: 8px; align-items: center; }
        .btn-acao { text-decoration: none; font-size: 0.8rem; font-weight: bold; padding: 4px 8px; border-radius: 4px; color: #38bdf8; }
        .btn-delete { background: none; border: none; color: #f87171; cursor: pointer; font-size: 0.8rem; font-weight: bold; padding: 4px 8px; }
        .btn-delete:hover { text-decoration: underline; }
        .alerta-sucesso { background: #14532d; border: 1px solid #16a34a; color: #bbf7d0; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .filtros { display: flex; gap: 10px; margin-bottom: 15px; }
        .filtros a { color: #94a3b8; text-decoration: none; font-size: 0.85rem; padding: 4px 10px; border-radius: 4px; background: #0f172a; }
        .filtros a:hover { color: #38bdf8; }
    </style>
</head>
<body>
<header>
    <h1>🏭 SENAI Client Flow — Gestão de Parceiros</h1>
    <a href="{{ route('ativos.index') }}" style="color:#94a3b8; text-decoration:none; font-size:0.85rem;">← Módulo de Ativos</a>
</header>
<div class="container">
    @if(session('sucesso'))
        <div class="alerta-sucesso">✅ {{ session('sucesso') }}</div>
    @endif

    <div class="card">
        <div class="topo-acoes">
            <h2>{{ $titulo }}</h2>
            <a href="{{ route('clientes.create') }}" class="btn-novo">+ Cadastrar Parceiro</a>
        </div>

        <div class="filtros">
            <span>Filtrar Setor:</span>
            <a href="{{ route('clientes.index') }}">Todos</a>
            <a href="{{ route('clientes.setor', 'autopeças') }}">Autopeças</a>
            <a href="{{ route('clientes.setor', 'usinagem') }}">Usinagem</a>
            <a href="{{ route('clientes.setor', 'robótica') }}">Robótica</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Setor</th>
                    <th>Status</th>
                    <th>Ações RESTful</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                    <tr>
                        <td>#{{ $c['id'] }}</td>
                        <td><strong>{{ $c['razao_social'] }}</strong></td>
                        <td>{{ $c['cnpj'] }}</td>
                        <td><span class="badge">{{ $c['setor'] }}</span></td>
                        <td><span style="color:#4ade80;">{{ $c['status'] }}</span></td>
                        <td>
                            <div class="acoes-col">
                                <a href="{{ route('clientes.show', $c['id']) }}" class="btn-acao">Ficha</a>
                                <a href="{{ route('clientes.edit', $c['id']) }}" class="btn-acao">Editar</a>

                                <form action="{{ route('clientes.destroy', $c['id']) }}" method="POST" onsubmit="return confirm('Deseja desativar este parceiro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#94a3b8;">Nenhum parceiro industrial localizado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>