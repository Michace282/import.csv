<?php
require_once('./services/UploadCSV.php');
require_once('./services/ParseCSV.php');
require_once('./models/Users.php');
if (isset($_POST)) {
    $uploadCsv = new \Services\UploadCSV();

    if ($uploadCsv->load($_FILES)) {
        $parseCsv = new \Services\ParseCSV();
        $pathFile = $uploadCsv->getFilePath();

        $usersfromCSV = $parseCsv->parse($pathFile);

        if ($usersfromCSV) {

            require('./config.php');
            $userModel = new \Models\Users(db_host, db_username, db_password, db_name);
            foreach ($usersfromCSV as $user) {
                if (!$userModel->findByEmail($user['email'])) {
                    if ($user['is manager'] == 'No')
                        $insert = $userModel->insert('delegate', $user['firstname'], $user['lastname'], $user['email']);
                    else
                        $insert = $userModel->insert('manager', $user['firstname'], $user['lastname'], $user['email']);

                    if (!$insert)
                        echo '<br>Пользователь с email - ' . $user['email'] . ' не был импортирован';
                    else
                        echo '<br>Пользователь с email - ' . $user['email'] . ' импортирован';
                } else
                    echo '<br>Пользователь с email - ' . $user['email'] . ' уже существует.';
            }
            //обновление индекса менеджера для пользователей
            foreach ($usersfromCSV as $user) {
                if ($user['is manager'] == 'No') {
                    $manager = $userModel->findByEmail($user['manager email']);
                    $user = $userModel->findByEmail($user['email']);
                    $userModel->updateManagerId($user[0], $manager[0]);
                }
            }

            echo '<br><a href="/users.php">Перейти в в список пользователей</a>';
        }
        else
            echo 'Ошибка парсинка, проверьте csv файл';
    }
    else
        echo $uploadCsv->getError();
}

