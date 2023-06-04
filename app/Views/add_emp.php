<?php
    if(isset($_POST['senduserdata'])){
        if(!isset(DB::query("SELECT * FROM Emp WHERE Name=:name", array('name'=>$_POST['Name']))[0])){
            $da = DB::query("INSERT INTO Emp (Name, rate) Values (:name, :rate)", array("name"=>$_POST['Name'], "rate"=>$_POST['rate']));
            if($da == 1){
                notify("Employee added.", 0);
                header("Location:user_data");
            }else{
                notify("There was an error adding that Employee.", 1);
                header("Location:add_emp");
            }
        }else{
            notify("That Employee already exists.", 1);
        }
    }
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Add Employee</h3>
<form action="" method="post">
    <div class="row">
        <div class="input-field col s6">
            <input type="text" name="Name">
            <label>Name</label>
        </div>
        <div class="input-field col s6">
            <input type="text" name="rate">
            <label>Rate</label>
        </div>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdata'>
</form>