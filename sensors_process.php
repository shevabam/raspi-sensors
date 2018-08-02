<?php
require 'assets/_checker.php';

$action = $_GET['action'];

switch ($action)
{
    // -- Add sensor
    case 'add':

        $result = array('errors' => '', 'datas' => array());

        if (!empty($_POST['name']) && !empty($_POST['device']))
        {
            $name = trim($_POST['name']);
            $device = trim($_POST['device']);
            $color = $_POST['color'];
            $group_id = (int)$_POST['group_id'];
            
            if ($result['errors'] == '')
            {
                $datas = array(
                    'name' => $name,
                    'device' => $device,
                    'color' => $color,
                    'group_id' => $group_id,
                );

                // Add the new sensor to database
                $Sensor->insert($datas);
            }
        }
        else
        {
            $result['errors'] = 'Please fill form !';
        }

        echo json_encode($result);

    break;


    // -- Edit sensor
    case 'edit':

        $result = array('errors' => '', 'datas' => array());

        if (!empty($_POST['name']) && !empty($_POST['device']))
        {
            $sensor_id = (int)$_GET['id'];

            // Test sensor_id in database
            $getSensor = $Sensor->getById($sensor_id);
            if (!$getSensor)
                $result['errors'] = 'The sensor does not exist';

            $name = trim($_POST['name']);
            $device = trim($_POST['device']);
            $color = $_POST['color'];
            $group_id = (int)$_POST['group_id'];
            $enabled = (int)$_POST['enabled'];
            
            if ($result['errors'] == '')
            {
                $datas = array(
                    'name' => $name,
                    'device' => $device,
                    'color' => $color,
                    'group_id' => $group_id,
                    'enabled' => $enabled,
                );

                // Update sensor into database
                $Sensor->update($sensor_id, $datas);

                // Remove cache
                $Sensor->removeCache();
            }
        }
        else
        {
            $result['errors'] = 'Please fill form !';
        }

        echo json_encode($result);

    break;


    // -- Remove sensor
    case 'delete':

        $result = array('errors' => '', 'datas' => array());

        if (isset($_GET['id']) && (int)$_GET['id'] > 0)
        {
            $sensor_id = (int)$_GET['id'];

            // If sensor has temperatures in DB
            $getTempBySensor = $Temperature->getAllBySensorId($sensor_id);
            if (count($getTempBySensor) > 0)
            {
                // Removes all temperatures of this sensor
                $Temperature->removeTempBySensorId($sensor_id);
            }
            
            $Sensor->delete($sensor_id);

            // Remove cache
            $Sensor->removeCache();
        }
        else
        {
            $result['errors'] = 'Not a valid sensor ID';
        }

        echo json_encode($result);

    break;
}

