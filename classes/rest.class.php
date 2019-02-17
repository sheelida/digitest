<?php 
    class Rest extends Database{
        protected $request, $serviceName, $param;
        protected $dbConn;
        public function __construct(){
            if($_SERVER['REQUEST_METHOD']!== 'POST'){
                $this->throwError("Please select the right method which is 'POST'.");
            }
            $handler = fopen('php://input','r');
            $this ->request = stream_get_contents($handler);
            $this ->validateRequest();

            $db = new Database();
            $this->$dbConn = $db->connDB();   

        }
        public function validateRequest(){

            if($_SERVER['CONTENT_TYPE'] !== 'application/json' ){
                $this->throwError('Content type is not valid.');
            }

            $data =  json_decode($this ->request, true);

            if(!isset($data['name']) || $data['name'] == ""){
               $this-> throwError('API name is required.');
            }
            $this->serviceName = $data['name'];

            if(!is_array($data['param'])) {
               $this->throwError('API Param is required.');
            }
            $this->param = $data['param']; 

        }

        public function processAPI(){

            $api = new Api();
            $refMethod = new reflectionMethod('API', $this->serviceName);
            if(!method_exists($api, $this->serviceName)){
                $this->throwError('This API does not exist!');
            }
            $refMethod->invoke($api);
        }
        public function validateParameter($fieldName, $value, $dataType, $required=true){

            if($required === true && empty($value)==true){
                $this->throwError($fieldName.' parameter is required.');
            }

            // switch($dataType){
            //     case BOOLEAN:
            //     if(!is_bool($value)){
            //         $this-> throwError('Invalid datatype for '.$fieldName.'Boolean type expected');
            //     }
            //     break;
            //     case INTEGER:
            //     if(!is_numeric($value)){
            //         $this-> throwError('Invalid datatype for '.$fieldName.'Integer type expected');
            //     }
            //     break;
            //     case STRING:
            //     if(!is_string($value)){
            //         $this-> throwError('Invalid datatype for '.$fieldName.'String type expected');
            //     }
            //     break;
            //     default:
            //     break;
            // }
            return $value;
        }
        public function throwError($message){
            header("content-type: application/json");
            $errorMsg = json_encode(['message'=> $message]);
            echo $errorMsg; exit;
        }
        public function returnResponse($data){
            header("content-type: application/json");
            $response = json_encode(['response'=>$data]);

            echo $response;
            exit;


        }
    }
?>