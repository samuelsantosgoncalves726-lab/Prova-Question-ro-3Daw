<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        table, th, td { border: 1px solid #000; border-collapse: collapse; padding: 6px; }
        th { background: #f0f0f0; }
        .bloco { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Relatório de Perguntas e Respostas</h2>
<hr>

<?php
if (file_exists("perguntas.txt")) {
    $perguntas = file("perguntas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $respostas = file_exists("respostas.txt") ? file("respostas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    foreach ($perguntas as $iP => $linhaP) {
        if ($iP === 0) continue;

        $dadosP = explode(";", $linhaP);
        $codP = $dadosP[0];
        $desafio = $dadosP[1];
        $enunciado = $dadosP[2];

        echo "<div class='bloco'>";
        echo "<p><strong>Código:</strong> $codP | <strong>Desafio:</strong> $desafio</p>";
        echo "<p><strong>Pergunta:</strong> $enunciado</p>";

        echo "<table>";
        echo "<tr><th>Cód. Resposta</th><th>Texto da Resposta</th><th>É Correta? (1=Sim / 0=Não)</th></tr>";

        foreach ($respostas as $iR => $linhaR) {
            if ($iR === 0) continue;

            $dadosR = explode(";", $linhaR);
            if ($dadosR[1] == $codP) {
                echo "<tr>";
                echo "<td>" . $dadosR[0] . "</td>";
                echo "<td>" . $dadosR[2] . "</td>";
                echo "<td>" . $dadosR[3] . "</td>";
                echo "</tr>";
            }
        }

        echo "</table></div><hr>";
    }
} else {
    echo "<p>Nenhuma pergunta cadastrada.</p>";
}
?>

</body>
</html>