<?php
$alltotal = 0;
$year = date('Y');
$month = date('n');
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
?>
<style>
    .headtop{
        text-align:center;
    }
    .datetotal{
        text-align:right;
        padding-right:20px;
    }
</style>
<div class="headtop"><h4>Monthly OWNER/OPERATOR Equipment Voucher</h4></div>
<br>

<div class="aboutuser">
    <h5>To:  Tempest Energy Services (2010) Inc.</h5>
    <h6>Name:  T Rempel Backhoe Services Ltd</h6>
    <p>Address: Box 13, Goodlow, BC, V0C 1S0</p>
</div>
<div class="datetotal">
    For The Month of: <?php echo $dates[$month] ." - ".$year;?>
</div>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>BL Number</th>
            <th>Location</th>
            <th>Prov</th>
            <th>Company</th>
            <th>Unit</th>
            <th>Hours</th>
            <th>Rate</th>
            <th>Discount</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php
    
    $getList = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m Order By Date ASC", array('y'=>$year, 'm'=>$month));
     
    foreach($getList as $l){
            $firsttotal = $l['Rate'] * $l['Hours'];
            $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
            $total = $firsttotal - $discount;
            $alltotal += $total;
            echo "
            <tr>
                <td>".$l['Date']."</td>
                <td>".$l['BLNum']."</td>
                <td>".$l['Loc']."</td>
                <td>".$l['Prov']."</td>
                <td>".$l['Comp']."</td>
                <td>".$l['Unit']."</td>
                <td>".$l['Hours']."</td>
                <td>$".$l['Rate']."/hr</td>
                <td>".$l['Dis']."%</td>
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
                <td></td>
                <td><b><strong>Total</strong></b></td>
                <td>$<?php echo $alltotal;?></td>
            </tr>
    </tbody>
</table>