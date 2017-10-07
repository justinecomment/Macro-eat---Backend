<?php 

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    include_once('login.php');
    require('db.php');
    include_once('validation.php');



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
        if(ISSET($_GET['username']) && ISSET($_GET['password'])){
            $username = $_GET['username'];
            $password = $_GET['password'];

            // Check JWT
            $auth = new userAuth();
            echo $auth->mailUser($username, $password);
        }

    }



?>