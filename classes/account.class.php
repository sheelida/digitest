<?php

    class Account extends Database{
        function __construct(){
            parent::__construct();
        }

        public function insertAccount(){

            $query = "INSERT INTO tb_login(username,password) VALUES ('teste','123')";

            try{
                $statement = $this -> connection -> prepare($query);
                $statement -> bind_param('s', $password);
                $success = $statement -> execute();          
            }catch(Exception $e){
                echo 'Error message: '.$e->getMessage();
            }            
        } 

        public function authenticate($username, $password){
            $query = "SELECT password FROM tb_login WHERE username = ?";

            try{
                $statement = $this -> connection -> prepare($query);
                $statement -> bind_param('s', $username);
                $success = $statement -> execute();
                $result = $statement -> get_result();
            }catch(Exception $e){
                echo 'Error message: '.$e->getMessage();
            }
            $row = $result-> fetch_row();
            // $hash = $row[0];            
            // $match = password_verify($password, $hash);
            if($password == $row[0]){               
                $this->generateToken($username);
                }
            else{
                header('HTTP/1.0 401 Unauthorized');
                echo  json_encode(array("failed"=>$result));
            }          

        }
        public function returnResponse($msg, $data) {
			header("content-type: application/json");
			$response = json_encode(['status' => $msg, 'result' => $data]);
			echo $response;
        }
        

        public function generateToken($username){
            $payload = [ 
                'iat'=>time(),
                'iss'=>'localhost',
                'exp'=>time()*(3000),
                'username'=>$username
            ];
            $token = JWT::encode($payload, getenv("key"));
            
            $this->returnResponse('Success login!','Token:'.$token);
        }
        public function validateToken(){


                $token = $this->getBearerToken();
                if($token != null){
                    $payload = JWT::decode($token, getenv("key"), ['HS256']);
                }
                else{
                    throw new Exception("Token not found.");
                }
                

            try{
                //check with DB
                $query = ("SELECT * FROM tb_login WHERE username = ?");
                
                $statement= $this->connection->prepare($query);
                $statement->bind_param('s', $payload->username);
                $success = $statement->execute();
                $result = $statement->get_result();
                if ($success == false){
                    throw new Exception('Query failed');
                }
                else{
                   
                    if($result->num_rows==0){
                        throw new Exception('Username not found!');
                        header('HTTP/1.0 401 Unauthorized');
                        echo  json_encode(array("failed"=>$result));
                    }
                    else{
                        $this->returnResponse('Success authentication!','Token:'.$token);
                        return $token;
                    }
                }

            }catch(Exception $e){
                echo 'Error message: '.$e->getMessage();
            }

        }



        public function getAuthorizationHeader(){
	        $headers = null;
	        if (isset($_SERVER['Authorization'])) {
	            $headers = trim($_SERVER["Authorization"]);
	        }
	        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	        } elseif (function_exists('apache_request_headers')) {
	            $requestHeaders = apache_request_headers();
	            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	            if (isset($requestHeaders['Authorization'])) {
	                $headers = trim($requestHeaders['Authorization']);
	            }
	        }
	        return $headers;
	    }
	    /**
	     * get access token from header
	     * */
	    public function getBearerToken() {

			try{
				$headers = $this->getAuthorizationHeader();
				// HEADER: Get the access token from the header
				if (!empty($headers)) {
					if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
						return $matches[1];
					}
				}
			}catch(Exception $e){
				echo 'Access token not found!' ;
			}	       
        
    }
    }
?>