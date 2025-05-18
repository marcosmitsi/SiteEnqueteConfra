<?php
require_once '../model/DB.php';
$title = "Resultados da Enquete";

$conn = DB::getConnection();

// Total de votos por casa
$stmt = $conn->query("
    SELECT casas.nome, COUNT(votos.id) AS total
    FROM casas
    LEFT JOIN votos ON votos.casa_id = casas.id
    GROUP BY casas.id
    ORDER BY total DESC
");
$votos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de votantes
$stmt = $conn->query("SELECT COUNT(DISTINCT celular) FROM votos");
$total_votantes = $stmt->fetchColumn();

require_once '../view/layout/header.php';
?>

<h2 class="text-center mb-4">📢 Resultado Parcial da Enquete</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">📊 Votos por Casa</h5>
        <canvas id="graficoPublico"></canvas>
    </div>
</div>

<div class="alert alert-info text-center">
    <strong>Total de votantes:</strong> <?= $total_votantes ?>
</div>

<div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">← Voltar para votar</a>
</div>

<p class="text-muted text-center mt-4 small">
    Atualizado em: <?= date('d/m/Y H:i:s') ?>
</p>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoPublico').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($votos, 'nome')) ?>,
            datasets: [{
                label: 'Total de Votos',
                data: <?= json_encode(array_column($votos, 'total')) ?>,
                backgroundColor: '#198754'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<?php require_once '../view/layout/footer.php'; ?>
