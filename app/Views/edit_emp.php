<?php
if(isset($_GET['ID'])){
    if(isset(DB::query("SELECT * FROM Emp WHERE ID=:id",array('id'=>$_GET['ID']))[0])){
    if(isset($_POST['senduserdataedit'])){
        if(isset(DB::query("SELECT * FROM Emp WHERE Name=:name", array('name'=>$_POST['Name']))[0])){
            $da = DB::query("UPDATE Emp set Name=:name, rate=:rate WHERE ID=:id", array("name"=>$_POST['Name'], "rate"=>$_POST['rate'], 'id'=>$_GET['ID']));
            if($da == 1){
                notify("Employee Updated.", 0);
                header("Location:user_data");
            }else{
                notify("There was an error adding that Employee.", 1);
                header("Location:add_emp");
            }
        }else{
            notify("That Employee does not exists.", 1);
        }
    }
    $data = DB::query("SELECT * FROM Emp WHERE ID=:id",array('id'=>$_GET['ID']));
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Edit Employee</h3>
<form action="" method="post">
    <div class="row">
        <div class="input-field col s6">
            <input type="text" name="Name" value='<?php echo $data[0]['Name'];?>'>
            <label>Name</label>
        </div>
        <div class="input-field col s6">
            <input type="text" name="rate" value='<?php echo $data[0]['rate'];?>'>
            <label>Rate</label>
        </div>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdataedit'>
</form>
<?php
        }
    }
?>