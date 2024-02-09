<?php

if (isset($_POST['responder_novo_chamado'])) {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];

    $sql = \MySql::conectar()->prepare("INSERT INTO interacao_chamado VALUES (null,?,?,?,1)");
    $sql->execute([$token, $mensagem, 1]);

    echo '<script>alert("Resposta enviada!")</script>';
} else if (isset($_POST['responder_novo_interacao'])) {
    $mensagem = $_POST['mensagem'];
    $token = $_POST['token'];

    \MySql::conectar()->exec("UPDATE interacao_chamado SET status = 1 WHERE id = $_POST[id]");

    $sql = \MySql::conectar()->prepare('INSERT INTO interacao_chamado VALUES (null,?,?,1,1)');
    $sql->execute([$token, $mensagem]);

    echo '<script>alert("Resposta enviada!")</script>';
}

?>

<h2>Novos Chamadas:</h2>

<?php

$pegarChamados = \MySql::conectar()->prepare("SELECT * FROM chamados ORDER BY id DESC");
$pegarChamados->execute();
$pegarChamados = $pegarChamados->fetchAll();

foreach ($pegarChamados as $key => $value) {
    $verificaInteracao = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamando = '$value[token]'");
    $verificaInteracao->execute();

    if ($verificaInteracao->rowCount() >= 1) {
        continue;
    }
    ?>

    <h2>
        <?php echo "Chamado aberto por: " . $value['nome']; ?>
        <hr>
        <?php echo "Pergunta: " . $value['pergunta']; ?>
    </h2>

    <form method="post">
        <textarea name="mensagem" id="mensagem" cols="30" rows="30" placeholder="Sua resposta"></textarea>

        <br><br>

        <input type="submit" name="responder_novo_chamado" value="Responder">

        <input type="hidden" name="token" value="<?php echo $value['token']; ?>">
        <input type="hidden" name="email" value="<?php echo $value['email']; ?>">
    </form>

<?php } ?>

<hr>

<h2>Últimas interações:</h2>

<?php

$pegarChamados = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE admin = -1 AND status = 0 ORDER BY id DESC");
$pegarChamados->execute();
$pegarChamados = $pegarChamados->fetchAll();

foreach ($pegarChamados as $key => $value) {

    ?>

    <h2>
        <?php echo $value['mensagem']; ?>
    </h2>

    <p>
        Clique <a href="<?php echo BASE ?>chamado?token=<?php echo $value['id_chamando']; ?>">aqui</a> para visualizar o
        chamado completo!
    </p>

    <form method="post">
        <textarea name="mensagem" id="mensagem" cols="30" rows="30" placeholder="Sua resposta"></textarea>

        <br><br>

        <input type="submit" name="responder_novo_interacao" value="Responder">

        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
        <input type="hidden" name="token" value="<?php echo $value['id_chamando']; ?>">
    </form>

<?php } ?>