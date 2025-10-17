<?php

    if(empty($_SESSION)){

        header('Location: ../index.php');

    }

    session_start();

    $_SESSION = [];
    session_destroy();

    header('Location: ../index.php');

?>

