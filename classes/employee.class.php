<?php 
    class Employee extends Database{
        function __construct(){
            parent::__construct();
        }

    public function insertEmployee($fname,$lname, $email, $datebirth, $role, 
    $manager_id,$phone_num,$phone_type, $address, $suburb, $state, $zip_code){
        $query = "INSERT INTO tb_employee(fname, lname, email, date_birth, role, 
        manager_id,phone_num, phone_type, address, suburb, state, zip_code)
        VALUES(?,?,?,?, ?, ?, ?, ?, ?, ?,?,?)";      
        
        try{
            $statement = $this -> connection -> prepare($query);
            $statement -> bind_param('sssssissssss', $fname,$lname,$email,$datebirth,$role,$manager_id,$phone_num,$phone_type,$address,$suburb,$state,$zip_code);
            $success = $statement -> execute();
        }catch(Exception $e){
            echo 'Error message: '.$e->getMessage();
        }

        if($success === true){
            echo 'Employee inserted with success!';
        }
        else 
            echo "Sorry, we  couldn't insert this employee.";
    }

    public function listEmployee(){

        $employee = array();

        $query = "SELECT fname, lname, suburb, phone_num FROM tb_employee WHERE phone_type = 'mobile'";
        
        try{
            $statement = $this -> connection -> prepare($query);
            $success = $statement -> execute();
        }catch(Exception $e){
            echo 'Error message: '.$e->getMessage();
        }
       
        $result = $statement -> get_result();
        while($employee = $result -> fetch_assoc()){
            echo $employee['fname'].' ';
            echo $employee['lname'].' ';
            echo $employee['suburb'].' ';
            echo $employee['phone_num'].' ';           
        }              
       if($success === true){
            echo 'Employees listed!';
        }
        else 
            echo "Sorry we couldn't list employees";        
    }

    public function listById($employee_id){

        $query = "SELECT e.fname, e.lname, e.phone_num, e.role,m.role as managerRole, m.fname as managerName,m.lname as  managerLname,m.email,m.phone_num as managerPhone 
        FROM tb_employee as e LEFT JOIN tb_employee as m
        ON e.manager_id = m.employee_id where e.employee_id = ?";

        try{
            $statement = $this -> connection -> prepare($query);
            $statement -> bind_param('i', $employee_id);
            $success = $statement -> execute();

            if($success == false){
                throw new Exception('Query failed!');            
            }
            else{
                $result = $statement->get_result();
                if($result->num_rows==0){
                    throw new Exception('Employee id not found.');
                }
                else{
                    $row = $result->fetch_assoc();
                    echo json_encode($row);
                    echo 'Employee listed!';
                }
            }
        }catch(Exception $e){
            echo 'Error message: '.$e->getMessage();
        }        

    }

    public function updateEmployee($employee_id, $fname, $lname ){

        $query = "UPDATE tb_employee SET fname = ?, lname = ? where employee_id = ?";

        try{
            $statement = $this -> connection -> prepare($query);
            $statement -> bind_param('ssi', $fname,$lname,$employee_id);
            $success = $statement -> execute();

        }catch(Exception $e){
            echo 'Error message: '.$e->getMessage();
        }

        if($success === true){
            echo 'Employee updated!';
        }
        else 
            echo "Sorry we couldn't update employee";     
    }

    public function deleteEmployee($employee_id){
        $query = "DELETE FROM tb_employee where employee_id = ?";
        try{
            $statement = $this -> connection -> prepare($query);
            $statement -> bind_param('i', $employee_id);
            $success = $statement -> execute();       
              
        }catch(Exception $e){
            echo 'Error message: '.$e->getMessage();
        }

        if($success === true){
            echo 'Employee deleted!';
        }
        else 
            echo "Sorry we couldn't delete employee";  

    }
    }


?>
