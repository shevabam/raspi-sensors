<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();
?>

<div class="white-popup" id="group_add">

    <h1>Add group</h1>

    <form action="groups_process.php?action=add" method="post" class="pure-form" onsubmit="group_addOrEdit('add', $(this)); return false;" data-redirect="groups.php">
        
        <div class="form-row">
            <label class="label" for="name">Name :</label>
            <input type="text" name="name" id="name" required="required">
        </div>

        <div class="form-row t-center">
            <input type="submit" name="submit" class="pure-button pure-button-success pure-button-large" value="Save group">
        </div>

    </form>

</div>