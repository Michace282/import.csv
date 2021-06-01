<?php
require_once('./models/Users.php');
require('./config.php');
$userModel = new \Models\Users(db_host, db_username, db_password, db_name);

?>
<table width="100%">
    <tr>
        <td><b>is Manager?</b></td>
        <td><b>Manager Id</b></td>
        <td><b>Firstname</b></td>
        <td><b>Lastname</b></td>
        <td><b>Email</b></td>
    </tr>
    <?php
    if ($userModel->findAll())
        foreach ($userModel->findAll() as $user) {
           echo '<tr>
                   <td>'.$user[1].'.</td>
                   <td>'.$user[2].'.</td>
                   <td>'.$user[3].'.</td>
                   <td>'.$user[4].'.</td>
                   <td>'.$user[5].'.</td>
                  </tr>';
        }
    ?>

</table>
