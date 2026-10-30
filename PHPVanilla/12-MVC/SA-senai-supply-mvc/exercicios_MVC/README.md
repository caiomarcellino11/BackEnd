## LISTA DE EXERCÍCIOS DE FIXAÇÃO E PRÁTICA

### Parte A: Questões Teóricas de Fixação

1. **O Colapso do Código Espaguete:** Explique por que manter consultas SQL, regras de validação e marcação HTML no mesmo arquivo inviabiliza a manutenção e a escalabilidade de um sistema corporativo.

R. Quando SQL, validação e HTML ficam no mesmo arquivo, tudo passa a depender de tudo. Mudar uma coluna do banco ou o visual de uma tela exige mexer em um arquivo enorme, e qualquer ajuste pode quebrar algo sem relação. Os mesmos trechos acabam copiados em várias páginas, as regras não podem ser testadas isoladamente e a equipe tem conflitos por editar o mesmo arquivo. Em um sistema corporativo, que cresce com o tempo, isso não se sustenta.

2. **A Tríade MVC:** Qual é a função específica de cada um dos três componentes do padrão MVC (Model, View, Controller)? Qual deles deve conter as regras de negócio?

R. O **Model** cuida dos dados: conversa com o banco e aplica as regras de negócio. A **View** cuida da apresentação: recebe os dados prontos e só os exibe em HTML. O **Controller** é o intermediário: recebe a requisição, chama o Model e escolhe qual View mostrar. As regras de negócio ficam no **Model**.

3. **O Papel do Composer:** Diferencie o papel do arquivo composer.json do arquivo composer.lock. Por que a pasta vendor/ nunca deve ser versionada no Git?

R. O `composer.json` é escrito pelo desenvolvedor e declara as dependências, as versões aceitas e o autoload. O `composer.lock` é gerado automaticamente e guarda as versões **exatas** instaladas, garantindo que toda a equipe e o servidor usem o mesmo ambiente (por isso ele vai para o Git).

A pasta `vendor/` não é versionada porque pode ser recriada a qualquer momento com `composer install`, e é muito pesada, cheia de código de terceiros. Ela vai no `.gitignore`.

4. **A Especificação PSR-4:** Como o Autoloader PSR-4 consegue localizar o arquivo src/Controllers/ProdutoController.php a partir do namespace App\Controllers\ProdutoController?

R. No `composer.json` se define que o prefixo `App\` corresponde à pasta `src/`:

```json
"autoload": {
    "psr-4": {
        "App\\": "src/"
    }
}
```

Para `App\Controllers\ProdutoController`, o autoloader troca `App\` por `src/`, converte as barras invertidas (`\`) em barras normais (`/`) e adiciona `.php`, chegando em `src/Controllers/ProdutoController.php`. Para isso, o nome do arquivo precisa ser igual ao da classe.

5. **Front Controller:** Qual é a principal vantagem de segurança ao centralizar todas as requisições em public/index.php e isolar os arquivos-fonte do projeto fora da raiz pública da web?

R. A vantagem é reduzir a superfície de ataque. Só a pasta `public/` fica acessível pela web, então ninguém consegue abrir pela URL arquivos internos como `src/Models/Produto.php`. Configurações e credenciais do banco também ficam fora do alcance do navegador, e como toda requisição passa pelo `index.php`, é possível aplicar tratamento de erros e verificações em um só lugar.

6. **Roteador HTTP:** Como o objeto Roteador diferencia uma requisição GET /produtos de uma requisição POST /produtos?

R. O Roteador considera o **método HTTP** (`$_SERVER['REQUEST_METHOD']`) junto com o **caminho** da URL, e as rotas são cadastradas por método:

```php
$roteador->get('/produtos',  [ProdutoController::class, 'index']);   // listar
$roteador->post('/produtos', [ProdutoController::class, 'salvar']);  // criar
```

Mesmo com o caminho igual, cada método leva a uma ação diferente do Controller. Se nenhuma combinação existir, o resultado é um erro 404.

7. **Injeção de Dados na View:** Como a função nativa extract() atua no método renderizar() do Controller para disponibilizar variáveis para as Views?

R. O `renderizar()` recebe o nome da View e um array associativo com os dados. O `extract()` transforma cada chave do array em uma variável:

```php
protected function renderizar(string $view, array $dados = []): void
{
    extract($dados); // ['produtos' => [...]] vira $produtos
    require __DIR__ . "/../Views/{$view}.php";
}
```

Como o `require` roda no mesmo escopo, a View usa `$produtos` diretamente. O `extract()` nunca deve receber dados crus do usuário (como `$_POST`), pois poderia sobrescrever variáveis da aplicação.

8. **Segurança contra XSS nas Views:** Por que todas as saídas dinâmicas exibidas em tabelas e formulários devem ser envolvidas com htmlspecialchars()?

R. Porque qualquer dado exibido pode ter sido digitado por um usuário. Se alguém cadastrar um nome como `<script>...</script>` e a View imprimir sem tratamento, o navegador executa o script de quem abrir a página (ataque XSS). O `htmlspecialchars()` converte `<`, `>`, `&` e aspas em entidades HTML, fazendo o navegador mostrar o conteúdo como texto:

```php
<td><?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?></td>
```

9. **Single Responsibility no Controller:** Por que um Controller nunca deve conter comandos SQL com strings concatenadas ou conexões diretas via new PDO()?

R. Porque o Controller deve ter uma única responsabilidade: receber a requisição, chamar o Model e escolher a View. Colocar SQL nele mistura fluxo com acesso ao banco e traz de volta o código espaguete. SQL concatenado também abre a porta para SQL Injection, quando o correto é usar *prepared statements* na camada de Model. E um `new PDO()` em cada Controller espalha as credenciais e abre conexões repetidas, quando a conexão deveria ser criada em um só lugar e injetada.

10. **Transição para o Framework Laravel:** Identifique quais conceitos aprendidos neste MVC artesanal são exatamente os mesmos utilizados pelo Laravel 11.

R. Praticamente tudo existe no Laravel, só que pronto:

- **MVC:** mesma divisão em Models, Views e Controllers.
- **Composer e PSR-4:** o framework é gerenciado pelo Composer, e `App\` aponta para a pasta `app/`.
- **Front Controller:** o `public/index.php` é o único ponto de entrada.
- **Roteador:** rotas definidas em `routes/web.php` com `Route::get()` e `Route::post()`.
- **Views:** o Blade usa `{{ $variavel }}`, que já faz o escape contra XSS automaticamente.
- **Banco de dados:** o Eloquent usa consultas preparadas, e a conexão fica configurada no `.env`.

O Laravel automatiza o que fizemos à mão, e entender o MVC artesanal ajuda a ver o framework como algo lógico, não como mágica.