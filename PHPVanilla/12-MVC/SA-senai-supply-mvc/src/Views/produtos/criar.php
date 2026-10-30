<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2><?= htmlspecialchars($titulo) ?></h2>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/produtos/salvar" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <div class="form-group">
            <label for="nome">Nome do Insumo Industrial</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex.: Broca Helicoidal HSS 10mm">
        </div>
        <div class="form-group">
            <label for="categoria">Categoria Técnica</label>
            <select id="categoria" name="categoria" required>
                <option value="Usinagem">Usinagem</option>
                <option value="Furação">Furação</option>
                <option value="Metrologia">Metrologia</option>
                <option value="Lubrificação">Lubrificação</option>
                <option value="Fixação">Fixação</option>
            </select>
        </div>
        <div class="form-group">
            <label for="preco">Preço Unitário (R$)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0.01" required placeholder="0.00">
        </div>
        <div class="form-group">
            <label for="estoque">Estoque Inicial</label>
            <input type="number" id="estoque" name="estoque" min="0" required placeholder="0">
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
            <a href="/produtos" style="color:#94a3b8; text-decoration:none; font-size:0.9rem;">← Cancelar</a>
            <button type="submit" class="btn">Cadastrar Insumo</button>
        </div>
    </form>
</div>