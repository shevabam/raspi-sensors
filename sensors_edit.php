<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

$sensor = false;
if (isset($_GET['id']) && (int)$_GET['id'] > 0)
{
    $sensor_id = (int)$_GET['id'];
    $sensor = $Sensor->getById($sensor_id);
}

// Groups list
$groups = $SensorGroup->getAllGroups();

// Enabled list
$enabled_list = array(0 => 'Disabled', 1 => 'Enabled');
?>

<div class="white-popup" id="group_edit">

    <h1>Edit sensor</h1>

    <?php if (!$sensor): ?>
        <div class="pure-message pure-message-error">Error loading this sensor</div>
    <?php else: ?>
        <form action="sensors_process.php?action=edit&id=<?= $sensor_id; ?>" method="post" class="pure-form" id="sensor_edit" onsubmit="sensor_addOrEdit('edit', $(this)); return false;" data-redirect="sensors.php">
            
            <div class="form-row">
                <label class="label" for="name">Name :</label>
                <input type="text" name="name" id="name" required="required" value="<?= $sensor['name']; ?>">
            </div>
            
            <div class="form-row">
                <label class="label" for="name">Device :</label>
                <input type="text" name="device" id="device" required="required" value="<?= $sensor['device']; ?>">
            </div>
            
            <div class="form-row">
                <label class="label" for="name">Color :</label>
                <input type="color" name="color" id="color" value="<?= $sensor['color']; ?>">
            </div>

            <div class="form-row">
                <label class="label" for="group_id">Group :</label>
                <select name="group_id" id="group_id" required="required">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id']; ?>"<?= $group['id'] == $sensor['group_id'] ? ' selected="selected"' : ''; ?>><?= $group['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label class="label" for="enabled">Enabled ?</label>
                <select name="enabled" id="enabled" required="required">
                    <?php foreach ($enabled_list as $k => $v): ?>
                        <option value="<?= $k; ?>"<?= $k == $sensor['enabled'] ? ' selected="selected"' : ''; ?>><?= $v; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row t-center">
                <input type="submit" name="submit" class="pure-button pure-button-success pure-button-large" value="Save sensor">
            </div>

        </form>
    <?php endif; ?>

</div>