<?php
declare(strict_types=1);

// Carga dos arquivos das classes
require_once __DIR__ . '/Exceptions/ContaException.php';
require_once __DIR__ . '/Exceptions/SaldoInsuficienteException.php';
require_once __DIR__ . '/Exceptions/ValorInvalidoException.php';
require_once __DIR__ . '/Dominio/Titular.php';
require_once __DIR__ . '/Dominio/ContaBancaria.php';
require_once __DIR__ . '/Dominio/ContaCorrente.php';
require_once __DIR__ . '/Dominio/ContaPoupanca.php';


$logsOperacionais = [];

try {
    // 1. Criacao dos titulares com dados imutaveis
    $titular1 = new Titular("Carlos Eduardo Rocha", "123.456.789-01");
    $titular2 = new Titular("Mariana Vasconcelos", "987.654.321-99");

    // 2. Instanciacao das contas
    $cc = new ContaCorrente("0001", "1010-1", $titular1, 1000.00, 300.00);
    $cp = new ContaPoupanca("0001", "2020-5", $titular2, 500.00);

    $logsOperacionais[] = "Contas criadas com sucesso para {$titular1->nome} e {$titular2->nome}.";

    // 3. Saque com cobranca de taxa na Conta Corrente
    $cc->sacar(200.00);
    $logsOperacionais[] = "Saque de R$ 200,00 efetuado na Conta Corrente (Tarifa: R$ 1,50). Saldo atual: R$ " . number_format($cc->getSaldo(), 2, ',', '.');

    // 4. Transferencia Polimorfica da Corrente para a Poupanca
    $cc->transferir(300.00, $cp);
    $logsOperacionais[] = "Transferencia de R$ 300,00 efetuada com sucesso da Conta Corrente para a Poupanca!";

    // 5. Aplicacao de juros de rendimento na Poupanca
    $cp->renderJuros(0.65);
    $logsOperacionais[] = "Rendimento de 0,65% creditado na Poupanca. Saldo atual: R$ " . number_format($cp->getSaldo(), 2, ',', '.');

    // 6. Teste de tentativa de saque invalido para simular captura de excecao
    $cp->sacar(50000.00);

} catch (ContaException $e) {
    $logsOperacionais[] = "⚠️ EXCECAO DE DOMINIO CAPTURADA: " . $e->getMessage();
} catch (\Throwable $e) {
    $logsOperacionais[] = "❌ ERRO INESPERADO: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENAI Bank — Core Banking Corporativo (PHP 8.3 POO)</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; margin: 0; color: #f8fafc; padding: 30px 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #334155; padding-bottom: 20px; }
        h1 { color: #38bdf8; margin: 0 0 8px 0; font-size: 1.8rem; }
        p.subtitle { color: #94a3b8; margin: 0; font-size: 0.95rem; }
        .grid-contas { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card-conta { background: #1e293b; border-radius: 10px; padding: 22px; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); }
        .card-conta h2 { margin: 0 0 15px 0; font-size: 1.2rem; color: #67e8f9; display: flex; justify-content: space-between; align-items: center; }
        .badge { font-size: 0.75rem; background: #0369a1; color: white; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; font-weight: bold; }
        .item-info { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #334155; font-size: 0.9rem; }
        .item-info span { color: #94a3b8; }
        .item-info strong { color: #f1f5f9; }
        .saldo-destaque { font-size: 1.6rem; color: #4ade80; text-align: center; margin-top: 15px; font-weight: bold; }
        .painel-logs { background: #020617; border-radius: 10px; padding: 20px; border: 1px solid #1e293b; }
        .painel-logs h3 { margin: 0 0 15px 0; font-size: 1.1rem; color: #fbbf24; }
        ul.logs-lista { list-style: none; padding: 0; margin: 0; }
        ul.logs-lista li { padding: 8px 12px; margin-bottom: 6px; background: #0f172a; border-left: 4px solid #38bdf8; border-radius: 4px; font-family: monospace; font-size: 0.85rem; }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>🏦 SENAI Bank — Módulo de Core Banking</h1>
        <p class="subtitle">Demonstração Prática de Encapsulamento, Herança, Polimorfismo e Exceções de Domínio em PHP 8.3</p>
    </header>

    <div class="grid-contas">
        <div class="card-conta">
            <h2>
                Conta Corrente
                <span class="badge">Tarifa Saque R$ 1,50</span>
            </h2>
            <div class="item-info"><span>Titular:</span><strong><?= htmlspecialchars($cc->titular->nome) ?></strong></div>
            <div class="item-info"><span>CPF:</span><strong><?= htmlspecialchars($cc->titular->cpf) ?></strong></div>
            <div class="item-info"><span>Agência / Conta:</span><strong><?= $cc->agencia ?> / <?= $cc->numeroConta ?></strong></div>
            <div class="item-info"><span>Cheque Especial:</span><strong>R$ <?= number_format($cc->getLimiteChequeEspecial(), 2, ',', '.') ?></strong></div>
            <div class="saldo-destaque">R$ <?= number_format($cc->getSaldo(), 2, ',', '.') ?></div>
        </div>

        <div class="card-conta">
            <h2>
                Conta Poupança
                <span class="badge" style="background: #15803d;">Rendimento Mensal</span>
            </h2>
            <div class="item-info"><span>Titular:</span><strong><?= htmlspecialchars($cp->titular->nome) ?></strong></div>
            <div class="item-info"><span>CPF:</span><strong><?= htmlspecialchars($cp->titular->cpf) ?></strong></div>
            <div class="item-info"><span>Agência / Conta:</span><strong><?= $cp->agencia ?> / <?= $cp->numeroConta ?></strong></div>
            <div class="item-info"><span>Tarifa Operacional:</span><strong>Isenta (R$ 0,00)</strong></div>
            <div class="saldo-destaque">R$ <?= number_format($cp->getSaldo(), 2, ',', '.') ?></div>
        </div>
    </div>

    <div class="painel-logs">
        <h3>📋 Log de Operações de Domínio Realizadas:</h3>
        <ul class="logs-lista">
            <?php foreach ($logsOperacionais as $log): ?>
                <li><?= htmlspecialchars($log) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
