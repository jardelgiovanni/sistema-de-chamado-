<?php $token = $_GET['token'] ?>


<h2>Visualizando chamado:
    <?php 
    $nome = \MySql::conectar()->prepare("SELECT * FROM chamados WHERE token = ?");
    $nome->execute([$token]);
    $nome = $nome->fetch()['nome'];

    echo $nome; 
    ?>
</h2>


<hr>

<h3>Pergunta para o suporte:
    <?php echo $info['pergunta'] ?>
</h3>

<?php

$puxandoInteracao = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamando = ? ");

echo '<hr>';

$puxandoInteracao->execute([$token]);
$puxandoInteracao = $puxandoInteracao->fetchAll();

foreach ($puxandoInteracao as $key => $value) {
    if ($value['admin'] == 1) {
        echo "<p><b>Atendente: </b>" . $value['mensagem'] . "</p>";
    } else {
        echo "<p><b>Você: </b>" . $value['mensagem'] . "</p>";
    }

    echo "<hr>";
}


?>

<?php

if (isset($_POST['responder_chamado'])) {
    $mensagem = $_POST['mensagem'];
    
    $sql = \MySql::conectar()->prepare("INSERT INTO interacao_chamado (id_chamando, mensagem, admin, status ) VALUES (?,?,?,0)");

    $sql->execute(array($token, $mensagem, -1));

    echo '<script>alert("Sua resposta foi enviada com sucesso! Aguarde o admin responde-lo(a) :)")</script>';
    echo '<script>location.href="' . BASE . 'chamado?token=' . $token . '"</script>';
    die();
}

$sql = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamando = ? ORDER BY id DESC");
$sql->execute([$token]);

if ($sql->rowCount() == 0) {
    echo "<p>Aguarde resposta do atendente!</p>";
} else {
    $info = $sql->fetchAll();

    if ($info[0]['admin'] == -1) {
        echo "<p>Aguarde resposta do atendente!</p>";
    } else {
        echo "<form method='POST'>
                <textarea name='mensagem' placeholder='Sua resposta...'></textarea>
                <br>
                <input type='submit' name='responder_chamado' value='Enviar'>
        </form>";
    }
}

?>