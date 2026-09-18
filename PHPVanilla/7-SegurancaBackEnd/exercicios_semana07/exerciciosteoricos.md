# 🔐 Segurança e Higienização de Dados

## 📚 Sobre o Projeto

Este projeto foi desenvolvido durante os estudos de **Desenvolvimento de Sistemas no SENAI**, com foco em **segurança de aplicações web e tratamento de dados em PHP**.

A atividade aborda conceitos importantes como **XSS (Cross-Site Scripting)**, validação de dados, `htmlspecialchars()`, proteção de sessões e boas práticas para evitar vulnerabilidades.

---

## 🛡️ XSS — Cross-Site Scripting

XSS é uma vulnerabilidade que ocorre quando dados fornecidos pelo usuário são interpretados pelo navegador como código, em vez de serem tratados apenas como texto.

Apesar de a execução acontecer no navegador, o **Back-End possui um papel importante na prevenção**, principalmente validando os dados e realizando a codificação correta antes de enviá-los para o usuário.

Existem dois tipos estudados:

* **Reflected XSS:** o conteúdo malicioso é enviado em uma requisição e refletido imediatamente na resposta.
* **Stored XSS:** o conteúdo é armazenado pela aplicação, podendo ser exibido posteriormente para outros usuários.

O Stored XSS pode atingir várias pessoas porque o conteúdo permanece armazenado no sistema.

---

## 🧹 `htmlspecialchars()`

A função `htmlspecialchars()` transforma caracteres especiais em entidades HTML, impedindo que sejam interpretados como tags.

Por exemplo:

```text
<  →  &lt;
>  →  &gt;
```

Dessa forma, um conteúdo que poderia ser interpretado como HTML passa a ser tratado como texto pelo navegador.

A flag `ENT_QUOTES` também protege **aspas simples e duplas**, sendo importante quando o valor é colocado dentro de atributos HTML, como:

```html
<input value="valor">
```

---

## ✉️ Validação de Dados

A atividade também apresenta a diferença entre verificar se um campo está preenchido e validar seu conteúdo.

```php
empty($email);
```

verifica se o campo está vazio, mas não confirma se ele possui formato de e-mail.

Já:

```php
filter_var($email, FILTER_VALIDATE_EMAIL);
```

verifica se o endereço possui um formato válido.

Também foi estudado que `FILTER_SANITIZE_STRING` não deve ser utilizado em projetos modernos, pois foi depreciado no PHP 8.1.

---

## 🍪 Proteção de Sessões

Uma vulnerabilidade XSS pode representar riscos para informações relacionadas à sessão do usuário, principalmente quando cookies importantes podem ser acessados por JavaScript.

Uma das medidas de proteção é utilizar cookies com configurações como **`HttpOnly`**, além de `Secure` e `SameSite`, reduzindo determinados riscos relacionados ao acesso e envio dessas informações.

---

## 🏗️ Segurança em Camadas

A segurança não deve depender de uma única função.

A validação, o tratamento dos dados e a codificação da saída possuem funções diferentes e devem trabalhar em conjunto.

Um fluxo básico pode ser:

```text
Entrada
   ↓
Validação
   ↓
Processamento
   ↓
Armazenamento
   ↓
Codificação da saída
   ↓
Navegador
```

Por isso, utilizar apenas `strip_tags()` na entrada não substitui a utilização de `htmlspecialchars()` quando o conteúdo será exibido em HTML.

---

## 🎯 Objetivo do Aprendizado

O principal objetivo da atividade foi compreender como **validar, tratar e exibir dados de forma mais segura**, evitando vulnerabilidades comuns em aplicações web.

Com esses conceitos, é possível desenvolver aplicações PHP mais seguras e aplicar boas práticas de **Back-End e segurança da informação**.

---

## 👨‍💻 Autor

**Caio Martins**

Estudante de **Desenvolvimento de Sistemas — SENAI**

---

### 🧰 Tecnologias e Conceitos

`PHP` • `HTML5` • `XSS` • `OWASP` • `Validação de Dados` • `htmlspecialchars()` • `Cookies` • `Sessões` • `Segurança Web`
