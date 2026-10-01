<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codEditar = trim($_POST["cod_pergunta"]);
    $novoEnunciado = trim($_POST["enunciado"]);
    $novaResposta = trim($_POST["resposta_esperada"]);

    if (file_exists("perguntas.txt")) {
        $linhasPerguntas = file("perguntas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $novasPerguntas = [];
        $encontrado = false;

        foreach ($linhasPerguntas as $i => $linha) {
            if ($i === 0) {
                $novasPerguntas[] = $linha;
                continue;
            }
            $dados = explode(";", $linha);
            if ($dados[0] == $codEditar) {
                $encontrado = true;
                $dados[2] = $novoEnunciado;
                $novasPerguntas[] = implode(";", $dados);
            } else {
                $novasPerguntas[] = $linha;
            }
        }

        if ($encontrado) {
            $arqP = fopen("perguntas.txt", "w");
            foreach ($novasPerguntas as $p) {
                fwrite($arqP, $p . PHP_EOL);
            }
            fclose($arqP);

            if (file_exists("respostas.txt")) {
                $linhasRespostas = file("respostas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $novasRespostas = [];

                foreach ($linhasRespostas as $i => $linha) {
                    if ($i === 0) {
                        $novasRespostas[] = $linha;
                        continue;
                    }
                    $dadosR = explode(";", $linha);
                    if ($dadosR[1] != $codEditar) {
                        $novasRespostas[] = $linha;
                    }
                }

                $codResp = $codEditar . "_TXT";
                $novasRespostas[] = implode(";", [$codResp, $codEditar, $novaResposta, "1"]);

                $arqR = fopen("respostas.txt", "w");
                foreach ($novasRespostas as $r) {
                    fwrite($arqR, $r . PHP_EOL);
                }
                fclose($arqR);
            }

            $msg = "Pergunta de texto alterada com sucesso!";
        } else {
            $msg = "Código de pergunta não encontrado.";
        }
    } else {
        $msg = "Arquivo de perguntas não existe.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta Discursiva</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"], textarea { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        .msg { font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>Alterar Pergunta (Texto)</h2>

<?php if ($msg !== ""): ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="campo">
        <label>Código da Pergunta a Editar:</label>
        <input type="text" name="cod_pergunta" required placeholder="Ex: 1700000000">
    </div>

    <div class="campo">
        <label>Novo Enunciado:</label>
        <textarea name="enunciado" rows="4" required></textarea>
    </div>

    <div class="campo">
        <label>Nova Resposta Esperada / Gabarito:</label>
        <textarea name="resposta_esperada" rows="3" required></textarea>
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>