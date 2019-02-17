<?php 
    include('autoloader.php');

    $postData = json_decode(file_get_contents("php://input"));
    
    $account = new Account();
    $account->validateToken();

    $employee_id = $postData ->employee_id;    

    $employee = new Employee();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $employee -> deleteEmployee($employee_id);
    }
    exit;
?>