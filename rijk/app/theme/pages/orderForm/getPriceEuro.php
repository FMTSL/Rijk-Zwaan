<?php
try {
    $conn = new PDO("pgsql:host=rijk.postgres;port=5432;dbname=aut_rijk", "postgres", "02W@9889forev");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define o timezone para o horário de Brasília
    date_default_timezone_set('America/Sao_Paulo');

    // Verifica se um novo valor foi enviado via POST
    if (isset($_POST['new_value'])) {
        $new_value = $_POST['new_value'];

        // Atualiza o último valor do Euro na tabela
        $stmt = $conn->prepare("UPDATE exchange SET value = :new_value WHERE id = (SELECT id FROM exchange WHERE DATE(created_at) = CURRENT_DATE ORDER BY id DESC LIMIT 1)");
        $stmt->bindParam(':new_value', $new_value);
        $stmt->execute();

        echo "Valor do Euro atualizado com sucesso!";
    } else {
        // Verifica se já existe um registro na tabela para o dia de hoje
        $stmt = $conn->prepare("SELECT id FROM exchange WHERE DATE(created_at) = CURRENT_DATE");
        $stmt->execute();
        $result = $stmt->fetch();

        // Se não existir, insere o novo valor do Euro da API
        if (!$result) {
            // Obtém o preço atual do Euro da API
            $url = 'https://api.exchangerate-api.com/v4/latest/EUR';
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            // Verifica se a API retornou os dados corretamente
            if (isset($data['rates']['BRL'])) {
                $value = $data['rates']['BRL']; // Obtém o preço em reais (BRL)
                
                // Insere o novo valor do Euro na tabela com o horário correto
                $stmt = $conn->prepare("INSERT INTO exchange (value, created_at) VALUES (:value, NOW() AT TIME ZONE 'America/Sao_Paulo')");
                $stmt->bindParam(':value', $value);
                $stmt->execute();

                echo "Valor atual: " . $value;
            } else {
                echo "Erro ao obter o valor do Euro da API.";
            }
        } else {
            // Se já existe um registro para o dia de hoje, exibe o valor atual da tabela
            $stmt = $conn->prepare("SELECT value FROM exchange WHERE DATE(created_at) = CURRENT_DATE");
            $stmt->execute();
            $result = $stmt->fetch();
        
            if ($result) {
                $value = $result['value'];
                echo "Valor atual: " . $value;
            } else {
                echo "Erro ao obter o valor do Euro da tabela.";
            }
        }
    }

    $conn = null;
} catch(PDOException $e) {
    echo "Erro ao inserir o valor do Euro: " . $e->getMessage();
}

?>
