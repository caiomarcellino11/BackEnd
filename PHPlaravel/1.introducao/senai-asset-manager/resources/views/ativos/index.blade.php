<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? config('app.name') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; color: #f8fafc; }
        header { background: #1e293b; padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; }
        header h1 { font-size: 1.3rem; color: #38bdf8; margin: 0; }
        .badge-env { background: #0284c7; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .container { max-width: 980px; margin: 30px auto; padding: 0 20px; }
        .card { background: #1e293b; border-radius: 10px; padding: 25px; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); }
        .grid-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .stat-box { background: #0f172a; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #334155; }
        .stat-box span { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; font-weight: bold; }
        .stat-box strong { display: block; font-size: 2rem; color: #38bdf8; margin-top: 8px; }
        .footer-note { text-align: center; margin-top: 40px; color: #64748b; font-size: 0.85rem; border-top: 1px solid #1e293b; padding-top: 20px; }
        .btn-status { display: inline-block; background: #10b981; color: #022c22; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.85rem; margin-top: 15px; }
        .btn-status:hover { background: #34d399; }
    </style>
</head>
<body>
<header>
    <h1> {{ config('app.name') }} — Manufatura 4.0</h1>
    <div>
        <span class="badge-env">{{ config('app.env') }}</span>
    </div>
</header>
<div class="container">
    <div class="card">
        <h2>{{ $titulo }}</h2>
        <p style="color: #94a3b8;">Ambiente de Gestão Integrada de Máquinas e Equipamentos operando sobre Laravel 11.</p>

        <div class="grid-stats">
            <div class="stat-box">
                <span>Total de Ativos</span>
                <strong>{{ $metricas['totalAtivos'] }}</strong>
            </div>
            <div class="stat-box">
                <span>Em Operação</span>
                <strong style="color: #4ade80;">{{ $metricas['emOperacao'] }}</strong>
            </div>
            <div class="stat-box">
                <span>Em Manutenção</span>
                <strong style="color: #facc15;">{{ $metricas['emManutencao'] }}</strong>
            </div>
            <div class="stat-box">
                <span>Eficiência Global</span>
                <strong style="color: #38bdf8;">{{ $metricas['taxaEficiencia'] }}%</strong>
            </div>
        </div>

        <div style="margin-top: 25px; text-align: right;">
            <a href="{{ route('ativos.status') }}" target="_blank" class="btn-status">Inspecionar Endpoint de Diagnóstico JSON ➔</a>
        </div>
    </div>

    <div class="footer-note">
        SENAI-SP — Habilitação Técnica em Desenvolvimento de Sistemas | Módulo 3: Framework Laravel 11.x & Artisan CLI
    </div>
</div>
</body>
</html>