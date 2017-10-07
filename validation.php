<?php

    use \Firebase\JWT\JWT;
    require_once('JWT.php');
    
    require_once 'vendor/autoload.php';
    
class userAuth {
    private $id;
    private $email;
    private $key = "secretSignKey";

    // Checks if the user exists in the database
    private function validUser($email, $password) {
        $connection = new PDO("mysql:host=localhost;dbname=macroeat",'justine','admin');

        $username = $_GET['username'];
        $password = $_GET['password'];

        $sql = $connection->query("SELECT username, password FROM login WHERE username = '$username' && `password`= '$password';");
        while ($result = $sql->fetch()){
            $data = array('username' => $result['username'],
                          'password' => $result['password']);
        }

        if($data['username'] == $username && $data['password'] == $password){
            return true;
        }
        else{
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
        return JWT::encode($payload, $this->key);
    }

    // sends signed token in email to user if the user exists
    public function mailUser($email, $password) {
        // check if the user exists
        if ($this->validUser($email, $password)) {
                $token = $this->genJWT();
                return $token;
        } 
        else{
            return 'Wrong Email/Password';
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