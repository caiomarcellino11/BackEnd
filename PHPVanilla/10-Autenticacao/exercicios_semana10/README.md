# Questionário: Segurança Web, Sessões e Criptografia de Senhas

## 1. Protocolo Stateless
**Pergunta:** O que significa afirmar que o protocolo HTTP é "stateless" e por que o mecanismo de cookies/sessões é necessário para aplicações modernas?

> **Resposta:**
> O HTTP é considerado stateless porque cada requisição é independente, ou seja, o servidor não mantém automaticamente informações sobre as requisições anteriores. Por isso, cookies e sessões são utilizados para preservar dados como autenticação, preferências e informações do usuário durante a navegação.

---

## 2. Cookies vs. Sessões
**Pergunta:** Qual é a diferença entre os dados armazenados em um cookie e os dados mantidos no array superglobal `$_SESSION`? Qual dos dois apresenta maior segurança para informações confidenciais?

> **Resposta:**
> Os cookies armazenam dados no navegador do usuário, enquanto as sessões mantêm informações no servidor, acessadas no PHP pelo array superglobal $_SESSION. As sessões são geralmente mais seguras para informações confidenciais, pois os dados ficam no servidor e o navegador normalmente armazena apenas o identificador da sessão.

---

## 3. A Flag HttpOnly
**Pergunta:** Explique detalhadamente como a configuração `httponly => true` impede o sequestro de sessão (*Session Hijacking*) mesmo se a aplicação possuir uma vulnerabilidade de *Cross-Site Scripting* (XSS).

> **Resposta:**
> A configuração HttpOnly impede que códigos JavaScript leiam diretamente o cookie de sessão por meio de document.cookie. Isso dificulta o roubo do identificador em ataques XSS (Cross-Site Scripting) e reduz o risco de sequestro de sessão (Session Hijacking). Entretanto, não impede que um script malicioso execute ações em nome do usuário autenticado.

---

## 4. A Flag SameSite
**Pergunta:** Qual é a finalidade do atributo `SameSite=Lax` em cookies e contra qual tipo de ataque corporativo ele atua?

> **Resposta:**
> O atributo SameSite=Lax restringe o envio de cookies em determinadas requisições originadas de outros sites, ajudando a proteger contra ataques CSRF (Cross-Site Request Forgery). Nesse ataque, um invasor tenta induzir o navegador de um usuário autenticado a realizar ações indesejadas em outro site, utilizando sua sessão ativa.

---

## 5. Ataque de Fixação de Sessão
**Pergunta:** O que é o ataque de *Session Fixation* e por que é mandatório executar `session_regenerate_id(true)` imediatamente após o login do usuário?

> **Resposta:**
> O Session Fixation ocorre quando um invasor tenta fazer com que a vítima utilize um identificador de sessão conhecido por ele. Após o login bem-sucedido, a função session_regenerate_id(true) deve ser executada para gerar um novo identificador e excluir os dados associados ao identificador anterior, dificultando que o invasor reutilize a sessão autenticada.

---

## 6. MD5 e Rainbow Tables
**Pergunta:** Por que é considerado negligência técnica armazenar senhas com algoritmos rápidos como MD5 ou SHA256? Como as *Rainbow Tables* operam contra esses hashes?

> **Resposta:**
> MD5 e SHA-256 são inadequados para armazenar senhas diretamente porque são algoritmos rápidos, permitindo testar muitas combinações em pouco tempo. As Rainbow Tables são estruturas pré-computadas que ajudam a encontrar senhas correspondentes a hashes conhecidos. Por isso, recomenda-se utilizar algoritmos específicos para senhas, como Argon2id ou bcrypt, que tornam as tentativas de descoberta mais custosas.

---

## 7. O Papel do Salt
**Pergunta:** O que é o *Salt* criptográfico e por que ele garante que dois usuários com a mesma senha possuam hashes completamente distintos no banco de dados?

> **Resposta:**
> O salt é um valor aleatório e exclusivo utilizado no processo de geração do hash de uma senha. Ele faz com que dois usuários com a mesma senha possam ter hashes diferentes, dificultando a identificação de senhas iguais e o uso de tabelas pré-computadas. No PHP, a função password_hash() gera e armazena automaticamente as informações necessárias para a verificação posterior.

---

## 8. Argon2id vs Bcrypt
**Pergunta:** Por que o algoritmo Argon2id é considerado superior ao Bcrypt na proteção contra ataques de força bruta realizados por placas de vídeo (GPUs) e circuitos ASIC?

> **Resposta:**
> O Argon2id permite configurar o consumo de memória e o custo de processamento, dificultando ataques de força bruta realizados com GPUs e circuitos ASIC. O bcrypt também é um algoritmo seguro quando configurado corretamente, mas o Argon2id oferece maior flexibilidade no uso de memória, aumentando o custo de ataques paralelos. Por isso, é frequentemente recomendado para novos sistemas quando está disponível.

---

## 9. Timing Attacks
**Pergunta:** Por que a verificação de senhas deve ser feita com `password_verify()` em vez do operador de comparação comum `===`?

> **Resposta:**
>Um Timing Attack explora diferenças no tempo de execução de operações para tentar descobrir informações confidenciais. Comparações comuns podem apresentar variações de tempo dependendo dos valores comparados. Por isso, a função password_verify() deve ser utilizada para verificar senhas armazenadas com password_hash(), pois realiza a verificação apropriada do hash e reduz os riscos associados a comparações inadequadas.