# PARTE A – QUESTÕES TEÓRICAS DE FIXAÇÃO
## Controllers e Routes no Laravel

**1. Semântica RESTful:**

Uma ação de exclusão não deve utilizar o método GET porque ele é destinado apenas à consulta de informações, sem modificar dados. Caso contrário, robôs de busca poderiam acessar automaticamente a URL e excluir registros sem intenção do usuário.

**2. Idempotência:**

Idempotência significa que executar uma mesma requisição várias vezes produz o mesmo resultado final que executá-la apenas uma vez. O método PUT é idempotente porque mantém o mesmo resultado ao atualizar um recurso com os mesmos dados. Já o POST pode criar novos registros a cada envio.

**3. Resource Controllers:**

As sete ações são: index, responsável por listar registros; create, responsável por exibir o formulário de cadastro; store, responsável por salvar um novo registro; show, responsável por exibir os detalhes de um registro; edit, responsável por exibir o formulário de edição; update, responsável por atualizar um registro; e destroy, responsável por excluir um registro.

**4. Injeção de Dependências:**

O Service Container do Laravel identifica automaticamente a classe Request informada como parâmetro do método e fornece uma instância pronta. Esse processo é chamado de injeção de dependências e evita a necessidade de criar objetos manualmente.

**5. Method Spoofing:**

Os formulários HTML convencionais aceitam nativamente apenas os métodos GET e POST. O Laravel contorna essa limitação utilizando a diretiva @method(), que cria um campo oculto informando o método HTTP desejado, permitindo trabalhar com PUT, PATCH e DELETE.

**6. Ataques CSRF:**

CSRF é um ataque no qual um site malicioso tenta realizar ações em outro sistema utilizando a sessão autenticada de um usuário. A diretiva @csrf gera um token de segurança que é verificado pelo Laravel antes de aceitar a requisição, dificultando ações falsificadas.

**7. Constraints de Rota:**

As constraints servem para restringir os valores aceitos nos parâmetros das rotas, garantindo que estejam no formato esperado. Quando um parâmetro não atende à restrição definida, o Laravel retorna o status HTTP 404 (Not Found).

**8. Rotas Nomeadas:**

As rotas nomeadas facilitam a manutenção do sistema, pois permitem alterar os endereços das rotas sem precisar modificar todos os links das views. Também melhoram a organização do código e reduzem erros relacionados à escrita manual de URLs.

**9. Single Responsibility no Controller:**

A validação deve ser mantida enxuta para evitar que o controller acumule responsabilidades. Utilizar validações simples ou Form Requests facilita a organização, manutenção e reutilização do código, além de ajudar a respeitar o limite de 20 linhas por função.

**10. A Ferramenta route:list:**

O comando route:list apresenta todas as rotas cadastradas na aplicação, seus métodos HTTP, endereços, nomes e controllers responsáveis. Ele facilita a documentação, consulta e auditoria dos endpoints, ajudando as equipes a identificar conflitos e configurações incorretas.