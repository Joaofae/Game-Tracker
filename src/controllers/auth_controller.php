<?php
session_start();
require_once '../../config/database.php';
$action = $_GET['action'] ?? 'login';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    switch ($action) {
        case 'register':
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha_plana = $_POST['senha'];

            if (empty($nome) || empty($email) || empty($senha_plana)) {
                header("Location: ../views/cadastro.php?erro=campos_vazios");
                exit;
            }

            $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $email, $senha_hash]);
                header("Location: ../views/login.php?sucesso=cadastro_ok");
                exit;

            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    header("Location: ../views/cadastro.php?erro=email_existente");
                } else {
                    header("Location: ../views/cadastro.php?erro=db_error");
                }
                exit;
            }
            break;

        case 'login':
            $email = $_POST['email'];
            $senha_plana = $_POST['senha'];
            if (empty($email) || empty($senha_plana)) {
                header("Location: ../views/login.php?erro=campos_vazios");
                exit;
            }

            try {
                $sql = "SELECT * FROM usuarios WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);
                $usuario = $stmt->fetch();

                if ($usuario && password_verify($senha_plana, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    header("Location: ../views/dashboard.php");
                    exit;
                } else {
                    header("Location: ../views/login.php?erro=login_invalido");
                    exit;
                }
            } catch (\PDOException $e) {
                header("Location: ../views/login.php?erro=db_error");
                exit;
            }
            break;
    }
} else if ($action == 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../views/login.php?sucesso=logout_ok");
    exit;
}
?>