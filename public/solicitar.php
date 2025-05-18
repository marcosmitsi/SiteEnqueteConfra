<?php
require_once '../model/DB.php';
$title = "Solicitar Participação";

$celular = $_GET['celular'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $celular = trim($_POST['celular']);
    $nome = trim($_POST['nome']);
    $comentario = trim($_POST['comentario']);

    try {
        $conn = DB::getConnection();

        // Verifica se já existe solicitação para esse celular
        $stmt = $conn->prepare("SELECT id FROM solicitacoes WHERE celular = ? AND status = 'pendente'");
        $stmt->execute([$celular]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Você já enviou uma solicitação! Aguarde aprovação.'); window.location.href = 'index.php';</script>";
            exit;
        }

        // Insere a solicitação
        $stmt = $conn->prepare("INSERT INTO solicitacoes (celular, nome, comentario) VALUES (?, ?, ?)");
        $stmt->execute([$celular, $nome, $comentario]);

        echo "<script>alert('Solicitação enviada com sucesso! Aguarde a aprovação.'); window.location.href = 'index.php';</script>";
    } catch (Exception $e) {
        die("Erro ao enviar solicitação: " . $e->getMessage());
    }
}

require_once '../view/layout/header.php';
?>

<h3 class="mb-4 text-center">📩 Solicitar Participação no Grupo</h3>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Celular</label>
                        <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($celular) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seu nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentário (opcional)</label>
                        <textarea name="comentario" class="form-control" rows="3" placeholder="Ex: Sou amigo da equipe..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar solicitação</button>
                    <a href="index.php" class="btn btn-link w-100 mt-2">← Voltar para a enquete</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../view/layout/footer.php'; ?>
