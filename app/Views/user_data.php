<?php
$data = DB::query("SELECT * from Comp Order BY Name asc");
$data1 = DB::query("SELECT * from Unit Order BY Name asc");
$data2 = DB::query("SELECT * from Emp Order BY Name asc");
$data3 = DB::query("SELECT * from User Order BY Username asc");

?>

<div class="other">
<h3>Companys</h3>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Discount %</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data as $c){
                echo "<tr>
                        <td><a href='edit_comp?ID=".$c['ID']."'>Edit</a>  |  <a href='del_comp?ID=".$c['ID']."'>Delete</a> </td>
                        <td>".$c['Name']."</td>
                        <td>".$c['Dis']."</td>
                    </tr>";
            }

        ?>
        </tbody>
    </table>
    <a href='add_comp' class='datebtn btn purple darken-2'>Add Company</a>
</div>
<div class="other">
<h3>Units</h3>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data1 as $c){
                echo "<tr>
                        <td><a href='edit_unit?ID=".$c['ID']."'>Edit</a>  |  <a href='del_unit?ID=".$c['ID']."'>Delete</a> </td>
                        <td>".$c['Name']."</td>
                        <td>".$c['Rate']."</td>
                    </tr>";
            }

        ?>
        </tbody>
    </table>
    <a href='add_unit' class='datebtn btn purple darken-2'>Add Unit</a>
</div>
<div class="other">
<h3>Employees</h3>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data2 as $c){
                echo "<tr>
                        <td><a href='edit_emp?ID=".$c['ID']."'>Edit</a>  |  <a href='del_emp?ID=".$c['ID']."'>Delete</a> </td>
                        <td>".$c['Name']."</td>
                    </tr>";
            }

        ?>
        </tbody>
    </table>
    <a href='add_emp' class='datebtn btn purple darken-2'>Add Employee</a>
</div>
<div class="other">
<h3>Users</h3>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data3 as $c){
                echo "<tr>
                        <td><a href='del_user?ID=".$c['ID']."'>Delete</a> </td>
                        <td>".$c['Username']."</td>
                    </tr>";
            }

        ?>
        </tbody>
    </table>
    <a href='add_user' class='datebtn btn purple darken-2'>Add User</a>
</div>
<br>