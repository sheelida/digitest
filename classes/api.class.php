<?php 
class Api extends Rest{
    
    public function __construct(){
        parent::__construct();        
  
    }

    public function generateToken(){
        $username = $this->validateParameter('username',$this->param['username'],'STRING');
        $password = $this->validateParameter('password',$this->param['password'],'STRING');

        $query = "SELECT FROM tb_login WHERE username = :username and password = :password";
        $statement = $this->dbConn->prepare($query);
        $statement->bindParam(":username",$username);
        $statement->bindParam(":password",$password);
        $statement->execute();
        $user = fetch_assoc($statement);
        if(!is_array($user)){
            $this->returnResponse('Username or password invalid!');
        }

    $payload = [ 
        'iat'=>time(),
        'iss'=>'localhost',
        'exp'=>time()*(60),
        'userid'=>$user['id']
    ];
    $token = JWT::encode($payload, 'teste123');

    echo $token;

    }
   
}

?>