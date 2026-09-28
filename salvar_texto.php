<?php

$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

         $codPergunta = time();
    $desafio = trim($_POST["desafio"]);
    $enunciado = trim($_POST["enunciado"]);

        $dadosPergunta = [
    $codPergunta,
        $desafio,
        $enunciado
    ];

    $arqPerguntas = "perguntas.txt";
     $novoArqPerguntas = !file_exists($arqPerguntas);
         $manipuladorPerguntas = fopen($arqPerguntas, "a");

    if ($manipuladorPerguntas)  {
        if ($novoArqPerguntas)      {
            fwrite($manipuladorPerguntas, "cod_pergunta;desafio;enunciado" . PHP_EOL);
            }

          $linhaPergunta = implode(";", $dadosPergunta) . PHP_EOL;
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

             $dadosResposta = [
                    $codResposta,
                $codPergunta,
                $orientacaoGabarito,
                "1"
                 ];

              $linhaResposta = implode(";", $dadosResposta) . PHP_EOL;
            fwrite($manipuladorRespostas, $linhaResposta);
            fclose($manipuladorRespostas);

            $feedback = "Pergunta discursiva (Código: $codPergunta) salva com sucesso!";
        }    else {
            $feedback = "Erro ao processar o arquivo de respostas.";
         }
    } else  {
        $feedback = "Erro ao processar o arquivo de perguntas.";
     }
    }

echo  "<script>alert('$feedback'); window.location.href='criar_texto.html';</script>";
?>