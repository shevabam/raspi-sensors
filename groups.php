<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

// ------- 

require 'assets/header.php';

echo Misc::makeNav('groups');
?>

<div class="main-container" id="groups">

    <a href="groups_add.php" class="popup pure-button pure-button-success pure-button-large">Add a group</a>
    <br><br>

    <?php
    $groups = $SensorGroup->getAllGroups();

    if (count($groups) > 0)
    {
    ?>
        <table class="pure-table pure-table-horizontal pure-table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th># sensors</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td class="g_name"><?= $group['name']; ?></td>
                    <td class="g_number"><?= $group['sensors_number']; ?></td>
                    <td class="action edit"><a href="groups_edit.php?id=<?= $group['id']; ?>" class="popup pure-button pure-button-secondary">Edit</a></td>
                    <td class="action delete"><a href="javascript:;" onclick="group_remove(<?= $group['id']; ?>); return false;" class="pure-button pure-button-error">Remove</a></td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    <?php
    }
    else
    {
        echo '<p class="pure-message pure-message-info">No group found</p>';
    }
    ?>

</div>


<?php require 'assets/footer.php'; ?>