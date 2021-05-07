<?php

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (array_key_exists('username', $_POST)) {
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=screen_casts',
            'mauro'
        );
        $sql = "SELECT email FROM users WHERE username = ?";
        $st = $pdo->prepare($sql);
        $st->bindValue(1, $_POST['username']);
        $st->execute();
        if ($result = $st->fetch(PDO::FETCH_ASSOC)) {
            echo "Enviar mail de recuperacion a {$result['email']}";

            $token = uniqid();

            $sql = "UPDATE users SET token = '$token' WHERE email = '{$result['email']};'";

            try {
                $pdo->exec($sql);
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'user@example.com';                     //SMTP username
                    $mail->Password   = 'secret';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom('mauro.chojrin@leewayweb.com', 'Mauro');

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Recupere su clave';
                    $mail->Body    = 'Haga click en <a href="http://localost:8989/recuperar.php?token='.$token.'">este link</a>';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } catch (PDOException $exception) {
                echo 'No pude guardar el token: '.$exception->getMessage();
            }
        } else {
            echo "No existe ese usuario";
        }
    } catch (PDOException $exception) {
        echo "Fallo la conexion a la base: {$exception->getMessage()}";
    }
}
