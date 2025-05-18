<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Enquete de Confraternização' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-container {
            max-width: 960px;
            margin: auto;
            padding: 30px 15px;
        }
    </style>
</head>
<body>

<!-- Navbar -->

<?php if (!isset($ocultarNavbar)) : ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">🏠 Enquete 2025</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="resultados.php">Resultados</a></li>
                <?php if (!empty($_SESSION['admin'])): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Painel Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_solicitacoes.php">Solicitações</a></li>

                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if (!empty($_SESSION['admin_nome'])): ?>
                    <span class="navbar-text text-white me-3">👤 <?= $_SESSION['admin_nome'] ?></span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Conteúdo principal -->
<div class="main-container">
