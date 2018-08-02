<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

// Groups list
$groups = $SensorGroup->getAllGroups();
?>

<div class="white-popup" id="sensor_add">

    <h1>Add sensor</h1>

    <form action="sensors_process.php?action=add" method="post" class="pure-form" onsubmit="sensor_addOrEdit('add', $(this)); return false;" data-redirect="sensors.php">
        
        <div class="form-row">
            <label class="label" for="name">Name :</label>
            <input type="text" name="name" id="name" required="required">
        </div>
        
        <div class="form-row">
            <label class="label" for="device">Device :</label>
            <input type="text" name="device" id="device" required="required">
        </div>
            
            <div class="form-row">
                <label class="label" for="name">Color :</label>
                <input type="color" name="color" id="color">
            </div>

        <div class="form-row">
            <label class="label" for="group_id">Group :</label>
            <select name="group_id" id="group_id" required="required">
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group['id']; ?>"><?= $group['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row t-center">
            <input type="submit" name="submit" class="pure-button pure-button-success pure-button-large" value="Save sensor">
        </div>

    </form>

</div>