<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();
?>

<div class="white-popup" id="manage_edit">

    <h1>Manage</h1>

    <form action="manage_process.php?action=edit" method="post" class="pure-form" onsubmit="manage_edit($(this)); return false;" data-redirect="index.php">

        <?php foreach ($Param->getAll() as $field): ?>
            <div class="form-row">
                <label class="label label-large" for="<?= $field['key']; ?>"><?= $field['key']; ?> :</label>
                <input type="text" name="param[<?= $field['key']; ?>]" id="<?= $field['key']; ?>" required="required" value="<?= $field['value']; ?>" style="width: 40%;">
                <span class="info"><?= $field['desc']; ?></span>
            </div>
        <?php endforeach; ?>

        <div class="form-row">
            <label class="label label-large" for="passwd_chk">Change password ?</label>
            <div>
                <input type="checkbox" name="passwd_chk" id="passwd_chk" onclick="manage_enableChangePasswd();">
                &nbsp;
                <input type="password" name="passwd" id="passwd" disabled="disabled" style="width: 38%;">
                <span class="info" style="margin-left: 240px;">At least 8 characters and 1 number</span>
            </div>
        </div>


        <div class="form-row t-center">
            <input type="submit" name="submit" class="pure-button pure-button-success pure-button-large" value="Save">
        </div>

    </form>

</div>