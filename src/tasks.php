<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=taskmanagement';
$usuariobd = 'root';
$senhabd = '';

try {
    $conexao = new PDO($dsn, $usuariobd, $senhabd);

    $filtroStatus = isset($_GET['status']) ? $_GET['status'] : 'todos';
    $query = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id";

    if ($filtroStatus !== 'todos') {
        $query .= " AND status = :status";
    }

    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':usuario_id', $_SESSION['user_id']);

    if ($filtroStatus !== 'todos') {
        $stmt->bindValue(':status', $filtroStatus);
    }

    $stmt->execute();
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getCode() . ' Mensagem: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskName'])) {
    try {
        $query = "INSERT INTO tarefas (titulo, descricao, status, usuario_id) VALUES (:titulo, :descricao, :status, :usuario_id)";
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':titulo', $_POST['taskName']);
        $stmt->bindValue(':descricao', $_POST['taskDescription']);
        $stmt->bindValue(':status', $_POST['taskStatus']);
        $stmt->bindValue(':usuario_id', $_SESSION['user_id']);
        $stmt->execute();
        header('Location: tasks.php');
        exit();
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getCode() . ' Mensagem: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editTaskName'])) {
    try {
        $query = "UPDATE tarefas SET titulo = :titulo, descricao = :descricao, status = :status WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':titulo', $_POST['editTaskName']);
        $stmt->bindValue(':descricao', $_POST['editTaskDescription']);
        $stmt->bindValue(':status', $_POST['editTaskStatus']);
        $stmt->bindValue(':id', $_POST['taskId']);
        $stmt->bindValue(':usuario_id', $_SESSION['user_id']);
        $stmt->execute();
        header('Location: tasks.php');
        exit();
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getCode() . ' Mensagem: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteTaskId'])) {
    try {
        $query = "DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':id', $_POST['deleteTaskId']);
        $stmt->bindValue(':usuario_id', $_SESSION['user_id']);
        $stmt->execute();
        header('Location: tasks.php');
        exit();
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getCode() . ' Mensagem: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management 1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="logo-sistema.png" alt="Logotipo do Gerenciador de Tarefas" width="30" class="me-2">
                <span>Task Management 1.0</span>
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3">Olá, <?php echo $_SESSION['user_name']; ?>!</span> 
                <a href="index.php" class="btn btn-outline-secondary">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">Lista de Tarefas</h2>
        <div class="mb-4 d-flex justify-content-between">
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Adicionar Tarefa</button>
            </div>
            <div>
                <label for="taskFilter" class="form-label">Filtrar Tarefas:</label>
                <form method="GET" action="tasks.php" class="d-inline">
                    <select id="taskFilter" class="form-select" name="status" onchange="this.form.submit()">
                        <option value="todos" <?php echo ($filtroStatus === 'todos') ? 'selected' : ''; ?>>Todas</option>
                        <option value="incompleta" <?php echo ($filtroStatus === 'incompleta') ? 'selected' : ''; ?>>Incompletas</option>
                        <option value="completa" <?php echo ($filtroStatus === 'completa') ? 'selected' : ''; ?>>Completas</option>
                    </select>
                </form>
            </div>
        </div>

        <table class="table table-striped" id="taskTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tarefa</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tarefas as $tarefa): ?>
                <tr>
                    <td><?php echo $tarefa['id']; ?></td>
                    <td><?php echo $tarefa['titulo']; ?></td>
                    <td><?php echo $tarefa['descricao']; ?></td>
                    <td><?php echo $tarefa['status']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTaskModal" 
                            data-id="<?php echo $tarefa['id']; ?>" data-titulo="<?php echo $tarefa['titulo']; ?>" 
                            data-descricao="<?php echo $tarefa['descricao']; ?>" data-status="<?php echo $tarefa['status']; ?>">Editar</button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" 
                            data-id="<?php echo $tarefa['id']; ?>">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Adicionar Nova Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="taskName" class="form-label">Nome da Tarefa</label>
                            <input type="text" class="form-control" id="taskName" name="taskName" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Descrição</label>
                            <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required></textarea>
                        </div>              
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-select" id="taskStatus" name="taskStatus" required>
                                <option selected>Incompleta</option>
                                <option>Completa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Editar Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="taskId" id="taskId">
                        <div class="mb-3">
                            <label for="editTaskName" class="form-label">Nome da Tarefa</label>
                            <input type="text" class="form-control" id="editTaskName" name="editTaskName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTaskDescription" class="form-label">Descrição</label>
                            <textarea class="form-control" id="editTaskDescription" name="editTaskDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editTaskStatus" class="form-label">Status</label>
                            <select class="form-select" id="editTaskStatus" name="editTaskStatus" required>
                                <option value="incompleta">Incompleta</option>
                                <option value="completa">Completa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Excluir Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja excluir esta tarefa?
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        <input type="hidden" name="deleteTaskId" id="deleteTaskId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editTaskModal = document.getElementById('editTaskModal');
        editTaskModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var taskId = button.getAttribute('data-id');
            var taskName = button.getAttribute('data-titulo');
            var taskDescription = button.getAttribute('data-descricao');
            var taskStatus = button.getAttribute('data-status');

            document.getElementById('editTaskName').value = taskName;
            document.getElementById('editTaskDescription').value = taskDescription;
            document.getElementById('editTaskStatus').value = taskStatus;
            document.getElementById('taskId').value = taskId;
        });

        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var taskId = button.getAttribute('data-id');
            document.getElementById('deleteTaskId').value = taskId;
        });
    </script>
</body>
</html>
