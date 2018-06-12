<?php

class User{
 
    private $connection;
    private $user_table = 'user_login';
    private $user_details_table = 'user_details';
 
    // user properties
    public $id;
    public $email;
    public $password;
    public $name;
    public $phone_number;
    public $address;
    public $organization;
    public $created_at;
    public $updated_at;
    public $last_login;
 
    public function __construct($db){
        $this->connection = $db;
    }

    public function create(){
    // prepare query 
    
    $query = "INSERT INTO $this->user_details_table (name, phone_number, address, organization, created_at, updated_at)
             VALUES ('$this->name', $this->phone_number, '$this->address', '$this->organization', '$this->created_at', '$this->updated_at')";

     if (mysqli_query($this->connection, $query)) {
        
         $last_id = mysqli_insert_id($this->connection);
        return $this->updateLoginTable($last_id);  
      }
    
      return false;
    }

    public function updateLoginTable($signup_id){
    
    $query = "INSERT INTO $this->user_table (e_mail, user_password, last_login, details_id)
             VALUES ('$this->email', '$this->password', '0', '$signup_id')";
    
    if(mysqli_query($this->connection, $query)){
        return true;
    } else {
        // delete the 'user details table' entry
        $query = "DELETE FROM $this->user_details_table WHERE id=" . $signup_id;
        mysqli_query($this->connection, $query);
        return false;
    }

    }

    public function check_email(){

        $query = "SELECT * FROM $this->user_table WHERE e_mail='$this->email'";
        $result = mysqli_query($this->connection, $query);
        
        if(mysqli_num_rows($result) > 0){
            return false;
        } else {
            return true;
        }
       
    }

    public function login_user(){

        $query = "SELECT id FROM $this->user_table WHERE e_mail='$this->email' AND user_password='$this->password'";
        $result = mysqli_query($this->connection, $query);
        

        if(mysqli_num_rows($result) > 0){
            $this->id = implode(',', mysqli_fetch_row($result));      
            return true;
        } else {
            echo json_encode(['status'=>'error','message'=>'user not found']);
            http_response_code(404);
        }
    }


    public function update_refresh_token($refresh_token){

      $query = "UPDATE $this->user_table SET refresh_token = '$refresh_token' WHERE id = $this->id";  
        
      if(mysqli_query($this->connection, $query)){
          return true;
      } else {
        http_response_code(500);
      }

    }

    public function checkRefreshToken($token){

        $query = "SELECT * FROM $this->user_table WHERE refresh_token = '$token'";
        $result = mysqli_query($this->connection, $query);
        
        if(mysqli_num_rows($result) > 0){
        
        return true;
        
        } else {
            return false;
        }
    }
}