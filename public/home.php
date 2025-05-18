<?php
$title = "Bem-vindo à Enquete";
require_once '../view/layout/header.php';
?>

<div class="text-center my-5">
    <h1 class="mb-3">🎉 Bem-vindo à Enquete da Confraternização 2025!</h1>
    <p class="lead mb-4">
        Escolha a casa ideal para celebrarmos juntos o fim de ano.
    </p>

    <a href="index.php" class="btn btn-primary btn-lg mb-3">
        🎯 Votar Agora
    </a>
    <br>
    <a href="login.php" class="btn btn-link text-muted small">
        🔐 Acesso do Administrador
    </a>
</div>

<?php require_once '../view/layout/footer.php'; ?>
