<?php
require_once '../model/DB.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID inválido.";
    exit;
}

try {
    $conn = DB::getConnection();

    // Buscar dados da casa
    $stmt = $conn->prepare("SELECT * FROM casas WHERE id = ?");
    $stmt->execute([$id]);
    $casa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$casa) {
        echo "Casa não encontrada.";
        exit;
    }

    // Buscar imagens associadas
    $stmtImg = $conn->prepare("SELECT arquivo FROM imagens_casa WHERE casa_id = ?");
    $stmtImg->execute([$id]);
    $imagens = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

} catch (Exception $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $casa['nome'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .galeria img {
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="index.php" class="btn btn-link">← Voltar</a>
    <h2 class="mb-3"><?= htmlspecialchars($casa['nome']) ?></h2>
    <p><?= htmlspecialchars($casa['descricao']) ?></p>

    <!-- Carrossel de imagens -->
<div id="carouselCasa" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($imagens as $i => $img): ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                <img src="assets/img/<?= htmlspecialchars($img) ?>" class="d-block w-100 rounded" style="height: 700px; object-fit: cover;" alt="Imagem da casa">
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

    <!-- Formulário de votação -->
    <div class="mt-4">
        <form action="votar.php" method="POST">
            <input type="hidden" name="casa_id" value="<?= $id ?>">
            <label class="form-label">Seu número de celular:</label>
            <input type="text" name="celular" class="form-control mb-2" placeholder="11999998888" required>
            <button type="submit" class="btn btn-success">Votar nesta casa</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
