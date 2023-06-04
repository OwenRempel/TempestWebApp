<?php
if(isset($_GET['ID'])){
    if(isset(DB::query("SELECT * FROM Comp WHERE ID=:id",array('id'=>$_GET['ID']))[0])){
    if(isset($_POST['senduserdataedit'])){
        if(!isset(DB::query("SELECT * FROM Comp WHERE Name=:name", array('name'=>$_POST['Name']))[0])){
            $da = DB::query("UPDATE Comp set Name=:name, Dis=:dis, Data=:data WHERE ID=:id", array("name"=>$_POST['Name'], "dis"=>$_POST['Dis'], 'data'=>$_POST['Data'], 'id'=>$_GET['ID']));
            if($da == 1){
                notify("Company added.", 0);
                header("Location:user_data");
            }else{
                notify("There was an error adding that company.", 1);
                header("Location:add_unit");
            }
        }else{
            notify("That Company already exists.", 1);
        }
    }
    $data = DB::query("SELECT * FROM Comp WHERE ID=:id",array('id'=>$_GET['ID']));
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Edit Company</h3>
<form action="" method="post">
    <div class="input-field col s6">
        <input type="text" name="Name" value='<?php echo $data[0]['Name'];?>'>
        <label>Name</label>
    </div>
    <div class="input-field col s6">
        <input type="number" name="Dis" value='<?php echo $data[0]['Dis'];?>'>
        <label>Discount</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="Data" value='<?php echo $data[0]['Data'];?>'>
        <label>About</label>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdataedit'>
</form>
<?php
        }
    }
?>
