<?php
header('Content-Type: application/json');
if(isset($_GET['url'])){
    $url = $_GET['url'];
}else{
    $url = 'none';
}

//this is the database class
class DB{
    private static function connection(){
        $username="coltdata";
        $password="owen@1234";
        $host="localhost";
        $db="mom";
        $pdo = new PDO("mysql:dbname=$db;host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    public static function query($query, $params = array()){
        $stat = self::connection()->prepare($query);
        $stat->execute($params);
        if(explode(" ", $query)[0] == 'SELECT'){
            $data =$stat->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }else{
            return 1;
        }
    }
}

//this is the part that defines the data for the rest of the app
//this is the method to decide what to do give info or upload stuff
$method = $_SERVER['REQUEST_METHOD'];
//this is the data that gets sent to the server
$input = json_decode(file_get_contents('php://input'),true);
$auth = false;
//this is the system to authenticate the users
if(isset($_GET['auth'])){
    $au = DB::query("SELECT * FROM Auth WHERE Auth = :auth", array('auth'=>$_GET['auth']));
    if(isset($au[0])){
        $datetime1 = date_create($au[0]['adate']);
        $datetime2 = date_create(date("Y-m-d H:i:s"));
        $interval = date_diff($datetime1, $datetime2);
        $dates = $interval->format('%a');
        if($dates < 3){
            $auth = true;
        }else{
            $auth = false;
            DB::query('DELETE FROM Auth WHERE ID=:id', array('id'=>$au[0]['ID']));
            echo json_encode(['error'=>"Token Has timed out please login"]);
            http_response_code(401); 
            exit();
        }
    }else{
        echo json_encode(['error'=>"Token is not valid"]);
        http_response_code(401); 
        exit();
    }
    
}
//this is the system to add users to the data base if you know the admin password
if($url == 'add_user' and $method == 'POST'){
    if(isset($_GET['admin'])){
        if($_GET['admin'] == 'owenrempel'){
            if(isset($_POST['user']) and isset($_POST['password'])){

                if(!isset(DB::query("SELECT Username FROM User WHERE Username = :user", array('user'=>$_POST['user']))[0])){
                    $ch = DB::query("INSERT INTO User (Username, Password) Values (:user, :pass)", array('user'=>$_POST['user'], 'pass'=>password_hash($_POST['password'], PASSWORD_BCRYPT)));
                    if($ch == 1){
                        //sucessful user add
                        echo json_encode(['response'=>"User added"]);
                        http_response_code(201); 
                    }else{
                        //unknown error
                        echo json_encode(['error'=>"There was an error adding the user", "desc"=>$ch]);
                        http_response_code(401); 
                    }
                }else{
                    //username already taken
                    echo json_encode(['error'=>"That username already exists"]);
                    http_response_code(401); 
                }
            }else{
                //no data sent
                echo json_encode(['error'=>"Please Include a username and password"]);
                http_response_code(401); 
            }
        }else{
            //admin password was not correct
            echo json_encode(['error'=>"Please enter a Valid Admin Password"]);
            http_response_code(401); 
        }
    }else{
        //they did not include a admin password
        echo json_encode(['error'=>"Please enter an Admin Password"]);
        http_response_code(401); 
    }
    exit();
}
//this is the system to authenticate a user
if($url == 'auth' and $method == 'POST'){
    if(isset($_POST['user']) and isset($_POST['password'])){
        $data = DB::query("SELECT Password FROM User WHERE Username = :user", array('user'=>$_POST['user']));
        if(isset($data[0])){
            $pass = $_POST['password'];
            $hash = $data[0]['Password'];
            if(password_verify($pass, $hash)){
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                $tokendata = DB::query("INSERT INTO Auth (Auth) Values (:auth) ", array('auth'=>$token));
                if($tokendata == 1){
                    echo json_encode(['response'=>"Access Granted", 'token'=>$token]);
                    http_response_code(200); 
                }else{
                    echo json_encode(['error'=>"There was an error loging you in", "desc"=>$tokendata]);
                    http_response_code(401); 
                }
            }else{
                echo json_encode(['error'=>"Password Incorect"]);
                http_response_code(401); 
            }
        }else{
            echo json_encode(['error'=>"Username Incorrect"]);
            http_response_code(401); 
        }
    }else{
        echo json_encode(['error'=>"Please Include a username and password"]);
        http_response_code(401); 
    }
    exit();
} 
//this is where the actual logic for the app is redirecting ppl to the right data
if($auth == true){
    if($url == 'list' and $method == 'GET'){ // this is the if statment that lists all the info from the main site
        if(isset($_GET['year']) and isset($_GET['month'])){

        }elseif(isset($_GET['month'])){

        }else{

        }
        echo json_encode(["Hello my name is owen"]);
    }elseif($url == 'list' and $method == 'POST'){//this is the statment that is used to publish to the main part of the site]
        if(isset($_POST['Date'], $_POST['BLNum'],$_POST['Loc'], $_POST['Prov'], $_POST['Comp'],$_POST['Unit'], $_POST['Hours'])){

        }else{
            echo json_encode(['error'=>"Please include the following post parms Date, BLNum, Loc, Prov, Comp, Unit, Hours"]);
            http_response_code(403);
        }
        echo json_encode(['response'=>"Enter Info"]);
        http_response_code(200);   
    }elseif($url == 'comp' and $method == 'POST'){ //this is the element that alows for adding a company
        if(isset($_POST['Name'], $_POST['Dis'])){
            if(isset($_POST['Data'])){
                $data = $_POST['Data'];
            }else{
                $data = "";
            }
            if(!isset(DB::query("SELECT Name FROM Comp WHERE Name=:name",array('name'=>$_POST['Name']))[0])){
                $da = DB::query("INSERT INTO Comp (Name, Dis, Data) Values (:name, :dis, :data)",array('name'=>$_POST['Name'], "dis"=>$_POST['Dis'], "data"=>$data));
                if($da == 1){
                    echo json_encode(['response'=>"Company Added"]);
                    http_response_code(200); 
                }else{
                    echo json_encode(['error'=>"There was an error", "info"=>$da]);
                    http_response_code(403); 
                }
            }else{
                echo json_encode(['error'=>"That Company already exists"]);
                http_response_code(403);
            }
        }else{
            echo json_encode(['error'=>"Please include the following post parms Name, Dis, Data"]);
            http_response_code(403);
        }
    }elseif($url == 'comp' and $method == 'GET'){ //this is the if statment that lists all the companys
        $da = DB::query("SELECT * FROM Comp Order By Name Asc");
        if(isset($da[0])){
            echo json_encode(['response'=>"Companys Listed", "items"=>$da]);
            http_response_code(200);
        }else{
            echo json_encode(['error'=>"There was an error", "info"=>$da]);
            http_response_code(403); 
        }
    }elseif($url == 'unit' and $method == "POST"){//this allows for the user to add a unit to the site
        if(isset($_POST['Name'], $_POST['Rate'])){
            if(!isset(DB::query("SELECT Name FROM Unit WHERE Name=:name",array('name'=>$_POST['Name']))[0])){
                if(isset($_POST['About'])){
                    $data = $_POST['About'];
                }else{
                    $data = "";
                }
                $da = DB::query('INSERT INTO Unit (Name, Rate, About) Values (:name, :rate, :about)', array("name"=>$_POST["Name"], "rate"=>$_POST['Rate'], 'about'=>$data));
                if($da == 1){
                    echo json_encode(['response'=>"Unit Added"]);
                    http_response_code(200); 
                }else{
                    echo json_encode(['error'=>"There was an error", "info"=>$da]);
                    http_response_code(403); 
                }
            }else{
                echo json_encode(['error'=>"That Unit already exists"]);
                http_response_code(403);
            }
        }else{
            echo json_encode(['error'=>"Please include the following post parms Name, Rate"]);
            http_response_code(403);
        }
    }elseif($url == 'unit' and $method == "GET"){ //this is used to list out all the units
        $da = DB::query("SELECT * FROM Unit Order By Name Asc");
        if(isset($da[0])){
            echo json_encode(['response'=>"Units Listed", "items"=>$da]);
            http_response_code(200);
        }else{
            echo json_encode(['error'=>"There was an error", "info"=>$da]);
            http_response_code(403); 
        }
    }else{ //this is the final case where a user does not enter a valid route
        echo json_encode(['error'=>"Please enter a valid route"]);
        http_response_code(404); 
    }
}else{
    echo json_encode(['error'=>"Please enter a Valid Auth token"]);
    http_response_code(401); 
}