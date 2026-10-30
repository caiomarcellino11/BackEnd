<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <p style="color:#94a3b8; font-size:0.9rem;">
        Insumo: <strong><?= htmlspecialchars($produto['nome']) ?></strong> | Saldo Atual: <span style="color:#4ade80; font-weight:bold;"><?= (int)$produto['estoque'] ?> un</span>
    </p>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/movimentacoes/salvar" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <input type="hidden" name="produto_id" value="<?= (int)$produto['id'] ?>">

        <div class="form-group">
            <label for="tipo">Tipo de Movimentação</label>
            <select id="tipo" name="tipo" required>
                <option value="ENTRADA">Entrada (+ Aumenta saldo físico)</option>
                <option value="SAIDA">Saída (- Requisição para produção)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="quantidade">Quantidade de Unidades</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required placeholder="Ex.: 5">
        </div>

        <div class="form-group">
            <label for="motivo">Motivo / Justificativa da Movimentação</label>
            <input type="text" id="motivo" name="motivo" required placeholder="Ex.: Ordem de Serviço OS-402, Reabastecimento NF 9912">
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
            <a href="/produtos" style="color:#94a3b8; text-decoration:none; font-size:0.9rem;">← Voltar ao Catálogo</a>
            <button type="submit" class="btn" style="background:#059669;">Efetivar Movimentação</button>
        </div>
    </form>
</div>