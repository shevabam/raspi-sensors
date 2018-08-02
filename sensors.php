<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

// ------- 

require 'assets/header.php';

echo Misc::makeNav('sensors');
?>

<div class="main-container" id="sensors">

    <a href="sensors_add.php" class="popup pure-button pure-button-success pure-button-large">Add a sensor</a>
    <br><br>

    <?php
    // Get all groups
    $groups = $SensorGroup->getAllGroups();

    if (count($groups) > 0)
    {
        foreach ($groups as $group)
        {
        ?>
            <h2><?= $group['name']; ?></h2>

            <?php
            // Get all sensors in this group
            $sensors = $Sensor->getAllSensorsByGroup($group['id']);

            if (count($sensors) > 0)
            {
            ?>
                <table class="pure-table pure-table-horizontal pure-table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Device</th>
                            <th>Color</th>
                            <th># temp.</th>
                            <th>Enabled ?</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sensors as $sensor): ?>
                        <?php
                        $enabled_class = '';
                        $enabled_title = '';
                        if ($sensor['enabled'] == 1)
                        {
                            $enabled_title = 'Enabled';
                            $enabled_class = ' pure-label-success';
                        }
                        else
                        {
                            $enabled_title = 'Disabled';
                            $enabled_class = ' pure-label-error';
                        }
                        ?>
                        <tr>
                            <td class="s_name"><?= $sensor['name']; ?></td>
                            <td class="s_device"><?= $sensor['device']; ?></td>
                            <td class="s_color"><span class="pure-label" style="background-color: <?= $sensor['color']; ?>"><?= $sensor['color']; ?></span></td>
                            <td class="s_number"><?= $sensor['temp_number']; ?></td>
                            <td class="s_enabled"><span class="pure-label<?= $enabled_class; ?>"><?= $enabled_title; ?></span></td>
                            <td class="action edit"><a href="sensors_edit.php?id=<?= $sensor['id']; ?>" class="popup pure-button pure-button-secondary">Edit</a></td>
                            <td class="action delete"><a href="javascript:;" onclick="sensor_remove(<?= $sensor['id']; ?>); return false;" class="pure-button pure-button-error">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            <?php
            }
            else
            {
                echo '<p class="pure-message pure-message-info">No sensor found</p>';
            }
        }
    }
    else
    {
        echo '<p class="pure-message pure-message-info">No group found</p>';
    }
    ?>

</div>


<?php require 'assets/footer.php'; ?>