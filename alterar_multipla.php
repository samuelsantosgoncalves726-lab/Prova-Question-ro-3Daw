<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codEditar = trim($_POST["cod_pergunta"]);
    $novoEnunciado = trim($_POST["enunciado"]);

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

                $opcoes = [
                    "A" => trim($_POST["opcao_a"]),
                    "B" => trim($_POST["opcao_b"]),
                    "C" => trim($_POST["opcao_c"]),
                    "D" => trim($_POST["opcao_d"])
                ];
                $respCorreta = $_POST["resposta_correta"];

                foreach ($opcoes as $chave => $texto) {
                    $codResp = $codEditar . "_" . $chave;
                    $ehCorreta = ($chave === $respCorreta) ? "1" : "0";
                    $novasRespostas[] = implode(";", [$codResp, $codEditar, $texto, $ehCorreta]);
                }

                $arqR = fopen("respostas.txt", "w");
                foreach ($novasRespostas as $r) {
                    fwrite($arqR, $r . PHP_EOL);
                }
                fclose($arqR);
            }

            $msg = "Pergunta e respostas alteradas com sucesso!";
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
    <title>Alterar Múltipla Escolha</title>
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

<h2>Alterar Pergunta (Múltipla Escolha)</h2>

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
        <textarea name="enunciado" rows="3" required></textarea>
    </div>

    <div class="campo">
        <label>Nova Opção A:</label>
        <input type="text" name="opcao_a" required>
    </div>

    <div class="campo">
        <label>Nova Opção B:</label>
        <input type="text" name="opcao_b" required>
    </div>

    <div class="campo">
        <label>Nova Opção C:</label>
        <input type="text" name="opcao_c" required>
    </div>

    <div class="campo">
        <label>Nova Opção D:</label>
        <input type="text" name="opcao_d" required>
    </div>

    <div class="campo">
        <label>Nova Resposta Correta:</label>
        <select name="resposta_correta" required>
            <option value="A">Opção A</option>
            <option value="B">Opção B</option>
            <option value="C">Opção C</option>
            <option value="D">Opção D</option>
        </select>
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>