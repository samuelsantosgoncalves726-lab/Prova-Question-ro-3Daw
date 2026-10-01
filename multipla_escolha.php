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

            $opcoes = [
                "A" => trim($_POST["opcao_a"]),
                "B" => trim($_POST["opcao_b"]),
                "C" => trim($_POST["opcao_c"]),
                "D" => trim($_POST["opcao_d"])
            ];
            $respostaCorreta = $_POST["resposta_correta"];

            foreach ($opcoes as $chave => $textoOpcao) {
                $codResposta = $codPergunta . "_" . $chave;
                $ehCorreta = ($chave === $respostaCorreta) ? "1" : "0";
                $linhaResposta = implode(";", [$codResposta, $codPergunta, $textoOpcao, $ehCorreta]) . PHP_EOL;
                fwrite($manipuladorRespostas, $linhaResposta);
            }

            fclose($manipuladorRespostas);
            $feedback = "Pergunta (Código: $codPergunta) salva com sucesso!";
        } else {
            $feedback = "Erro ao abrir o arquivo de respostas.";
        }
    } else {
        $feedback = "Erro ao abrir o arquivo de perguntas.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Múltipla Escolha</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"], select, textarea { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        .msg { font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>Nova Pergunta (Múltipla Escolha)</h2>

<?php if ($feedback !== ""): ?>
    <div class="msg"><?php echo $feedback; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="campo">
        <label>Pergunta / Enunciado:</label>
        <textarea name="enunciado" rows="3" required></textarea>
    </div>

    <div class="campo">
        <label>Opção A:</label>
        <input type="text" name="opcao_a" required>
    </div>

    <div class="campo">
        <label>Opção B:</label>
        <input type="text" name="opcao_b" required>
    </div>

    <div class="campo">
        <label>Opção C:</label>
        <input type="text" name="opcao_c" required>
    </div>

    <div class="campo">
        <label>Opção D:</label>
        <input type="text" name="opcao_d" required>
    </div>

    <div class="campo">
        <label>Resposta Correta:</label>
        <select name="resposta_correta" required>
            <option value="A">Opção A</option>
            <option value="B">Opção B</option>
            <option value="C">Opção C</option>
            <option value="D">Opção D</option>
        </select>
    </div>

    <button type="submit">Salvar</button>
</form>

</body>
</html>