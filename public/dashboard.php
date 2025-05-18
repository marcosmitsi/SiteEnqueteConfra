<?php
require_once '../auth/protect.php';
require_once '../model/DB.php';
$title = "Dashboard da Enquete";

$conn = DB::getConnection();

// Totais de votos por casa
$stmt = $conn->query("
    SELECT casas.nome, COUNT(votos.id) AS total_votos
    FROM casas
    LEFT JOIN votos ON casas.id = votos.casa_id
    GROUP BY casas.id
    ORDER BY total_votos DESC
");
$votos_por_casa = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Participantes que votaram
$stmt = $conn->query("SELECT * FROM participantes WHERE votou = 1 ORDER BY nome ASC");
$votaram = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Participantes que ainda não votaram
$stmt = $conn->query("SELECT * FROM participantes WHERE votou = 0 ORDER BY nome ASC");
$nao_votaram = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../view/layout/header.php';
?>

<h2 class="mb-4">📊 Painel Administrativo</h2>

<!-- Gráfico -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Total de Votos por Casa</h5>
        <canvas id="graficoVotos"></canvas>
    </div>
</div>

<!-- Listagem -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 border-success shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-success">✅ Votaram (<?= count($votaram) ?>)</h5>
                <ul class="list-group">
                    <?php foreach ($votaram as $p): ?>
                        <li class="list-group-item"><?= htmlspecialchars($p['nome']) ?> - <?= $p['celular'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4 border-warning shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-warning">⏳ Não votaram (<?= count($nao_votaram) ?>)</h5>
                <ul class="list-group">
                    <?php foreach ($nao_votaram as $p): ?>
                        <li class="list-group-item"><?= htmlspecialchars($p['nome']) ?> - <?= $p['celular'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoVotos').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($votos_por_casa, 'nome')) ?>,
            datasets: [{
                label: 'Total de Votos',
                data: <?= json_encode(array_column($votos_por_casa, 'total_votos')) ?>,
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>

<?php require_once '../view/layout/footer.php'; ?>
