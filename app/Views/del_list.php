<?php
if(isset($_GET['ID'])){
    if(isset($_POST['yes'])){
        $data = DB::query("DELETE From List WHERE ID=:id", array("id"=>$_GET['ID']));
        if($data==1){
            notify('Entry Deleted');
            header("location:list");
        }else{
            notify('Error Deleting Entry',1);
            header("location:list");
        }
    }
}else{
    notify('That Entry doesnt exist', 1);
    header("location:list");
}

?>

<form action="" method="post">
    <h3>Are You sure You want to delete this Entry!</h3>
    <input type="submit" class='btn purple darken-2' name='yes' Value='Yes'>
</form>