<?php

$year = date('Y');
if(isset($_GET['y'])){
    $year = $_GET['y'];
}

echo "<a href='?y=".($year - 1)."' class=' datebtn btn purple darken-2'>Prev</a><a href='?y=".($year + 1)."' class='btn purple darken-2'>Next</a>";

?>

<h3>Stats for <?php echo $year; ?></h3>
<br>
<h4>Total hours Terry worked this year</h4>
<br>
<h5>  
<?php
    $data = DB::query('SELECT sum(hours) as total FROM List WHERE YEAR(Date) = :y and emp = 3', array('y'=>$year));
    if(isset($data[0]['total'])){
        echo $data[0]['total'];
    }else{  
        echo 0;
    }
?>
</h5>
<br>
<h4>Income AB vs BC</h4>
<?php



$allmonths = [];
$getListBC = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y", array('y'=>$year));
    if(isset($getListBC[0])){
        foreach($getListBC as $l){
            $firsttotal = $l['Rate'] * $l['Hours'];
            $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
            $total = $firsttotal - $discount;
            if(!isset($allmonths[$l['Prov']][0])){
                $allmonths[$l['Prov']][0] = 0;
            }
            $allmonths[$l['Prov']][0] += $total;
        }
    }

echo "<h5>BC: $".(isset($allmonths['BC'][0]) ? $allmonths['BC'][0] : 0)." <br>AB: $".(isset($allmonths['AB'][0]) ? $allmonths['AB'][0] : 0)."</h5>";

echo '<br>';

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
$allmonths = [];
foreach($dates as $t){
    $allmonths[$t] = 0;
}

$high = 0;
foreach($dates as $month1 => $dayname){
    $montha = 0;
    $getListBC = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m", array('y'=>$year, 'm'=>$month1));
    if(isset($getListBC[0])){
        foreach($getListBC as $l){
            $firsttotal = $l['Rate'] * $l['Hours'];
            $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
            $total = $firsttotal - $discount;
            $montha += $total;
            
            $allmonths[$dayname] += $total;
        }
    }
    if($montha > $high){
        $high = $montha;
    }
}

?>  
<h5>Chart of Income Per Month</h5>
<div class="chart">
    <div class="bars">
        <?php
            foreach($allmonths as $m=>$r){
                ?>
                    <div class="barcont">
                        <div class="val">
                            <div class="disp" style='height:<?php echo ($r == 0 ? 0 : (($r/($high + 300))*100));?>%'></div>
                        </div>
                        <div class="month"><?php echo $m;?></div>
                    </div>
                <?php
            }
        ?>
    </div>
</div>
<br>
<br>
<h5>User Income</h5>

<div id="user" class='row'>

</div>



<?php


$ye = [
    $year,
    $year - 1,
    $year - 2,
]
?>
<h5>Total Income By Month</h5>
    <table>
        <thead>
            <tr>
            <th>Year</th>
                <?php
                foreach($dates as $f){
                    echo '<th>'.$f.'</th>';
                }
                ?>
                <th>Year Total</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($ye as $year){
            echo '<tr> <td>'.$year.'</td>';
            $yeartotal = 0;
            foreach($dates as $month => $r){
                $getList = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m Order By Date Desc", array('y'=>$year, 'm'=>$month));
                $alltotal = 0;
                if(isset($getList[0])){
                    foreach($getList as $l){
                        $firsttotal = $l['Rate'] * $l['Hours'];
                        $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
                        $total1 = $firsttotal - $discount;
                        $alltotal += $total1;
                        $yeartotal += $total1;
                        
                    }
                    echo "<td>$$alltotal</td>";
                }else{
                    echo "<td>$0</td>";
                }
                
            }
            echo "<td>$$yeartotal</td></tr>";
        }
        ?>
        </tbody>
    </table>
<?php
    $units = DB::query("SELECT * FROM Unit");
    ?>
    <br>
    <h5>Income by Unit Numbers</h5>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <?php 

                foreach($units as $u){
                    echo "<th>".$u['Name']."</th>";
                }   
                ?>
            </tr>
        </thead>
        <tbody>
        
    <?php
    foreach($units as $u){
        $unitt[$u['Name']] = 0;
    }   
    
    foreach($dates as $month=>$date){
        echo "<tr><td>$date</td>";
        foreach($units as $unit){
            $unit = $unit['Name'];
            $unittotal = 0;
            $un = DB::query('SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m AND Unit = :unit', array('y'=>$year, 'm'=>$month, 'unit'=>$unit));
            if(isset($un[0])){
                foreach($un as $l){
                    $firsttotal = $l['Rate'] * $l['Hours'];
                    $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
                    $total = $firsttotal - $discount;
                    $unittotal += $total;
                    $unitt[$unit] += $total;
                }
            }
            echo "<td>$$unittotal</td>";
        }
        echo "</tr>";
    }
    echo "<tr>
            <td><strong>Total</strong></td>";
            foreach($units as $u){
                echo "<td><strong>$".$unitt[$u['Name']]."</strong></td>";
            }   
    echo"</tr>";
?>
        </tbody>
    </table>