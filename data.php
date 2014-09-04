<?php

$pdoUsers = new PDO('sqlite:responses_users.sqlite');
$pdoUsers->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoUsers->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$question = 1;
if (isset($_GET['question'])) {
    $question = $_GET['question'];
}

$statement = $pdoUsers->prepare('SELECT * FROM answers WHERE question = :question');
$statement->bindParam(':question', $question);
$statement->execute();
$answers = $statement->fetchAll();

foreach ($answers as $answer) {
    echo $answer->freeText;
    echo '<br />';
    echo '<br />';
}
