<?php
// Verifica se a solicitação é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $novoValor = $_POST["value"];

    var_dump($id, $novoValor);

    // Conexão com o banco de dados
    $host = 'rijk.postgres';
    $db = 'postgres';
    $user = 'postgres';
    $password = '02W@9889forev';
    $port = 5432; // Porta padrão do PostgreSQL

    try {
        // Conecta ao banco de dados
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Atualiza o valor no banco de dados
        $stmt = $conn->prepare("UPDATE public.exchange SET value = :value WHERE id = :id");
        $stmt->bindParam(':value', $novoValor);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Retorna uma mensagem de sucesso
        echo json_encode(['message' => 'Valor atualizado com sucesso']);

    } catch (PDOException $e) {
        // Retorna uma mensagem de erro caso ocorra uma exceção
        echo json_encode(['error' => 'Erro ao atualizar o valor: ' . $e->getMessage()]);
    }
} else {
    // Retorna uma mensagem de erro se a solicitação não for do tipo POST
    echo json_encode(['error' => 'Método não permitido']);
}
?>
