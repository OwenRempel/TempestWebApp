<?php
    $user = $_GET['user'];
    echo "<h1>".DB::query('SELECT Name FROM Emp WHERE ID=:user', array('user'=> $_GET['user']))[0]['Name']."</h1>";
    $year = date('Y');
    $month = date('n');
    $alltotal = 0;
    if(isset($_GET['y'])){
        $year = $_GET['y'];
    }
    if(isset($_GET['m'])){
        $month = $_GET['m'];
    }
    
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
    12=>'Dec'
];
echo "<h3>".$dates[$month]." ".$year."</h3>"; 
if($month == 12){
    echo "<a href='?user=".$_GET['user']."&y=".$year."&m=".($month - 1)."' class=' datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".($year + 1)."&m=".(1)."' class='btn purple darken-2'>Next</a>";
}elseif($month == 1){
    echo "<a href='?user=".$_GET['user']."&y=".($year - 1)."&m=".(12)."' class='datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".$year."&m=".($month + 1)."' class='btn purple darken-2'>Next</a>";
}else{
    echo "<a href='?user=".$_GET['user']."&y=".$year."&m=".($month - 1)."' class='datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".$year."&m=".($month + 1)."' class='btn purple darken-2'>Next</a>";
}

$total = 0;
$truck = 0;
$travel = 0;
$user = 0;

$getList = DB::query("SELECT Date, BLNum, Loc, Prov, 
(SELECT Name FROM Comp WHERE ID=List.Comp) as Comp, 
(SELECT Name FROM Unit WHERE ID=List.Unit) as UnitN,
(SELECT Name FROM Emp WHERE ID=List.emp) as emp,
(SELECT Rate FROM Emp WHERE ID=List.emp) as emp_rate, Unit, Hours, Rate, Dis, ID FROM List WHERE emp = :user AND YEAR(Date) = :y AND MONTH(Date) = :m Order By Date Desc", array('user'=> $_GET['user'], 'y'=>$year, 'm'=>$month));

foreach($getList as $rowz){
    if($rowz['Unit'] == 3){
        $truck += ($rowz['Rate'] * $rowz['Hours'])-($rowz['Rate'] * $rowz['Hours']) * ($rowz['Dis'] / 100);
    }elseif($rowz['Unit'] == 4){
        $travel += $rowz['Hours'] * $rowz['emp_rate'];
    }else{
        $user += $rowz['Hours'] * $rowz['emp_rate'];
    }
}
echo '<br><h4>Truck: $'.$truck."</h4>";
echo '<br><h4>Travel: $'.$travel."</h4>";
echo '<br><h4>User: $'.$user."</h4>";
echo '<br><h4>Total: $'.($truck + $travel + $user)."</h4>";

?>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>BL Number</th>
            <th>Unit</th>
            <th>Employee</th>
            <th>Hours</th>
            <th>Rate</th>
            <th>Discount</th>
            <th>Discount Total</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php
    
   
    foreach($getList as $l){
            $firsttotal = $l['Rate'] * $l['Hours'];
            $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
            $total = $firsttotal - $discount;
            $alltotal += $total;
            echo "
            <tr>
                <td>".$l['Date']."</td>
                <td>".$l['BLNum']."</td>
                <td>".$l['UnitN']."</td>
                <td>".$l['emp']."</td>
                <td>".$l['Hours']."</td>
                <td>$".$l['Rate']."/hr</td>
                <td>".$l['Dis']."%</td>
                <td>$$discount</td>
                <td>$$total</td>
            </tr>";
        }
        

    ?>
            <tr>
            
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b><strong>Total</strong></b></td>
                <td>$<?php echo $alltotal;?></td>
            </tr>
    </tbody>
</table>