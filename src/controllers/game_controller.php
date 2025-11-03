<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php?erro=acesso_negado");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$action = $_GET['action'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($action) {
        case 'create':
            $nome_jogo = $_POST['nome_jogo'];
            $plataforma = $_POST['plataforma'];
            $status = $_POST['status'];

            if (empty($nome_jogo) || empty($status)) {
                header("Location: ../views/dashboard.php?erro=campos_vazios");
                exit;
            }

            try {
                $sql = "INSERT INTO lista_pessoal_jogos (id_usuario, nome_jogo, plataforma, status) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_usuario, $nome_jogo, $plataforma, $status]);
                header("Location: ../views/dashboard.php?sucesso=adicionado");
                exit;

            } catch (\PDOException $e) {
                header("Location: ../views/dashboard.php?erro=db_error");
                exit;
            }
            break;

        case 'update':
            $id_jogo = $_POST['id_jogo'];
            $status = $_POST['status'];

            if (empty($id_jogo) || empty($status)) {
                header("Location: ../views/dashboard.php?erro=dados_invalidos");
                exit;
            }

            try {
                $sql = "UPDATE lista_pessoal_jogos SET status = ? WHERE id = ? AND id_usuario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$status, $id_jogo, $id_usuario]);
                
                header("Location: ../views/dashboard.php?sucesso=atualizado");
                exit;

            } catch (\PDOException $e) {
                header("Location: ../views/dashboard.php?erro=db_error");
                exit;
            }
            break;
    }

} else if ($_SERVER["REQUEST_METHOD"] == "GET") {

    switch ($action) {

        case 'delete':
            $id_jogo = $_GET['id'];

            if (empty($id_jogo)) {
                header("Location: ../views/dashboard.php?erro=id_invalido");
                exit;
            }

            try {
                $sql = "DELETE FROM lista_pessoal_jogos WHERE id = ? AND id_usuario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_jogo, $id_usuario]);
                
                header("Location: ../views/dashboard.php?sucesso=deletado");
                exit;

            } catch (\PDOException $e) {
                header("Location: ../views/dashboard.php?erro=db_error");
                exit;
            }
            break;
    }
}

header("Location: ../views/dashboard.php");
exit;
?>