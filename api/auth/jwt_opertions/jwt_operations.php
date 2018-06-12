<?php

include_once 'jwt_helper.php';
/**
 * All the JWT operations. If token present in API response and establish user identity.  
 * 
 * If the auth token is expired and refresh token is available a new auth token 
 * is generated for the users
 * 
 * @return integer
 */

class JWTOperations{

    public $user_details;
    public $secret_key = 'flyt_security';
    public $auth_valid_for = '3600';
    public $refresh_valid_for = '18000';

    /**
	 * Gets authorisation header and onverts it into associtive array
	 *
	 *
	 * @return array
	 */

    public function getJwtHeader(){

        $headers = getallheaders();
        $auth_header = explode(",", $headers['Authorization']);
    
        $authArray = array();
        
        foreach($auth_header as $value){
                $val = explode(":",$value);
                $authArray[$val[0]] = $val[1];
        }
        
        return $authArray;
    }

    /**
	 * Checks JWT auth token.
	 * If token matches then returns user id 
     * 
     * @return string||bool
	*/
    public function verifyAuthToken() {
    
    $secret_key = 'flyt_security';
    $jwt_token = $this->getJwtHeader();
    $auth_token = $jwt_token['auth_token'];
    
    $decode = JWT::decode($auth_token, $secret_key);
    // check if JWT auth token is expired or not
    if($decode->exp > time()) {
        // token is not expired. Return user details present in the token.
        $this->user_details['id'] = $decode->id;
        $this->user_details['email'] = $decode->email;

        return true;
    } else {
        // token expired. Try to generate new one
        $this->generateNewAuthToken();
    }
        
        return false;
    }

    public function verifyUser(){
        $this-> verifyAuthToken();
        
    }

    public function generateNewAuthToken(){
        
        $jwt_token = $this->getJwtHeader();
        $refresh_token = $jwt_token['refresh_token'];
        $refresh_exp = $jwt_token['refresh_exp'];

        // check if refresh token is expired or not.
        if($refresh_exp > time()){
            // refresh token not expired so check in database and generate a new JWT token
            $refreshTokenVerify = User::checkRefreshToken($refresh_token);
            if($refreshTokenVerify){
                        
            } else {
                echo json_encode(['status'=>'error','message'=>'User not found.']);
                http_response_code(404);    
            }
        } else {
            // refresh token also expired so prompt user to login once again.
            echo json_encode(['status'=>'error','message'=>'Session Expired. Please login once again']);
            http_response_code(500);
        }
    }


    public function generateJWTOnLogin(){

        // $secret_key = 'flyt_security';
        // $valid_for = '3600';
        // $refresh_valid_for = '18000';

        $token = array();
         $token['id'] = $user->id;
         $token['email'] = $user->email;
         $token['exp'] = time() + $this->auth_valid_for;
         $auth_token = $this->encode($token, $this->secret_key);
         $refresh_token = $this->refresh_encode();
         $refresh_token_exp = time() + $this->refresh_valid_for;
         User::update_refresh_token($refresh_token);

         return $jwt_token;
    }

    public function generateAuthToken(){

    }

    public function generateRefreshToken(){
        
    }

}