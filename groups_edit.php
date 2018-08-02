<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

$group = false;
if (isset($_GET['id']) && (int)$_GET['id'] > 0)
{
    $group_id = (int)$_GET['id'];
    $group = $SensorGroup->getById($group_id);
}
?>

<div class="white-popup" id="group_edit">

    <h1>Edit group</h1>

    <?php if (!$group): ?>
        <div class="pure-message pure-message-error">Error loading this group</div>
    <?php else: ?>
        <form action="groups_process.php?action=edit&id=<?= $group_id; ?>" method="post" class="pure-form" id="group_edit" onsubmit="group_addOrEdit('edit', $(this)); return false;" data-redirect="groups.php">
            
            <div class="form-row">
                <label class="label" for="name">Name :</label>
                <input type="text" name="name" id="name" required="required" value="<?= $group['name']; ?>">
            </div>

            <div class="form-row t-center">
                <input type="submit" name="submit" class="pure-button pure-button-success pure-button-large" value="Save group">
                <button class="pure-button pure-button-error pure-button-large" name="remove" onclick="group_remove(<?= $group['id']; ?>); return false;">Remove this group</button>
            </div>

        </form>
    <?php endif; ?>

</div>