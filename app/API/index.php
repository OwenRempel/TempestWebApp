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

$dates = [
    1=>'Jan',
    2=>'Feb',
    3=>'Mar',
    4=>'Apr',
    5=>'May',
    6=>'June',
    7=>'July',
    8=>'Aug',
    9=>'Sept',
    10=>'Oct',
    11=>'Nov',
    12=>'Dec',

];

if($url == 'users' and $method == 'GET'){
    echo json_encode(DB::query("SELECT Name, ID from Emp"));
}elseif($url == 'user' and $method == "GET" and isset($_GET['ID'])){
    $us = DB::query('SELECT rate from Emp WHERE ID=:ID', array('ID'=>$_GET['ID']));
    $out = [];
    for ($i=1; $i <= 12; $i++) { 
        $data = DB::query("SELECT Hours, Prov FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m AND emp=:user", array('y'=>Date('Y'), 'm'=>$i, 'user'=>$_GET['ID']));
        $out[$i]['total'] = 0;
        $out[$i]['AB'] = 0;
        $out[$i]['BC'] = 0;
        $out[$i]['m'] = $dates[$i];
        foreach($data as $row){
            $out[$i][$row['Prov']] += ($row['Hours'] * $us[0]['rate']);
            $out[$i]['total'] += ($row['Hours'] * $us[0]['rate']);
        }
    }
        $out[13]['total'] = 0;
        $out[13]['AB'] = 0;
        $out[13]['BC'] = 0;
    foreach($out as $re){
        $out[13]['total'] += $re['total'];
        $out[13]['AB'] += $re["AB"];
        $out[13]['BC'] += $re["BC"];
    }
    $out[13]['m']='Totals';
    echo json_encode($out);
    
}



/*
<?php
    
        foreach($users as $re){
            $yeartotal[$re['Name']] = ['BC'=>0, "AB"=>0];
        }
        //print_r($users);
        foreach($dates as $month1 => $dayname){
            echo "<tr>
            <td>$dayname</td>";
            foreach($users as $user){
                //echo  $user['Name'] ."<br>";
                $getListBC = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m AND emp=:user AND Prov='BC'", array('y'=>$year, 'm'=>$month1, 'user'=>$user['Name']));
                $getListAB = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m AND emp=:user AND Prov='AB'", array('y'=>$year, 'm'=>$month1, 'user'=>$user['Name']));
                if(isset($getListBC[0])){
                    foreach($getListBC as $l){
                        $firsttotal = $l['Rate'] * $l['Hours'];
                        $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
                        $total = $firsttotal - $discount;
                        $yeartotal[$user['Name']]['BC'] += $total;
                    }
                    echo "<td>$$total</td>";
                }else{
                    echo "<td>$0</td>";
                }
                if(isset($getListAB[0])){
                    foreach($getListAB as $l){
                        $firsttotal = $l['Rate'] * $l['Hours'];
                        $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
                        $total = $firsttotal - $discount;
                        $yeartotal[$user['Name']]['AB'] += $total;
                    }
                    echo "<td>$$total</td>";
                }else{
                    echo "<td>$0</td>";
                }
            
            }
        echo "
        </tr>";
    }
        echo "<tr><td><strong>Totals</strong></td>";

            foreach($yeartotal as $ea){
                foreach($ea as $r){
                    echo "<td><strong>$$r</strong></td>";
                }
            }
        echo "</tr>";
    ?>
    */