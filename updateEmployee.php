<?php 
    include('autoloader.php');
    
    $account = new Account();
    $account->validateToken();

    $postData = json_decode(file_get_contents("php://input"));
    
    $employee_id = $postData ->employee_id;
    $fname = $postData ->fname;
    $lname = $postData ->lname;


    $employee = new Employee();

    if($fname != null && $lname !=null && $employee_id != null){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $employee -> updateEmployee($employee_id, $fname, $lname);
        }
        else{
            echo 'Post method required.';
        }
    }
    else{
        echo 'Employee ID, first name and last name required!';
    }


    exit;
?>