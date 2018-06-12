<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/core.php';
include_once '../../config/database.php';
include_once '../../users/user.php';
include_once '../jwt_opertions/jwt_operations.php';


$database = new Database();
$db = $database->getConnection();
// $secret_key = 'flyt_security';
// $valid_for = '3600';
// $refresh_valid_for = '18000';

if($db){

     $user = new User($db);

     // get post data
    $data = json_decode(file_get_contents("php://input"));

     // set user data
    $user->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $user->password = filter_var($data->password, FILTER_SANITIZE_STRING);
    $user->last_login = date('Y-m-d H:i:s');
    
    if($user->login_user()){


        $jwt = new JWTOperations();
        $jwt->generateJWTOnLogin();
    
        // $token = array();
        // $token['id'] = $user->id;
        // $token['email'] = $user->email;
        // $token['exp'] = time() + $valid_for;
        // $auth_token = JWT::encode($token, $secret_key);
        // $refresh_token = JWT::refresh_encode();
        // $refresh_token_exp = time() + $refresh_valid_for;
        // $user->update_refresh_token($refresh_token);

        echo json_encode(['status'=>'success', 'jwt'=>['auth_token' => $auth_token, 'refresh_token'=>$refresh_token, 'refresh_exp' =>$refresh_token_exp]]);
	}
    

}