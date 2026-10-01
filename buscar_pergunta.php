<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Uma Pergunta</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"] { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        table, th, td { border: 1px solid #000; border-collapse: collapse; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Buscar Pergunta por Código</h2>

<form method="GET">
    <div class="campo">
        <label>Informe o Código:</label>
        <input type="text" name="cod_pergunta" required placeholder="Ex: 1700000000">
    </div>
    <button type="submit">Buscar</button>
</form>

<hr>

<?php
$codBusca = isset($_GET["cod_pergunta"]) ? trim($_GET["cod_pergunta"]) : "";

if ($codBusca !== "") {
    $achou = false;

    if (file_exists("perguntas.txt")) {
        $perguntas = file("perguntas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $respostas = file_exists("respostas.txt") ? file("respostas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        foreach ($perguntas as $iP => $linhaP) {
            if ($iP === 0) continue;

            $dadosP = explode(";", $linhaP);
            if ($dadosP[0] == $codBusca) {
                $achou = true;
                echo "<p><strong>Código:</strong> " . $dadosP[0] . "</p>";
                echo "<p><strong>Desafio:</strong> " . $dadosP[1] . "</p>";
                echo "<p><strong>Pergunta:</strong> " . $dadosP[2] . "</p>";

                echo "<h3>Respostas Vinculadas:</h3>";
                echo "<table>";
                echo "<tr><th>Cód. Resposta</th><th>Texto da Resposta</th><th>Correta</th></tr>";

                foreach ($respostas as $iR => $linhaR) {
                    if ($iR === 0) continue;
                    $dadosR = explode(";", $linhaR);
                    if ($dadosR[1] == $codBusca) {
                        echo "<tr>";
                        echo "<td>" . $dadosR[0] . "</td>";
                        echo "<td>" . $dadosR[2] . "</td>";
                        echo "<td>" . $dadosR[3] . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
                break;
            }
        }
    }

    if (!$achou) {
        echo "<p>Pergunta não encontrada.</p>";
    }
}
?>

</body>
</html>