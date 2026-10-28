<?php
declare(strict_types=1);

final class AuthController extends Controller {
    // abrir o formulário de login
    public function formularioLogin(): void {
        $this->renderizar('auth/login', [
            'titulo'    => 'Login Corporativo — SENAI Supply',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    //realizar o login do usuario
    public function login(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->renderizar('auth/login', ['titulo' => 'Login', 'erro' => 'Sessão expirada. Tente novamente.']);
            return;
        }
        $email = trim($_POST['email'] ?? '');
        $senha = (string)($_POST['senha'] ?? '');
        $usuario = Usuario::buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id']     = (int)$usuario['id'];
            $_SESSION['usuario_nome']   = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];
            Logger::info("Login corporativo bem-sucedido", ['usuario_id' => $usuario['id'], 'perfil' => $usuario['perfil']]);
            $this->redirecionar('/');
        }
        Logger::aviso("Falha na tentativa de login corporativo", ['email' => $email]);
        $this->renderizar('auth/login', ['titulo' => 'Login', 'erro' => 'Credenciais incorretas.', 'csrfToken' => $this->obterCsrfToken()]);
    }

    //realizar o logout do usuário
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $usuarioId = $_SESSION['usuario_id'] ?? null;
        Logger::info("Logout efetuado pelo operador", ['usuario_id' => $usuarioId]);
        $_SESSION = [];
        session_destroy();
        $this->redirecionar('/login');
    }
}