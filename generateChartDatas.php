<?php
require 'assets/_checker.php';

// Filter group
$group_id = 1;
if (isset($_GET['group']) && (int)$_GET['group'] > 0)
    $group_id = (int)$_GET['group'];

$action = '';
if (isset($_GET['action']) && !empty($_GET['action']))
    $action = $_GET['action'];


switch ($action)
{
    // Temperatures for chart
    case 'getDatas':

        $Cache->setDir('datas/')->setFile('cache.datas.json');

        if (!$Cache->check($Param->get('cache_expires')))
        {
            $Cache->start();

            $datas_chart = array();

            $temp_dates_tmp = $Temperature->getAllDatesByGroupId($group_id, array('order_type' => 'DESC', 'limit' => $Param->get('graph_max_entries')));

            $temp_dates = array_reverse($temp_dates_tmp);

            $i = 0;
            foreach ($temp_dates as $temp_date)
            {
                $temperatures = $Temperature->getAllByDate($temp_date['date_group']);

                $datas_chart[$i]['date'] = substr($temperatures[0]['date'], 0, -3);

                foreach ($temperatures as $temp)
                {
                    $datas_chart[$i]['sensor_'.$temp['sensor_id']] = $temp['value'];
                }

                $i++;
            }

            $datas_json = json_encode($datas_chart);
            echo $datas_json;

            $Cache->end();
        }
        else
        {
            echo $Cache->get();
        }

    break;

    // Zoom
    case 'zoomDatas':

        $Cache->setDir('datas/')->setFile('cache.zoom.json');

        if (!$Cache->check($Param->get('cache_expires')))
        {
            $Cache->start();

            $datas_chart = array();

            $temp_dates = $Temperature->getAllDatesByGroupId($group_id, array('order_type' => 'DESC', 'limit' => $Param->get('graph_zoom_entries')));

            $i = 0;
            foreach ($temp_dates as $temp_date)
            {
                $temperatures = $Temperature->getAllByDate($temp_date['date_group']);

                $datas_chart[$i]['date'] = substr($temperatures[0]['date'], 0, -3);

                foreach ($temperatures as $temp)
                {
                    $datas_chart[$i]['sensor_'.$temp['sensor_id']] = $temp['value'];
                }

                $i++;
            }

            $datas_zoom = array(
                'last'  => $datas_chart[0],
                'first' => end($datas_chart),
            );

            $datas_json = json_encode($datas_zoom);
            echo $datas_json;

            $Cache->end();
        }
        else
        {
            echo $Cache->get();
        }
        
    break;
}