<?php
class Database{
 
    private $host = "localhost";
    private $db_name = "flyt_security";
    private $username = "root";
    private $password = "";
    public $conn;
    
   
    public function getConnection(){
 
        $conn = mysqli_connect( $this->host, $this->username, $this->password, $this->db_name);

        if (!$conn) {
                //echo "Connection failed: " . mysqli_connect_error();
                echo json_encode(['status'=>'error']);
                http_response_code(500);
                return false; 
            }
            return $conn;
        }
}

   
?>