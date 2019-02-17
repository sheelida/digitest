<?php 
// database setup
class Database{
    
    private $dbhost, $username, $password, $dbname;
    protected $connection;

    public function __construct(){
    
        $this ->username = getenv("dbuser");
        $this ->password = getenv("dbpassword");
        $this ->dbhost = getenv("dbhost");
        $this ->dbname = getenv("dbname");
    
        $this ->connection = mysqli_connect($this -> dbhost, $this -> username, $this -> password, $this -> dbname);

    }

    public function connDB(){
    
        $this ->connection = mysqli_connect($this -> dbhost, $this -> username, $this -> password, $this -> dbname);
        return $this->connection;

    }
}

?>