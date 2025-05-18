<?php
session_start();
require_once '../model/DB.php';
$title = "Login do Administrador";

$conn = DB::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admins WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_nome'] = $admin['nome'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
$ocultarNavbar = true;
require_once '../view/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm mt-5">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">🔐 Login do Admin</h4>

                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger text-center"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Usuário</label>
                        <input type="text" name="usuario" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    <a href="index.php" class="btn btn-link w-100 mt-2">← Voltar para a Enquete</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../view/layout/footer.php'; ?>
