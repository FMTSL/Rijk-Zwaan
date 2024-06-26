<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter o novo valor do Euro enviado via POST
    $newValue = $_POST["new_value"];

    // Conectar ao banco de dados
    $pdo = new PDO("pgsql:host=rijk.postgres;port=5432;dbname=aut_rijk", "postgres", "02W@9889forev");

    // Preparar e executar a declaração SQL para atualizar o valor do Euro no primeiro registro da tabela exchange
    $stmt = $pdo->prepare("UPDATE exchange SET value = :value ORDER BY id ASC LIMIT 1");
    $stmt->execute(array(':value' => $newValue));

    // Verificar se a atualização foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        // Atualização bem-sucedida
        $response = ['success' => true, 'message' => 'Valor do Euro atualizado com sucesso!'];
    } else {
        // Erro ao atualizar
        $response = ['success' => false, 'message' => 'Erro ao atualizar o valor do Euro.'];
    }

    // Retornar a resposta em formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    die(); // Interrompe a execução do script aqui
}
?>
