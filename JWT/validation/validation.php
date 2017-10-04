<?php
 
require_once '/../vendor/autoload.php';
include_once('../../login.php');
use \Firebase\JWT\JWT;
 
class userAuth {
    private $id;
    private $email;
    private $key = "secretSignKey";


    private $user = array(
        "email" => 'ju',
        "password" => 'ju'
    );





     
    // Checks if the user exists in the database
    private function validUser($email, $password) {
    // doing a user exists check with minimal to no validation on user input
        if ($email == $this->user['email'] && $password == $this->user['password']) {
            // Add user email and id to empty email and id variable and return true
            $this->id = $this->user['id'];
            
            $this->email = $this->user['email'];
            return true;
        } 
        else {    
            return false;
        }
    }

    // Generates and signs a JWT for User
    private function genJWT() {
        $payload = array(
            "id" => $this->id,
            "email" => $this->email,
            "exp" => time() + (60 * 60)
        );
    
        // encode the payload using our secretkey and return the token
        return JWT::encode($payload, $this->key);
    }

    // sends signed token in email to user if the user exists
    public function mailUser($email, $password) {
        // check if the user exists
        if ($this->validUser($email, $password)) {
                // generate JSON web token and store as variable
                $token = $this->genJWT();
                // create email
                $message = 'http://ghostffco.de/index.php?token='.$token;
                
                
                // if the email is successful, send feedback to user
                if ($message) {
                    $final = "salut";
                    return $message;
                } 
                else {
                    return 'An Error Occurred While Sending The Email';
                }
        } 
        else{
            return 'We Couldn\'t Find You In Our Database. Maybe Wrong Email/Password Combination';
        }
    }

    // Validates a given JWT from the user email
    private function validJWT($token) {
        $res = array(false, '');
        try {
            $decoded = JWT::decode($token, $this->key, array('HS256'));
        } 
        catch (Exception $e) {
            return $res;
        }

        $res['0'] = true;
        $res['1'] = (array) $decoded;
        return $res;
    }
 
 
    public function validMail($token) {
        // checks if an email is valid
        $tokenVal = $this->validJWT($token);
    
        // check if the first array value is true
        if ($tokenVal['0']) {
            return "Everything went well, time to serve you what you need.";
        } 
        else{
            return "There was an error validating your email. Send another link";
        }
    }
}

?>