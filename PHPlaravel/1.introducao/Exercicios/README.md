# Parte A – Questões Teóricas de Fixação: Laravel 11

### 1. Evolução Arquitetural
O MVC artesanal da Semana 12 ensinou a separar as responsabilidades do sistema entre Model, View e Controller. Também permitiu compreender conceitos como rotas, conexão com banco de dados e organização do código. Esses conhecimentos facilitaram o entendimento do Laravel, que utiliza essa mesma arquitetura de forma mais organizada e automatizada.

### 2. A Nova Arquitetura do Laravel 11
No Laravel 11, a estrutura do projeto foi simplificada, reduzindo a quantidade de arquivos de configuração. O principal ponto central de inicialização e configuração passou a ser o arquivo `bootstrap/app.php`, responsável por configurar componentes como rotas, middlewares e tratamento de exceções.

### 3. Padrão 12-Factor App
Gravar senhas diretamente no arquivo `config/database.php` é uma falha de segurança, pois essas informações podem ser expostas quando o código é compartilhado pelo GitHub. O arquivo `.env` permite armazenar dados sensíveis separados do código-fonte, evitando sua exposição no repositório.

### 4. O Papel da APP_KEY
Alterar a `APP_KEY` de uma aplicação em produção pode causar a perda de acesso a dados criptografados anteriormente, como cookies e informações de sessões. Isso pode deslogar usuários e impedir a recuperação de dados protegidos com a chave antiga.

### 5. Isolamento de Diretórios
O servidor web deve apontar somente para a pasta `public/` porque ela contém os arquivos que podem ser acessados externamente. Isso protege arquivos internos, como `.env`, configurações, códigos-fonte e informações sensíveis do projeto.

### 6. Artisan CLI
O comando `php arquivo.php` executa diretamente um arquivo PHP específico. Já o `php artisan` utiliza uma ferramenta própria do Laravel para executar tarefas administrativas, como criar controllers, gerar migrations, iniciar servidores e limpar caches.

### 7. O Arquivo .env.example
O arquivo `.env.example` serve como modelo de configuração para outros desenvolvedores. Ele facilita a instalação do projeto, indicando quais variáveis precisam ser configuradas sem expor senhas ou informações confidenciais.

### 8. Motor de Templates Blade
A diretiva `{{ $variavel }}` realiza o escape automático do conteúdo, ajudando a proteger a aplicação contra ataques XSS. Já `{!! $variavel !!}` exibe o conteúdo sem escapar o HTML, podendo permitir a execução de scripts maliciosos quando recebe dados não confiáveis.

### 9. Responsabilidade do Controller
Um Controller deve ter métodos curtos e objetivos, cuidando principalmente de receber requisições e direcionar as respostas. Consultas SQL e regras de negócio extensas devem ficar em Models ou classes específicas de serviços, facilitando a manutenção, organização e reutilização do código.

### 10. A Ferramenta Tinker
O comando `php artisan tinker` abre um ambiente interativo para executar códigos PHP dentro do contexto da aplicação Laravel. Ele permite testar Models, consultar registros do banco de dados e verificar funções sem precisar criar arquivos ou telas temporárias.