<div class="card" style="max-width: 420px; margin: 40px auto;">
    <h2 style="text-align:center; color:#38bdf8;"><?= htmlspecialchars($titulo) ?></h2>
    <p style="text-align:center; color:#94a3b8; font-size:0.9rem;">Insira suas credenciais corporativas</p>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <div class="form-group">
            <label for="email">E-mail Corporativo</label>
            <input type="email" id="email" name="email" required placeholder="operador@senai.br">
        </div>
        <div class="form-group">
            <label for="senha">Senha de Acesso</label>
            <input type="password" id="senha" name="senha" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn" style="width:100%; margin-top:10px;">Autenticar no Sistema</button>
    </form>
    <div style="margin-top:20px; font-size:0.8rem; color:#64748b; text-align:center;">
        Ambiente Didático — Senhas protegidas com BCRYPT e auditoria de log ativa
    </div>
</div>