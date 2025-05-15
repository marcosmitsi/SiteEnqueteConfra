<?php
require_once '../model/DB.php';

try {
    $conn = DB::getConnection();

    // Buscar todas as casas ativas
    $stmt = $conn->prepare("SELECT * FROM casas WHERE status = 1 ORDER BY id ASC");
    $stmt->execute();
    $casas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erro ao buscar casas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Enquete de Confraternização</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .casa-img {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .card {
            box-shadow: 0 0 10px #ccc;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="text-center mb-4">🏡 Enquete: Escolha a Casa da Confraternização</h1>

    <div class="row">
        <?php foreach ($casas as $casa): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <!-- 🔒 Substituir por imagem real depois -->
                    <img src="assets/img/casa<?= $casa['id'] ?>.jpg" class="card-img-top casa-img" alt="Imagem da casa">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($casa['nome']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($casa['descricao']) ?></p>
                        <p><strong>Capacidade:</strong> <?= $casa['capacidade'] ?> pessoas</p>

                        <form action="votar.php" method="POST">
                            <input type="hidden" name="casa_id" value="<?= $casa['id'] ?>">
                            <label class="form-label">Seu número de celular:</label>
                            <input type="text" name="celular" class="form-control mb-2" placeholder="11999998888" required>
                            <button type="submit" class="btn btn-primary w-100">Votar nesta casa</button>
                        </form>

                        <a href="ver_casa.php?id=<?= $casa['id'] ?>" class="btn btn-outline-secondary mt-2 w-100">Ver mais</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
