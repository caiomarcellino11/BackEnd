<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2><?= htmlspecialchars($titulo) ?></h2>
        <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['almoxarife', 'admin'])): ?>
            <a href="/produtos/novo" class="btn">+ Novo Insumo</a>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cód</th>
                <th>Nome do Insumo</th>
                <th>Categoria</th>
                <th>Preço Unitário</th>
                <th>Estoque</th>
                <th style="text-align:right;">Ações de Almoxarifado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr><td colspan="6" style="text-align:center; color:#94a3b8;">Nenhum insumo encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td>#<?= (int)$p['id'] ?></td>
                        <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td>R$ <?= number_format((float)$p['preco'], 2, ',', '.') ?></td>
                        <td>
                            <span style="color: <?= (int)$p['estoque'] < 10 ? '#f87171' : '#4ade80' ?>; font-weight:bold;">
                                <?= (int)$p['estoque'] ?> un
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <?php if (in_array($_SESSION['usuario_perfil'] ?? '', ['almoxarife', 'admin'])): ?>
                                <a href="/movimentacoes/novo?produto_id=<?= (int)$p['id'] ?>" class="btn" style="background:#059669; font-size:0.78rem;">
                                    ↕ Movimentar
                                </a>
                            <?php else: ?>
                                <span style="color:#64748b; font-size:0.8rem;">Somente Leitura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>