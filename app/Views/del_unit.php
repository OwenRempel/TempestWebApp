<?php
if(isset($_GET['ID'])){
    if(isset($_POST['yes'])){
        $data = DB::query("DELETE From Unit WHERE ID=:id", array("id"=>$_GET['ID']));
        if($data==1){
            notify('Unit Deleted');
            header("location:user_data");
        }else{
            notify('Error Deleting Unit',1);
            header("location:user_data");
        }
    }
}else{
    notify('That Unit doesnt exist', 1);
    header("location:user_data");
}

?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Delete Unit</h3>
<form action="" method="post">
    <h3>Are You sure You want to delete this Unit!</h3>
    <input type="submit" class='btn purple darken-2' name='yes' Value='Yes'>
</form>