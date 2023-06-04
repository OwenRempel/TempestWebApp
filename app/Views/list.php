<?php

if(isset($_POST['listsend'])){
    $com = explode(',', $_POST['Comp']);
    $un = explode(',', $_POST['Unit']);
    $da = DB::query("INSERT INTO List (Date, BLNum, Loc, Prov, Comp, Unit, emp, Hours, Rate, Dis)
        Values (:date, :bln, :l, :p, :co, :un, :em, :hr, :ra, :dis)",array(
            "date"=>$_POST['Date'],
            "bln"=>$_POST['BLNum'],
            "l"=>$_POST['Loc'],
            "p"=>$_POST['Prov'],
            "co"=>$com[0],
            "un"=>$un[0],
            "em"=>$_POST['emp'],
            "hr"=>($_POST['Hours'] == '' ?  0 : $_POST['Hours']),
            "ra"=>$un[1],
            "dis"=>$com[1],
        ));
        if($da == 1){
            notify('Sucess', 0);
            header("location:list");
        }
}


$comp = DB::query("SELECT * from Comp Order BY Name asc");
$unit = DB::query("SELECT * from Unit Order BY Name asc");
$emp = DB::query("SELECT * from Emp Order BY Name asc");
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
echo "<h3>".$dates[$month]." ".$year."</h3>"; 
if($month == 12){
    echo "<a href='?y=".$year."&m=".($month - 1)."' class=' datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".($year + 1)."&m=".(1)."' class='btn purple darken-2'>Next</a>";
}elseif($month == 1){
    echo "<a href='?y=".($year - 1)."&m=".(12)."' class='datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".$year."&m=".($month + 1)."' class='btn purple darken-2'>Next</a>";
}else{
    echo "<a href='?y=".$year."&m=".($month - 1)."' class='datebtn btn purple darken-2'>Prev</a><a href='?y=".date('Y')."&m=".date('n')."' class='datebtn btn purple darken-2'>Today</a>  <a href='?y=".$year."&m=".($month + 1)."' class='btn purple darken-2'>Next</a>";
}
?>
<div class="printbtn"><a href='rev?y=<?php echo $year;?>&m=<?php echo $month; ?>' class='datebtn btn purple darken-2'>Review</a><a href='print?y=<?php echo $year;?>&m=<?php echo $month; ?>' class='datebtn btn purple darken-2'>Print</a></div>
<table>
    <thead>
        <tr>
            <th></th>
            <th>Date</th>
            <th>BL Number</th>
            <th>Location</th>
            <th>Province</th>
            <th>Company</th>
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
    
    $getList = DB::query("SELECT Date, BLNum, Loc, Prov, 
    (SELECT Name FROM Comp WHERE ID=List.Comp) as Comp, 
    (SELECT Name FROM Unit WHERE ID=List.Unit) as Unit,
    (SELECT Name FROM Emp WHERE ID=List.emp) as emp, Hours, Rate, Dis, ID FROM List WHERE YEAR(Date) = :y AND MONTH(Date) = :m Order By Date Desc", array('y'=>$year, 'm'=>$month));
    foreach($getList as $l){
            $firsttotal = $l['Rate'] * $l['Hours'];
            $discount = ($l['Rate'] * $l['Hours']) * ($l['Dis'] / 100);
            $total = $firsttotal - $discount;
            $alltotal += $total;
            echo "
            <tr>
                <td><a href='edit_list?ID=".$l['ID']."'>Edit</a> | <a href='del_list?ID=".$l['ID']."'>Delete</a></td>
                <td>".$l['Date']."</td>
                <td>".$l['BLNum']."</td>
                <td>".$l['Loc']."</td>
                <td>".$l['Prov']."</td>
                <td>".$l['Comp']."</td>
                <td>".$l['Unit']."</td>
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
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b><strong>Total</strong></b></td>
                <td>$<?php echo $alltotal;?></td>
            </tr>
    </tbody>
</table>

<h3>Add New Entry</h3>
<form action="" method="post">
<div class="row">
<div class="input-field col s6">
        <input type="text" name="Date" class="datepicker" value='<?php  echo date('Y-m-d');?>'>
        <label>Date</label>
    </div>
    <div class="input-field col s6">
        <input type="number" name="BLNum">
        <label>BL Number</label>
    </div>
</div>
<div class="row">
<div class="input-field col s6 ">
        <input type="text" name="Loc">
        <label>Location</label>
    </div>
    <div class="input-field col s6">
    <label class='active'>Province</label>
        <select name='Prov'>
            <option value='AB'>AB</option>
            <option value='BC' selected>BC</option>
        </select>
        
    </div>
</div>
<div class="row">
<div class="input-field col s6">
        <input type="number" step='any' name="Hours">
        <label>Hours</label>
    </div>
    <div class="input-field col s6">
    <label class='active'>Company</label>
        <select name='Comp'>
            <?php
                foreach($comp as $c){
                    echo "<option value='".$c['ID'].",".$c['Dis']."'>".$c['Name']."  ".$c['Dis']."%</option>";
                }
            ?>
        </select>
        
    </div>
</div>
<div class="row">
<div class="input-field col s6">
    <label class='active'>Unit</label>
        <select name='Unit'>
            <?php
                foreach($unit as $c){
                    echo "<option value='".$c['ID'].",".$c['Rate']."'>".$c['Name']."</option>";
                }
            ?>
        </select>
        
    </div>
    <div class="input-field col s6">
    <label class='active'>Employee</label>
        <select name='emp'>
            <?php
                foreach($emp as $c){
                    echo "<option value='".$c['ID']."'>".$c['Name']."</option>";
                }
            ?>
        </select>
        
    </div>
</div>
    
   
    
   
    
    <input type="submit" class='btn purple darken-2' name='listsend'>
    <br>
    <br>

    <br>

</form>