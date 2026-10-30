<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2><?= htmlspecialchars($titulo) ?></h2>
        <a href="/produtos" class="btn">Ir para o Catálogo</a>
    </div>
    <p style="color:#94a3b8; font-size:0.88rem;">Trilha de auditoria completa de entradas e retiradas de insumos do almoxarifado.</p>

    <table>
        <thead>
            <tr>
                <th>Data / Hora</th>
                <th>Insumo</th>
                <th>Operação</th>
                <th>Quantidade</th>
                <th>Operador</th>
                <th>Justificativa / Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movimentacoes)): ?>
                <tr><td colspan="6" style="text-align:center; color:#94a3b8;">Nenhum registro de movimentação encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($movimentacoes as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($m['criado_em']))) ?></td>
                        <td><strong><?= htmlspecialchars($m['produto_nome']) ?></strong></td>
                        <td>
                            <span class="<?= $m['tipo'] === 'ENTRADA' ? 'badge-entrada' : 'badge-saida' ?>">
                                <?= htmlspecialchars($m['tipo']) ?>
                            </span>
                        </td>
                        <td><strong><?= (int)$m['quantidade'] ?> un</strong></td>
                        <td><?= htmlspecialchars($m['usuario_nome']) ?></td>
                        <td><?= htmlspecialchars($m['motivo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>