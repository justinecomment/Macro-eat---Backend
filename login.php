<?php 

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('login.php');
    require_once('db.php');


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);
        $_POST = json_decode(file_get_contents('php://input'), true);

        $username = $_POST['username'];
        $password = $_POST['password'];

        try{
            $sql = $connection->query("SELECT username, password FROM login WHERE username = '$username'");
            if($sql->rowCount()> 0 ){
                throw new Exception('Username existant', 500);
            }
            else{
                $sql = $connection->prepare("INSERT INTO login (username, password) VALUES('$username', '$password')");
                $result = $sql->execute();
                echo json_encode($result);
            }
        }
        catch(Exception $e){
            header("HTTP/1.1 500 Internal Server Error");
            echo 'error: '.$e->getMessage();
        }
    };


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $connection = new PDO("mysql:host=$HOST;dbname=$DBNAME", $USER, $PASS);
        $username = $_GET['username'];
        $password = $_GET['password'];

        $sql = $connection->query("SELECT username, password FROM login WHERE username = '$username' && `password`= '$password';");
        while ($result = $sql->fetch()){
            $data = array('username' => $result['username'],
                          'password' => $result['password']);
        }
        try{
            if($data['username'] !== $username && $data['password'] !== $password )  {
                throw new Exception('Login incorrect', 500);
            }
            else{
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                echo $username;
            }
        }
        catch(Exception $e){
            header("HTTP/1.1 500 Internal Server Error");
            echo 'error: '.$e->getMessage();
        }

    }

?>