<a href='list' class='datebtn btn purple darken-2'>Back</a>
<?php

if(isset($_POST['list_edit_send'])){
    $unit = explode(',', $_POST['Unit']);
    $da = DB::query("UPDATE List SET Date=:date, BLNum=:bln, Loc=:l, Prov=:p, Hours=:hr, Unit=:un, emp=:em, Rate=:ra, Dis=:di WHERE ID=:id",array(
            "date"=>$_POST['Date'],
            "bln"=>$_POST['BLNum'],
            "l"=>$_POST['Loc'],
            'un'=>$unit[0],
            "ra"=>$unit[1],
            "di"=>$_POST['Dis'],
            "p"=>$_POST['Prov'],
            'em'=>$_POST['emp'],
            "hr"=>($_POST['Hours'] == '' ?  0 : $_POST['Hours']),
            'id'=>$_GET['ID']
        ));
        if($da == 1){
            notify('Sucess', 0);
            header("location:list");
        }
}
    if(isset($_GET['ID'])){
        $comp = DB::query("SELECT * from Comp Order BY Name asc");
        $unit = DB::query("SELECT * from Unit Order BY Name asc");
        $emp = DB::query("SELECT * from Emp Order BY Name asc");
        $data = DB::query('SELECT * from List WHERE ID=:id', array('id'=>$_GET['ID']));
        print_r($data);
        if(isset($data[0])){
            $data = $data[0];
?>
<h3>Edit Entry</h3>
<form action="" method="post">
<div class="row">
<div class="input-field col s6">
        <input type="text" name="Date" class="datepicker" value='<?php  echo $data['Date'];?>'>
        <label>Date</label>
    </div>
    <div class="input-field col s6">
        <input type="number" name="BLNum" value='<?php  echo $data['BLNum'];?>'>
        <label>BL Number</label>
    </div>
</div>
<div class="row">
<div class="input-field col s6 ">
        <input type="text" name="Loc" value='<?php  echo $data['Loc'];?>'>
        <label>Location</label>
    </div>
    <div class="input-field col s6">
    <label class='active'>Province</label>
        <select name='Prov'>
            <option value='AB' <?php  echo ($data['Prov'] == "AB" ? 'selected' : "" );?>>AB</option>
            <option value='BC' <?php  echo ($data['Prov'] == "BC" ? 'selected' : "" );?>>BC</option>
        </select>
        
    </div>
</div>
<div class="row">
    <div class="input-field col s6">
        <input type="number" step='any' name="Hours" value='<?php  echo $data['Hours'];?>'>
        <label>Hours</label>
    </div>
    <div class="input-field col s6">
        <input type="number" step='any' name="Dis" value='<?php  echo $data['Dis'];?>'>
        <label>Discount</label>
    </div>
</div>
<div class="row">
<div class="input-field col s6">
    <label class='active'>Unit</label>
        <select name='Unit'>
            <?php
                foreach($unit as $c){
                    if($data['Unit'] == $c['ID']){
                        echo "<option value='".$c['ID'].",".$c['Rate']."'selected>".$c['Name']."</option>";
                    }else{
                        echo "<option value='".$c['ID'].",".$c['Rate']."'>".$c['Name']."</option>";
                    }
                }
            ?>
        </select>
        
    </div>
    <div class="input-field col s6">
    <label class='active'>Employee</label>
        <select name='emp'>
            <?php
                foreach($emp as $c){
                    if($data['emp'] == $c['ID']){
                        echo "<option value='".$c['ID']."' selected>".$c['Name']."</option>";
                    }else{
                        echo "<option value='".$c['ID']."'>".$c['Name']."</option>";
                    }
                }
            ?>
        </select>
        
    </div>
</div>
    <input type="submit" class='btn purple darken-2' name='list_edit_send'>
    <br>
    <br>

    <br>

</form>

        <?php
        }else{
            notify('That is not an entry', 1);
            header("location:list");
        }
    }else{
        notify('please Supply an ID', 1);
        header("location:list");
    }
?>
