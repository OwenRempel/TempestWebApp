<?php
session_start();
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
function notify($error, $type=0){
    if(!isset($_SESSION['error'])){
        $_SESSION['error'] = json_encode([['name'=>$error, 'type'=>$type, 'time'=>time()]]);
    }else{
        $dec = json_decode($_SESSION['error'], true);
        $dec[] = ['name'=>$error, 'type'=>$type, 'time'=>time()];
        $_SESSION['error'] = json_encode($dec); 
    }
}
$auth = 1;
if(isset($_COOKIE['auth'])){
    $au = DB::query("SELECT * FROM Auth WHERE Auth = :auth", array('auth'=>$_COOKIE['auth']));
    if(isset($au[0])){
        $datetime1 = date_create($au[0]['adate']);
        $datetime2 = date_create(date("Y-m-d H:i:s"));
        $interval = date_diff($datetime1, $datetime2);
        $dates = $interval->format('%a');
        if($dates < 3){
            $auth = 0;
            
        }else{
            $auth = 1;
            DB::query('DELETE FROM Auth WHERE ID=:id', array('id'=>$au[0]['ID']));
            setcookie("auth", "", time() - 3600, "/");
            
        }
    }else{
            setcookie("auth", "", time() - 3600, "/");
    }
}else{
    if(isset($_POST['passwordsend'])){
        if(isset($_POST['user']) and isset($_POST['pass'])){
            $data = DB::query("SELECT Password FROM User WHERE Username = :user", array('user'=>$_POST['user']));
            if(isset($data[0])){
                $pass = $_POST['pass'];
                $hash = $data[0]['Password'];
                if(password_verify($pass, $hash)){
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                    $tokendata = DB::query("INSERT INTO Auth (Auth) Values (:auth) ", array('auth'=>$token));
                    if($tokendata == 1){
                        setcookie('auth', $token, time() + (86400 * 5), "/"); // 86400 = 1 day
                        notify('Successfully Logged in');
                        header("location:home");
                    }else{
                        notify('There Was an Error' ,1);
                        header("location:home");
                    }
                }else{
                    notify('Your Passwords Don\'t match',1);
                    header("location:home");
                }
            }else{
                notify('Username Incorecct', 1);
                header("location:home");
            }
        }else{
            notify('Nothing to work with!',1);
            header("location:home");
        }
    }else{
        $auth = 1;
    }
}
if($auth == 1){
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<style>
.login{
    width: 300px;
    margin: auto;
    margin-top: 17vh;
    background-color: #eef3fb00;
    padding: 80px 20px;
    box-shadow: 50px 0px 30px -56px black, -54px 0px 30px -60px black;
}
.logologin{
    width: 100%;
    margin: auto;
}
#error{
            position: fixed;
            bottom: 20px;
            left: 10px;
            padding: 22px;
            transition: all 100ms;
            z-index: 31;

        }  
        .response{
            width: 300px;
            height: 60px;
            margin: 10px 0px;
            z-index: 5;
            background-color: #7b1fa2;
            color: #f5f5f5;
            text-align: center;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 300;
        }
        .error{
            background-color: red;
        }
</style>
<div class="login">
            <img class='logologin' src="logo.png">
            <form action="" method='post' class="log">
                <div class="input-field">
                <input type="text" name="user">
                    <label for="user">Username</label>
                </div>
                <div class="input-field">
                <input type="password" name="pass">
                    <label for="pass">Password</label>
                </div>
                <input type="submit" value="Login" name='passwordsend' class='btn purple lighten-1'>
            </form>
        </div>
        <div id='error'>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <?php
}else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>T Rempel Backhoe Services</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script type = "text/javascript" src = "https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });
        $(document).ready(function(){
            $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' });
        });
    </script>
    <style>
        @media print {
            nav{
                display:none;
            }
        }
        .printbtn{
            text-align:right;
        }
        .brand-logo{
            margin-left:20px;
        }
        .datebtn{
            margin:10px;
        }
        .sticky{
            position:sticky;
            top:0;
        }
        .barcont{
            display: inline-block;
            height: 100%;
            width: calc(100% / 12 - 5px);
        }
        .bars{
            height:180px;
        }
        .val{
            height: 95%;
            width: 100%;
            position: relative;
        }
        .disp{
            width: 100%;
            position: absolute;
            background-color: #7a1fa18a;
            bottom: 0px;
            margin:auto;
        }
        .month{
            text-align:center;
        } 
        #error{
            position: fixed;
            bottom: 20px;
            left: 10px;
            padding: 22px;
            transition: all 100ms;
            z-index: 31;

        }  
        .response{
            width: 300px;
            height: 60px;
            margin: 10px 0px;
            z-index: 5;
            background-color: #7b1fa2;
            color: #f5f5f5;
            text-align: center;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 300;
        }
        .error{
            background-color: red;
        }
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
            box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.726); 
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.726); 
        }
    </style>
</head>
<body>
<div id='error'></div>
<nav class='sticky'>
    <div class="nav-wrapper purple darken-2">
        <a href="home" class="brand-logo ">T Rempel Backhoe Services</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="home">Home</a></li>
        <li><a href="list">List</a></li>
        <li><a href="user_data">Other Data</a></li>
        </ul>
    </div>
</nav>
<div class="content container l10">
    <?php
        if(isset($_GET['url'])){
            $url = strtolower($_GET['url']);
        }else{
            $url = "home";
        }
        if(file_exists("./Views/$url.php")){
            require_once("./Views/$url.php");
        }else{
            require_once("error.php");
        }
    ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src='js/main.js'></script>
</body>
</html>
<?php
}

    if(isset($_SESSION['error'])){
        $check = json_decode($_SESSION['error'], true);
        $error = 0;
        foreach($check as $r){
            if(abs($r['time'] - time()) > 3){
                $error = 1;
            }
        }
        //echo "true";
        if($error == 1){
            unset($_SESSION['error']);
        }else{
            $data = json_encode($check);
        ?>
            <script>
                    var data = `<?php echo $data; ?>`;
                    //console.log(data);
                    JSON.parse(data).forEach(item =>{
                        if(item.type == 1){
                            var type = "error";
                        }else{
                            var type = " ";
                        }
                        var out = "<div class='text response "+type+"'>"+item.name+"</div>";
                        document.getElementById('error').innerHTML += out;
                        setTimeout(function(){
                            document.getElementById('error').innerHTML = "";
                        }, 1000);
                    });
            </script>
        <?php
        }
        function errorsel($error, $type=0){
            ?>
            <script>
                    var wh = document.getElementById("error");
                    wh.innerHTML = "<?php echo $error; ?>";
                    wh.classList = "errorShow white-text <?php echo ($type == 1 ? 'red' : "green darken-3");?>";
                    setTimeout(() => {
                        wh.style.display = "none";
                    }, 6000);
            </script>
            <?php
        }
    }

    ?>

