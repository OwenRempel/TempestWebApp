<?php
if(isset($_GET['ID'])){
    if(isset(DB::query("SELECT * FROM Unit WHERE ID=:id",array('id'=>$_GET['ID']))[0])){
        if(isset($_POST['senduserdataedit'])){
            $da = DB::query("UPDATE Unit set Name=:name, Rate=:dis, About=:data WHERE ID=:id", array("name"=>$_POST['Name'], "dis"=>$_POST['Dis'], 'data'=>$_POST['About'], 'id'=>$_GET['ID']));
            if($da == 1){
                notify("Unit updated.", 0);
                Header("Location:user_data");
            }else{
                notify("There was an error adding that Unit.", 1);
                header("Location:add_unit");
            }
        }
        $data = DB::query("SELECT * FROM Unit WHERE ID=:id",array('id'=>$_GET['ID']));
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Edit Unit</h3>
<form action="" method="post">
    <div class="input-field col s6">
        <input type="text" name="Name" value='<?php echo $data[0]['Name'];?>'>
        <label>Name</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="Dis" value='<?php echo $data[0]['Rate'];?>'>
        <label>Rate</label>
    </div>
    <div class="input-field col s6">
        <input type="text" name="About" value='<?php echo $data[0]['About'];?>'>
        <label>About</label>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdataedit'>
</form>
<?php
        }
    }
?>
