<?php
/*

/api.php

POST :
  key : API key
  sensor : sensor device
  value : temperature
  date : date from Pi (optional)

*/

require 'assets/_checker.php';

if (isset($_POST))
{
    if (isset($_POST['key']) && !empty($_POST['key']))
    {
        $api_key = $_POST['key'];

        if ($api_key != $Param->get('api_key'))
        {
            Misc::httpHeader("401 Authorization Required");
        }
        else
        {
            if (isset($_POST['sensor']) && isset($_POST['value']) && !empty($_POST['sensor']) && !empty($_POST['value']))
            {
                $sensor = $_POST['sensor'];
                $value  = (float)$_POST['value'];

                if (!isset($_POST['date']) || empty($_POST['date']))
                    $date = date('Y-m-d H:i:s');
                else
                    $date = $_POST['date'];

                $getSensor = $Sensor->getByDevice($sensor);

                if (!$getSensor)
                {
                    Misc::httpHeader("400 Bad Request - Unknown sensor");
                }
                else
                {
                    $sensor_id = (int)$getSensor['id'];
                    
                    $datas = array(
                        'sensor_id'  => $sensor_id,
                        'value'      => $value,
                        'date'       => $date,
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $Temperature->insert($datas);
                }
            }
            else
            {
                Misc::httpHeader("400 Bad Request - Wrong parameters");
            }
        }
    }
    else
    {
        Misc::httpHeader("401 Authorization Required");
    }
}
else
{
    Misc::httpHeader("401 Authorization Required");
}