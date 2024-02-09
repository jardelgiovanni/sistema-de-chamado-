<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

?>

<h1>chamado</h1>

<?php

if (isset($_POST["acao"])) {
    // pegando dados formulário
    $nome = $_POST['nome'];
    $email = $_POST["email"];
    $pergunta = $_POST["pergunta"];
    $token = md5(uniqid());

    // enviando para o banco
    $sql = \MySql::conectar()->prepare("INSERT INTO chamados VALUES (null,?,?,?,?)");
    $sql->execute([$nome, $pergunta, $email, $token]);

    // enviando para o email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'mail.dominio.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'email@dominio.com';                     //SMTP username
        $mail->Password = 'senha';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('email@dominio.com', 'Sistema');
        $mail->addAddress($email, $nome);

        //Content
        $mail->isHTML(true);  
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Novo chamado aberto';
        $url = BASE . 'chamado?token=' . $token;
        $informacoes = "
        Olá, {$nome}!<br>
        Seu chamado foi aberto com sucesso. Utilize o link abaixo para começar o atendimento<br>
        <b><a href='{$url}'>Acessar</a></b>
        ";
        $mail->Body = $informacoes;

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        echo "<script>alert('Erro ao enviar chamado, verifique seu e-mail se esta correto')</script>";
        
    }

    echo "<script>alert('Chamado aberto! Verifique seu e-mail com as informações')</script>";
}

?>

<form action="" method="POST">
    <input type="text" name="nome" id="nome" placeholder="Seu nome">
    <br>
    <br>
    <input type="email" name="email" id="email" placeholder="Seu e-mail">
    <br>
    <br>
    <textarea name="pergunta" id="pergunta" cols="30" rows="10" placeholder="Em que posso te ajudar?"></textarea>
    <br>
    <br>
    <button type="submit" name="acao"> Enviar</button>
    <!-- <input type="submit" value="Enviar" name="acao"> -->
</form>