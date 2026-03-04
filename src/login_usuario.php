<?php
session_start();

if(!empty($_POST['username']) && !empty($_POST['password'])) {

    $dsn = 'mysql:host=localhost;dbname=taskmanagement';
    $usuariobd = 'root';
    $senhabd = '';

    try {
       $conexao = new PDO($dsn, $usuariobd, $senhabd);

        $query = "select * from usuarios where";
        $query .= " usuario = :pusuario ";
        $query .= " AND senha = :psenha";

        $stmt = $conexao->prepare($query);

        $stmt->bindValue(':pusuario',$_POST['username']);
        $stmt->bindValue(':psenha',$_POST['password']);

        $stmt->execute();

        $usuario = $stmt->fetch();

        if (!empty($usuario)) {
            $_SESSION['user_name'] = $_POST['username'];
            $_SESSION['user_id'] = $usuario['id'];
            header('location:tasks.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Não foi possível realizar o login. Usuário ou senha incorretos.';
            header('location:index.php');
            exit();
        }

    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getCode() . ' Mensagem: ' . $e->getMessage();
    }

}
?>
