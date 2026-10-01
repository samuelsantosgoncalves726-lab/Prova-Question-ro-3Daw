<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codExcluir = trim($_POST["cod_pergunta"]);

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
            if ($dados[0] == $codExcluir) {
                $encontrado = true;
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
                    if ($dadosR[1] != $codExcluir) {
                        $novasRespostas[] = $linha;
                    }
                }

                $arqR = fopen("respostas.txt", "w");
                foreach ($novasRespostas as $r) {
                    fwrite($arqR, $r . PHP_EOL);
                }
                fclose($arqR);
            }

            $msg = "Pergunta e respostas excluídas com sucesso!";
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
    <title>Excluir Pergunta</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"] { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        .msg { font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>Excluir Pergunta</h2>

<?php if ($msg !== ""): ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST" onsubmit="return confirm('Excluir esta pergunta e respostas?');">
    <div class="campo">
        <label>Código da Pergunta:</label>
        <input type="text" name="cod_pergunta" required placeholder="Ex: 1700000000">
    </div>

    <button type="submit">Excluir Registro</button>
</form>

</body>
</html>