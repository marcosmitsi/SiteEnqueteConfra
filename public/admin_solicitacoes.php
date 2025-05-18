<?php
require_once '../auth/protect.php';
require_once '../model/DB.php';
$title = "Gerenciar Solicitações";

$conn = DB::getConnection();

// Aprovar
if (isset($_GET['aprovar'])) {
    $id = $_GET['aprovar'];
    $stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE id = ?");
    $stmt->execute([$id]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($s) {
        $conn->prepare("INSERT INTO participantes (celular, nome) VALUES (?, ?)")->execute([$s['celular'], $s['nome']]);
        $conn->prepare("UPDATE solicitacoes SET status = 'aprovado' WHERE id = ?")->execute([$id]);
        echo "<script>alert('Solicitação aprovada com sucesso!'); location.href='admin_solicitacoes.php';</script>";
        exit;
    }
}

// Rejeitar
if (isset($_GET['rejeitar'])) {
    $id = $_GET['rejeitar'];
    $conn->prepare("UPDATE solicitacoes SET status = 'recusado' WHERE id = ?")->execute([$id]);
    echo "<script>alert('Solicitação rejeitada.'); location.href='admin_solicitacoes.php';</script>";
    exit;
}

// Buscar pendentes
$stmt = $conn->query("SELECT * FROM solicitacoes WHERE status = 'pendente' ORDER BY data_envio DESC");
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../view/layout/header.php';
?>

<h2 class="mb-4">📥 Solicitações Pendentes</h2>

<?php if (empty($solicitacoes)): ?>
    <div class="alert alert-info text-center">Nenhuma solicitação pendente no momento.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Celular</th>
                    <th>Nome</th>
                    <th>Comentário</th>
                    <th>Enviado em</th>
                    <th style="width: 160px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['celular']) ?></td>
                        <td><?= htmlspecialchars($s['nome']) ?></td>
                        <td><?= nl2br(htmlspecialchars($s['comentario'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($s['data_envio'])) ?></td>
                        <td>
                            <a href="?aprovar=<?= $s['id'] ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Aprovar
                            </a>
                            <a href="?rejeitar=<?= $s['id'] ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Rejeitar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../view/layout/footer.php'; ?>
