<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($titulo ?? 'produtos') }}</title>
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
    </style>
</head>
<body>
<header>
    <h1>🏭 SENAI Client Flow — Gestão de Produtos</h1>
    <a href="{{ route('clientes.index') }}" style="color:#94a3b8; text-decoration:none; font-size:0.85rem;">← Módulo de Clientes</a>
</header>
<div class="container">
    @if(session('sucesso'))
        <div class="alerta-sucesso">✅ {{ session('sucesso') }}</div>
    @endif

    <div class="card">
        <div class="topo-acoes">
            <h2>{{ ucfirst($titulo ?? 'produtos') }}</h2>
            <a href="{{ route('produtos.create') }}" class="btn-novo">+ Cadastrar Produto</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                    <th>Ações RESTful</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produtos as $produto)
                    <tr>
                        <td>#{{ $produto->id }}</td>
                        <td><strong>{{ $produto->nome }}</strong></td>
                        <td>R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                        <td><span class="badge">{{ $produto->quantidade }}</span></td>
                        <td>
                            <div class="acoes-col">
                                <a href="{{ route('produtos.show', $produto->id) }}" class="btn-acao">Ficha</a>
                                <a href="{{ route('produtos.edit', $produto->id) }}" class="btn-acao">Editar</a>

                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Deseja excluir este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#94a3b8;">Nenhum produto cadastrado ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>