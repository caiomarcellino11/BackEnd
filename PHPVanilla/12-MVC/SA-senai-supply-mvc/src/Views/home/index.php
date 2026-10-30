<div class="card">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <p style="color: #94a3b8;">Visão integrada do almoxarifado sob arquitetura MVC artesanal com controle de acesso.</p>

    <div class="grid-stats">
        <div class="stat-box">
            <span>Variedade de Insumos</span>
            <strong><?= (int)$totalInsumos ?></strong>
        </div>
        <div class="stat-box">
            <span>Saldo Físico Total</span>
            <strong style="color: #4ade80;"><?= (int)$totalEstoque ?> un</strong>
        </div>
        <div class="stat-box">
            <span>Camada de Segurança</span>
            <strong style="font-size: 1.1rem; color: #f59e0b;">Guards & RBAC</strong>
        </div>
    </div>

    <h3 style="margin-top:35px; border-bottom:1px solid #334155; padding-bottom:8px;">Últimas Movimentações no Estoque</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Insumo</th>
                <th>Operação</th>
                <th>Qtd</th>
                <th>Responsável</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ultimasMovimentacoes)): ?>
                <tr><td colspan="5" style="text-align:center; color:#94a3b8;">Nenhuma movimentação registrada.</td></tr>
            <?php else: ?>
                <?php foreach ($ultimasMovimentacoes as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($m['criado_em']))) ?></td>
                        <td><?= htmlspecialchars($m['produto_nome']) ?></td>
                        <td>
                            <span class="<?= $m['tipo'] === 'ENTRADA' ? 'badge-entrada' : 'badge-saida' ?>">
                                <?= htmlspecialchars($m['tipo']) ?>
                            </span>
                        </td>
                        <td><strong><?= (int)$m['quantidade'] ?> un</strong></td>
                        <td><?= htmlspecialchars($m['usuario_nome']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>