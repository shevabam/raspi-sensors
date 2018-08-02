<?php
require 'assets/_checker.php';

$action = $_GET['action'];

switch ($action)
{
    // -- Add group
    case 'add':

        $result = array('errors' => '', 'datas' => array());

        if (!empty($_POST['name']))
        {
            $name = trim($_POST['name']);
            
            if ($result['errors'] == '')
            {
                $datas = array(
                    'name' => $name,
                );

                // Add the new group to database
                $SensorGroup->insert($datas);
            }
        }
        else
        {
            $result['errors'] = 'Please fill form !';
        }

        echo json_encode($result);

    break;


    // -- Edit group
    case 'edit':

        $result = array('errors' => '', 'datas' => array());

        if (!empty($_POST['name']))
        {
            $group_id  = (int)$_GET['id'];

            // Test group_id in database
            $getGroup = $SensorGroup->getById($group_id);
            if (!$getGroup)
                $result['errors'] = 'The group does not exist';

            $name = trim($_POST['name']);
            
            if ($result['errors'] == '')
            {
                $datas = array(
                    'name' => $name,
                );

                // Update group into database
                $SensorGroup->update($group_id, $datas);
            }
        }
        else
        {
            $result['errors'] = 'Please fill form !';
        }

        echo json_encode($result);

    break;


    // -- Remove group
    case 'delete':

        $result = array('errors' => '', 'datas' => array());

        if (isset($_GET['id']) && (int)$_GET['id'] > 0)
        {
            $group_id = (int)$_GET['id'];

            // If group has devices
            $getSensorsByGroup = $Sensor->getAllDevicesByGroup($group_id);
            if (count($getSensorsByGroup) > 0)
            {
                $result['errors'] = 'There are already sensors in this group. Please edit them before deleting this group!';
            }
            else
            {
                $SensorGroup->delete($group_id);
            }
        }
        else
        {
            $result['errors'] = 'Not a valid group ID';
        }

        echo json_encode($result);

    break;
}

