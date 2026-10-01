<?php
$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codPergunta = time();
    $enunciado = trim($_POST["enunciado"]);

    $arqPerguntas = "perguntas.txt";
    $novoArqPerguntas = !file_exists($arqPerguntas);
    $manipuladorPerguntas = fopen($arqPerguntas, "a");

    if ($manipuladorPerguntas) {
        if ($novoArqPerguntas) {
            fwrite($manipuladorPerguntas, "cod_pergunta;desafio;enunciado" . PHP_EOL);
        }

        $linhaPergunta = implode(";", [$codPergunta, "Geral", $enunciado]) . PHP_EOL;
        fwrite($manipuladorPerguntas, $linhaPergunta);
        fclose($manipuladorPerguntas);

        $arqRespostas = "respostas.txt";
        $novoArqRespostas = !file_exists($arqRespostas);
        $manipuladorRespostas = fopen($arqRespostas, "a");

        if ($manipuladorRespostas) {
            if ($novoArqRespostas) {
                fwrite($manipuladorRespostas, "cod_resposta;cod_pergunta;texto_resposta;eh_correta" . PHP_EOL);
            }

            $codResposta = $codPergunta . "_TXT";
            $orientacaoGabarito = trim($_POST["resposta_esperada"]);

            $linhaResposta = implode(";", [$codResposta, $codPergunta, $orientacaoGabarito, "1"]) . PHP_EOL;
            fwrite($manipuladorRespostas, $linhaResposta);
            fclose($manipuladorRespostas);

            $feedback = "Pergunta discursiva (Código: $codPergunta) salva com sucesso!";
        } else {
            $feedback = "Erro ao abrir arquivo de respostas.";
        }
    } else {
        $feedback = "Erro ao abrir arquivo de perguntas.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Pergunta Discursiva</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        textarea { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        .msg { font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>Nova Pergunta (Texto)</h2>

<?php if ($feedback !== ""): ?>
    <div class="msg"><?php echo $feedback; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="campo">
        <label>Pergunta / Enunciado:</label>
        <textarea name="enunciado" rows="4" required></textarea>
    </div>

    <div class="campo">
        <label>Resposta Esperada / Gabarito:</label>
        <textarea name="resposta_esperada" rows="3" required></textarea>
    </div>

    <button type="submit">Salvar</button>
</form>

</body>
</html>