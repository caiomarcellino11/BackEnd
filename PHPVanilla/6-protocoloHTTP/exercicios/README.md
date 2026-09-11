## Parte A: Exercícios teóricos de fixação



## 1. Diferença Estrutural: GET x POST

###  Pergunta

Explique a diferença física entre onde os dados são anexados em uma requisição GET e em uma requisição POST.

###  Resposta

No método **GET**, os dados são anexados à própria **URL**, por meio da query string.

Exemplo:

```text
pagina.php?nome=Caio&idade=18
```

No método **POST**, os dados são enviados no **corpo (body) da requisição HTTP**, não ficando diretamente visíveis na URL.

###  Resumindo

* **GET:** dados ficam na URL.
* **POST:** dados ficam no corpo da requisição.

---

## 2. Segurança e Privacidade

###  Pergunta

Por que senhas de usuário nunca devem ser enviadas via método GET? Cite pelo menos dois locais onde essa senha ficaria gravada de forma insegura.

###  Resposta

Senhas não devem ser enviadas utilizando **GET** porque os dados ficam visíveis na URL.

Essa informação pode acabar sendo armazenada em locais como:

* Histórico do navegador;
* Logs de servidores.

Por isso, informações sensíveis, como senhas, devem ser enviadas utilizando o método **POST**.

---

## 3. Coalescência Nula

###  Pergunta

Por que a instrução `$nome = $_POST['nome'];` dispara um Warning na primeira vez que a página é carregada no navegador? Como o operador `??` resolve isso?

###  Resposta

Quando a página é aberta pela primeira vez, o formulário ainda não foi enviado. Portanto, a chave `nome` ainda não existe dentro de `$_POST`.

Ao utilizar:

```php
$nome = $_POST['nome'];
```

o PHP tenta acessar uma chave que ainda não existe, podendo gerar um aviso de **Undefined array key**.

O operador `??` permite definir um valor padrão caso a chave não exista:

```php
$nome = $_POST['nome'] ?? '';
```

Nesse caso, se `nome` não existir, uma string vazia será utilizada.

---

## 4. Idempotência

###  Pergunta

O que significa dizer que uma requisição GET é idempotente? Por que atualizar ou deletar dados no banco usando links GET é uma má prática de segurança?

###  Resposta

Dizer que uma requisição **GET é idempotente** significa que realizar a mesma requisição várias vezes não deve causar alterações no estado dos dados.

GET deve ser utilizado principalmente para **consultar informações**.

Utilizar links GET para atualizar ou deletar dados de um banco de dados é uma má prática porque uma simples visita ou atualização da página poderia executar uma ação que altera os dados.

Por exemplo:

```text
produto.php?acao=deletar&id=10
```

Um link desse tipo poderia causar uma exclusão apenas ao ser acessado.

---

## 5. Validação Client-Side x Server-Side

###  Pergunta

Um desenvolvedor júnior afirma que o formulário dele é 100% seguro porque colocou `required` e `type="email"` em todas as tags HTML. Explique por que essa afirmação é falsa.

###  Resposta

A afirmação é falsa porque `required` e `type="email"` são validações realizadas no **lado do cliente (client-side)**.

Essas validações podem ser desativadas ou ignoradas.

Por isso, o servidor também precisa verificar os dados recebidos. Essa validação é chamada de **server-side**.

O PHP não deve confiar somente nas validações realizadas pelo navegador.

---

## 6. XSS e Sanitização

###  Pergunta

Qual é o risco de exibir dados vindos de um `$_POST` diretamente na tela sem utilizar `htmlspecialchars()`?

###  Resposta

O principal risco é o **XSS (Cross-Site Scripting)**.

Isso pode acontecer quando dados enviados pelo usuário são exibidos diretamente na página sem serem tratados corretamente.

A função:

```php
htmlspecialchars()
```

converte caracteres especiais em entidades HTML, ajudando a impedir que o conteúdo enviado pelo usuário seja interpretado como código HTML.

Exemplo:

```php
echo htmlspecialchars($nome);
```

Assim, dados recebidos de formulários podem ser exibidos de maneira mais segura.

---

## 7. Sticky Forms

###  Pergunta

O que é a técnica de Sticky Forms e qual é o seu impacto na experiência do usuário (UX)?

###  Resposta

**Sticky Forms** é uma técnica utilizada para manter os dados que o usuário já digitou no formulário após o envio.

Isso é especialmente útil quando ocorre algum erro de validação.

Por exemplo, se o usuário preencher vários campos e errar apenas um deles, os outros campos podem continuar preenchidos.

###  Impacto na UX

Essa técnica melhora a **experiência do usuário**, pois evita que seja necessário preencher novamente todos os campos do formulário.

---

## 8. DevTools — Aba Network

###  Pergunta

Como você utilizaria a aba Network do navegador para comprovar que um formulário foi enviado via POST e não via GET?

###  Resposta

Primeiro, é necessário abrir as **DevTools** do navegador e acessar a aba **Network**.

Depois, o formulário deve ser enviado.

Na lista de requisições, é possível selecionar a requisição correspondente e verificar o campo **Request Method**.

Se aparecer:

```text
GET
```

o formulário foi enviado utilizando GET.

Se aparecer:

```text
POST
```

o formulário foi enviado utilizando POST.

Dessa forma, a aba Network permite verificar diretamente qual método HTTP foi utilizado.

---

# Conceitos Principais Aprendidos

| Conceito                 | O que significa                                                              |
| ------------------------ | ---------------------------------------------------------------------------- |
| **GET**                  | Envia dados pela URL                                                         |
| **POST**                 | Envia dados no corpo da requisição                                           |
| **`??`**                 | Define um valor padrão quando uma chave não existe                           |
| **Idempotência**         | Repetir uma operação não deve causar novas alterações                        |
| **Client-side**          | Validação realizada no navegador                                             |
| **Server-side**          | Validação realizada no servidor                                              |
| **XSS**                  | Ataque relacionado à execução/interpretação de conteúdo enviado pelo usuário |
| **`htmlspecialchars()`** | Trata caracteres especiais antes da exibição                                 |
| **Sticky Form**          | Mantém dados preenchidos após o envio                                        |
| **DevTools / Network**   | Permite analisar as requisições HTTP                                         |
