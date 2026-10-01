<?php
$arquivo = "usuarios.txt";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST["acao"];
    $cod = trim($_POST["cod_usuario"]);
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if ($acao === "C") {
        $codNovo = time();
        $novoArq = !file_exists($arquivo);
        $m = fopen($arquivo, "a");

        if ($m) {
            if ($novoArq) {
                fwrite($m, "cod_usuario;nome;email;senha" . PHP_EOL);
            }
            fwrite($m, implode(";", [$codNovo, $nome, $email, $senha]) . PHP_EOL);
            fclose($m);
            $msg = "Usuário cadastrado com sucesso! Código: $codNovo";
        }
    } elseif ($acao === "U" || $acao === "D") {
        if (file_exists($arquivo)) {
            $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $novas = [];
            $achou = false;

            foreach ($linhas as $i => $linha) {
                if ($i === 0) {
                    $novas[] = $linha;
                    continue;
                }
                $dados = explode(";", $linha);
                if ($dados[0] == $cod) {
                    $achou = true;
                    if ($acao === "U") {
                        $novas[] = implode(";", [$cod, $nome, $email, $senha]);
                    }
                } else {
                    $novas[] = $linha;
                }
            }

            if ($achou) {
                $m = fopen($arquivo, "w");
                foreach ($novas as $l) {
                    fwrite($m, $l . PHP_EOL);
                }
                fclose($m);
                $msg = ($acao === "U") ? "Usuário alterado com sucesso!" : "Usuário excluído com sucesso!";
            } else {
                $msg = "Código de usuário não encontrado.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Usuários</title>
    <style>
        body { font-family: sans-serif; background: #fff; color: #000; padding: 20px; }
        .campo { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"], input[type="email"], input[type="password"], select { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #000; }
        button { background: #000; color: #fff; border: none; padding: 8px 16px; cursor: pointer; }
        table, th, td { border: 1px solid #000; border-collapse: collapse; padding: 6px; }
        th { background: #f0f0f0; }
        .msg { font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2>Gerenciador de Usuários (CRUD)</h2>

<?php if ($msg !== ""): ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="campo">
        <label>Ação desejada:</label>
        <select name="acao" required>
            <option value="C">Cadastrar Novo Usuário</option>
            <option value="U">Alterar Usuário Existente</option>
            <option value="D">Excluir Usuário</option>
        </select>
    </div>

    <div class="campo">
        <label>Código do Usuário (Obrigatório para Alterar/Excluir):</label>
        <input type="text" name="cod_usuario" placeholder="Ex: 1700000000">
    </div>

    <div class="campo">
        <label>Nome:</label>
        <input type="text" name="nome">
    </div>

    <div class="campo">
        <label>E-mail:</label>
        <input type="email" name="email">
    </div>

    <div class="campo">
        <label>Senha:</label>
        <input type="password" name="senha">
    </div>

    <button type="submit">Executar Operação</button>
</form>

<hr>

<h3>Usuários Cadastrados</h3>
<table>
    <tr>
        <th>Código</th>
        <th>Nome</th>
        <th>E-mail</th>
    </tr>
    <?php
    if (file_exists($arquivo)) {
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $i => $linha) {
            if ($i === 0) continue;
            $dados = explode(";", $linha);
            echo "<tr>";
            echo "<td>" . $dados[0] . "</td>";
            echo "<td>" . $dados[1] . "</td>";
            echo "<td>" . $dados[2] . "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>

</body>
</html>