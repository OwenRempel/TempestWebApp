<?php
    if(isset($_POST['senduserdata'])){
        if(!isset(DB::query("SELECT * FROM Unit WHERE Name=:name", array('name'=>$_POST['Name']))[0])){
            $da = DB::query("INSERT INTO Unit (Name, Rate, About) Values (:name, :dis, :data)", array("name"=>$_POST['Name'], "dis"=>$_POST['Rate'], 'data'=>$_POST['About']));
            if($da == 1){
                notify("Unit added.", 0);
                header("Location:user_data");
            }else{
                notify("There was an error adding that Unit.", 1);
                header("Location:add_unit");
            }
        }else{
            notify("That Unit already exists.", 1);
        }
    }
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Add Unit</h3>
<form action="" method="post">
    <div class="input-field col s6">
        <input type="text" name="Name">
        <label>Name</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="Rate">
        <label>Rate</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="About">
        <label>About</label>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdata'>
</form>