<?php 
    include('autoloader.php');
    $postData = json_decode(file_get_contents("php://input"));

    $account = new Account();
    $account->validateToken();

  
    $fname = $postData ->fname;
    $lname = $postData ->lname;
    $email = $postData ->email;
    $datebirth = $postData ->datebirth;
    $role = $postData ->role;
    $manager_id = $postData ->manager_id;
    $phone_num = $postData ->phone_num;
    $phone_type = $postData->phone_type;
    $address = $postData ->address;
    $suburb = $postData ->suburb;
    $state = $postData ->state;
    $zip_code = $postData ->zip_code;


    $employee = new Employee();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $employee -> insertEmployee($fname, $lname, $email, $datebirth, $role, $manager_id,
        $phone_num,$phone_type, $address, $suburb, $state, $zip_code);
    }
    else{
        echo 'Post method required.';
    }

    exit;
?>