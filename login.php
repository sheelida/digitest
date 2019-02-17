<?php 
    include('autoloader.php');
    use \Firebase\JWT\JWT;
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type,      Accept");
    $postData = json_decode(file_get_contents("php://input"));

    $username = $postData->username;
    $password = $postData->password; 

    $account = new Account();
  

    if($username != null && $password !=null ){  
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $account -> authenticate($username,$password);  
        }    
    }
    else {
        header('HTTP/1.0 401 Unauthorized');
        echo  json_encode(array("Error:"=>"Username and Password is required!"));
    }
     exit;

?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DigiTest - Login</title>
</head>
<body>
    <form action="" method="POST" name="loginform">
        <p>Username:</p>
        <input type="username" id="username" name="username">
        <p>Password:</p>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>    
    </form>

</body>
</html> -->