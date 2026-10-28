<?php
declare(strict_types=1);

final class Roteador {
    //Roteador HTTP com suporte a depacho de rotas e Guards de Segurança
    private array $rotas = [];
    // Métodos
    //Registra rota solicitada
    public function registrar(string $metodo, string $uri, array $acao, array $guards = []): void {
        $uriNormalizada = '/' . trim($uri, '/');
        $this->rotas[strtoupper($metodo)][$uriNormalizada] = [
            'acao'   => $acao,
            'guards' => $guards
        ];
    }

    //Despacha para rota solicitada 
    public function despachar(string $metodo, string $uri): void {
        $uriLimpa = '/' . trim((string)parse_url($uri, PHP_URL_PATH), '/');
        $metodoHttp = strtoupper($metodo);

        if (isset($this->rotas[$metodoHttp][$uriLimpa])) {
            $registro = $this->rotas[$metodoHttp][$uriLimpa];
            $this->executarGuards($registro['guards']);
            [$classeController, $nomeMetodo] = $registro['acao'];
            $controller = new $classeController();
            $controller->$nomeMetodo();
            return;
        }
        $this->responderNaoEncontrado();
    }

    //Executa o guard para a rota

          private function executarGuards(array $guards): void {
        foreach ($guards as $guard) {
            [$classeGuard, $argumentos] = $guard;
            $classeGuard::proteger($argumentos ?? []);
        }
    }

    //Rota não Encontrada
     private function responderNaoEncontrado(): void {
        http_response_code(404);
        echo "<h1 style='font-family:sans-serif;text-align:center;margin-top:50px;'>404 — Rota Nao Encontrada</h1>";
        echo "<p style='font-family:sans-serif;text-align:center;'><a href='/'>Voltar para o Inicio</a></p>";
    }
}