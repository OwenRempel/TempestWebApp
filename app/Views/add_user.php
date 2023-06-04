<?php
    if(isset($_POST['senduserdata'])){
        if(!isset(DB::query("SELECT Username FROM User WHERE Username = :user", array('user'=>$_POST['user']))[0])){
            if($_POST['password'] == $_POST['passcheck']){
                $da = DB::query("INSERT INTO User (Username, Password) Values (:user, :pass)", array('user'=>$_POST['user'], 'pass'=>password_hash($_POST['password'], PASSWORD_BCRYPT)));
                if($da == 1){
                    notify("User added.", 0);
                    header("Location:user_data");
                }else{
                    notify("There was an error adding that User.", 1);
                    header("Location:add_user");
                }
            }else{
                notify("Your passwords don't match.", 1);
                header("Location:add_user");
            }
        }else{
            notify("That User already exists.", 1);
        }
    }
?>
<a href='user_data' class='datebtn btn purple darken-2'>Back</a>
<h3>Add User</h3>
<form action="" method="post">
    <div class="input-field col s6">
        <input type="text" name="user">
        <label>Username</label>
    </div>
    <div class="input-field col s6">
        <input type="password" name="password">
        <label>Password</label>
    </div>
    <div class="input-field col s6">
        <input type="password" name="passcheck">
        <label>Password Again</label>
    </div>
    <input type="submit" class='btn purple darken-2' value="Submit" name='senduserdata'>
</form>