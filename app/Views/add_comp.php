<?php
    if(isset($_POST['senduserdata'])){
        if(!isset(DB::query("SELECT * FROM Comp WHERE Name=:name", array('name'=>$_POST['Name']))[0])){
            $da = DB::query("INSERT INTO Comp (Name, Dis, Data) Values (:name, :dis, :data)", array("name"=>$_POST['Name'], "dis"=>$_POST['Dis'], 'data'=>$_POST['Data']));
            if($da == 1){
                notify("Company added.", 0);
                header("Location:user_data");
            }else{
                notify("There was an error adding that company.", 1);
                header("Location:add_comp");
            }
        }else{
            notify("That Company already exists.", 1);
        }
    }
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Add Company</h3>
<form action="" method="post">
    <div class="input-field col s6">
        <input type="text" name="Name">
        <label>Name</label>
    </div>
    <div class="input-field col s6">
        <input type="number" name="Dis">
        <label>Discount</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="Data">
        <label>About</label>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdata'>
</form>