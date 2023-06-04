<?php
 $year = date('Y');
 $month = date('n');
 if(isset($_GET['y'])){
     $year = $_GET['y'];
 }
 if(isset($_GET['m'])){
     $month = $_GET['m'];
 }

?>
<a href='list' class='datebtn btn purple darken-2'>Back</a>
<table>
<thead>
    <tr>
        <th></th>
        <th>Date</th>
        <th>BL Number</th>
        <th>Total</th>
    </tr>
</thead>
<tbody>
<?php

$getList = DB::query("SELECT * FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m Order By Date ASC", array('y'=>$year, 'm'=>$month));
$alltotal=0;
foreach($getList as $l){
        $firsttotal = $l['Rate'] * $l['Hours'];
        $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
        $total = $firsttotal - $discount;
        $alltotal += $total;
        $date = strtotime($l['Date']);
        echo "
        <tr>
            <td><a href='edit_list?ID=".$l['ID']."'>Edit</a> </td>
            <td>".Date("M d Y",$date)."</td>
            <td>".$l['BLNum']."</td>
            <td>$$total</td>
        </tr>";
    }
    

?>
        <tr>
            <td></td>
            <td></td>
            <td><b><strong>Total</strong></b></td>
            <td>$<?php echo $alltotal;?></td>
        </tr>
</tbody>
</table>