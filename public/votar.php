<?php
require_once '../model/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $casa_id = $_POST['casa_id'] ?? null;
    $celular = trim($_POST['celular'] ?? '');

    if (!$casa_id || !$celular) {
        die("Dados inválidos.");
    }

    try {
        $conn = DB::getConnection();

        // 1. Verifica se o número está autorizado (tabela participantes)
        $stmt = $conn->prepare("SELECT * FROM participantes WHERE celular = ?");
        $stmt->execute([$celular]);
        $participante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$participante) {
            // Redireciona para a tela de solicitação
            header("Location: solicitar.php?celular=" . urlencode($celular));
            exit;
        }

        // 2. Verifica se já votou
        if ($participante['votou']) {
            echo "<script>alert('Você já votou!'); window.location.href = 'index.php';</script>";
            exit;
        }

        // 3. Salva o voto
        $stmt = $conn->prepare("INSERT INTO votos (celular, casa_id) VALUES (?, ?)");
        $stmt->execute([$celular, $casa_id]);

        // 4. Atualiza o status "votou" na tabela participantes
        $stmt = $conn->prepare("UPDATE participantes SET votou = 1 WHERE celular = ?");
        $stmt->execute([$celular]);

        echo "<script>alert('✅ Voto registrado com sucesso!'); window.location.href = 'index.php';</script>";

    } catch (Exception $e) {
        die("Erro ao registrar voto: " . $e->getMessage());
    }

} else {
    echo "Acesso inválido.";
}
