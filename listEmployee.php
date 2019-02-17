<?php 
    include('autoloader.php');

    $account = new Account();
    $account->validateToken();

    $employee = new Employee();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $employee -> listEmployee();
    }
    else{
        echo 'Post method required.';
    }

    exit;

?>