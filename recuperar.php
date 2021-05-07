<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=screen_casts',
    'mauro'
);

if (array_key_exists('token', $_GET)) {
    $sql = "SELECT id, username FROM users WHERE token = ?";
    $st = $pdo->prepare($sql);
    $st->bindValue(1, $_GET['token']);
    $st->execute();
    if ($result=$st->fetch(PDO::FETCH_ASSOC)) {
        $sql = "UPDATE users SET token = null WHERE id = {$result['id']};";
        $pdo->exec($sql);
        ?>
        <h1>Bienvenido <?php echo $result['username'];?></h1>
        <form method="post">
            <input type="hidden" value="<?php echo $result['id'];?>" name="uid"/>
            <table>
                <tr>
                    <td><label for="newPassword">Ingresa tu nueva contrase&ntilde;a</label></td>
                    <td><input type="password" name="newPassword" id="newPassword"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Enviar"/>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
} elseif (array_key_exists('uid', $_POST)) {
    $sql = 'UPDATE users SET password = '.password_hash($_POST['newPassword'], PASSWORD_BCRYPT).' WHERE id = '.$_POST['uid'];
    $pdo->exec($sql);

    echo "Contrase&ntilde;a cambiada!";
}