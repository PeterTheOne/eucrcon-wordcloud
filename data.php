<?php

$pdoUsers = new PDO('sqlite:responses_users.sqlite');
$pdoUsers->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoUsers->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$pdoRegistered = new PDO('sqlite:responses_registered.sqlite');
$pdoRegistered->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoRegistered->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$pdoOtherStakeholders = new PDO('sqlite:responses_other-stakeholders.sqlite');
$pdoOtherStakeholders->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoOtherStakeholders->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$question = 1;
if (isset($_GET['question'])) {
    $question = $_GET['question'];
}

$pdo = $pdoUsers;
if (isset($_GET['database'])) {
    switch ($_GET['database']) {
        case 'registered':
            $pdo = $pdoRegistered;
            break;
        case 'other-stakeholders':
            $pdo = $pdoOtherStakeholders;
            break;
        case 'users':
        default:
            break;
    }
    $question = $_GET['question'];
}

$statement = $pdo->prepare('SELECT * FROM answers WHERE question = :question');
$statement->bindParam(':question', $question);
$statement->execute();
$answers = $statement->fetchAll();

foreach ($answers as $answer) {
    echo $answer->freeText;
    echo '<br />';
    echo '<br />';
}
