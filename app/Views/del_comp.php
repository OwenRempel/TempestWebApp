<?php
if(isset($_GET['ID'])){
    if(isset($_POST['yes'])){
        $data = DB::query("DELETE From Comp WHERE ID=:id", array("id"=>$_GET['ID']));
        if($data==1){
            notify('Company Deleted');
            header("location:user_data");
        }else{
            notify('Error Deleting Company',1);
            header("location:user_data");
        }
    }
}else{
    notify('That Company doesnt exist', 1);
    header("location:user_data");
}

?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Delete Company</h3>
<form action="" method="post">
    <h3>Are You sure You want to delete this Company!</h3>
    <input type="submit" class='btn purple darken-2' name='yes' Value='Yes'>
</form>