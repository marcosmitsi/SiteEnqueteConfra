<?php
require_once '../model/DB.php';
$title = "Detalhes da Casa";

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

require_once '../view/layout/header.php';
?>

<h2 class="mb-3"><?= htmlspecialchars($casa['nome']) ?></h2>
<p><?= htmlspecialchars($casa['descricao']) ?></p>

<!-- Carrossel de imagens -->
<?php if ($imagens): ?>
    <div id="carouselCasa" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($imagens as $i => $img): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <img src="assets/img/<?= htmlspecialchars($img) ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;" alt="Imagem da casa">
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
<?php endif; ?>

<!-- Formulário de voto -->
<div class="card shadow-sm">
    <div class="card-body">
        <form action="votar.php" method="POST">
            <input type="hidden" name="casa_id" value="<?= $id ?>">
            <label class="form-label">Seu número de celular:</label>
            <input type="text" name="celular" class="form-control mb-2" placeholder="11999998888" required>
            <button type="submit" class="btn btn-success w-100">Votar nesta casa</button>
        </form>
    </div>
</div>

<?php require_once '../view/layout/footer.php'; ?>
