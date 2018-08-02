<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require 'assets/_checker.php';

if ($Param->get('force_connect') == true)
    User::checkIfIsAuthenticated();

// Enabled list
$enabled_list = array('all' => 'All sensors', '1' => 'Only enabled sensors', '0' => 'Only disabled sensors');

// Filter group
$group_id = 1;
if (isset($_GET['group']) && (int)$_GET['group'] > 0)
    $group_id = (int)$_GET['group'];

// Filter enabled
$enabled = 1;
if (isset($_GET['enabled']))
    $enabled = $_GET['enabled'];

// Get all sensors for this group
$getSensors = $Sensor->getAllSensorsByGroup($group_id, $enabled);

// -------

require 'assets/header.php';

echo Misc::makeNav('home');
?>

<div class="main-container" id="home">

    <?php if (count($SensorGroup->getAllGroups()) == 0): ?>
        <div class="pure-message pure-message-info">No group found, <a href="groups.php">add group</a>!</div>
    <?php else: ?>
        <div class="pure-g">
            <div class="pure-u-1-1">
                <form action="index.php" class="pure-form">
                    <select name="group" id="group" class="group_filter">
                        <?php foreach ($SensorGroup->getAllGroups() as $group): ?>
                            <?php $selected = $group['id'] == $group_id ? ' selected="selected"' : ''; ?>
                            <option value="<?= $group['id']; ?>"<?= $selected; ?>><?= $group['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (User::isAuthenticated()): ?>
                        <select name="enabled" id="enabled" class="enabled_filter">
                            <?php foreach ($enabled_list as $k => $v): ?>
                                <option value="<?= $k; ?>"<?= (string)$k == $enabled ? ' selected="selected"' : ''; ?>><?= $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <input type="submit" value="Apply" class="pure-button pure-button-success">
                </form>
            </div>
        </div>
    <?php endif; ?>


    <?php if (count($getSensors) == 0): ?>

        <div class="pure-message pure-message-info">No sensor found, <a href="sensors.php">add sensor</a>!</div>

    <?php else: ?>

        <div class="gauges">
            <?php
            foreach ($getSensors as $sensor)
            {
                // Get last temperature for this sensor
                $last_temp = $Temperature->getLastBySensorDevice($sensor['device']);

                $temp = '?';
                $date = '-';
                if ($last_temp)
                {
                    $temp = $last_temp['value'];

                    $dt = new \Datetime($last_temp['date']);
                    $date = strftime("%a %d %b %H:%M", $dt->getTimestamp());                
                }
            ?>
                <div class="gauge" style="box-shadow: 0 1px 14px <?= $sensor['color']; ?>, inset 0px 2px 3px #fff;">
                    <span class="name"><?= $sensor['name']; ?></span>
                    <span class="temp"><?= $temp; ?>°C</span>
                    <span class="date"><?= $date; ?></span>
                </div>
            <?php
            }
            ?>
        </div>


        <div id="chart"></div>
        <?php
        $array_graphs = array();
        foreach ($getSensors as $sensor)
        {
            $array_graphs[] = array(
                'id' => 'g'.$sensor['id'],
                'balloon' => array(
                    'adjustBorderColor' => false,
                    'color' => '#ffffff',
                ),
                'lineColor' => $sensor['color'],
                'bullet' => 'round',
                'bulletBorderAlpha' => 1,
                'bulletColor' => '#ffffff',
                'bulletSize' => 7,
                'hideBulletsCount' => 50,
                'lineThickness' => 2,
                'title' => $sensor['name'],
                'useLineColorForBulletBorder' => true,
                'valueField' => 'sensor_'.$sensor['id'],
                'balloonText' => "<span style='font-size:18px;'>[[value]]°C</span>",
            );
        }
        ?>
        <script>
        var chart = AmCharts.makeChart("chart", {
            "type": "serial",
            "dataDateFormat": "YYYY-MM-DD HH-NN",
            "pathToImages": "assets/images/amcharts/",
            "valueAxes": [{
                "id": "v1",
                "title": "Temperatures",
                "axisAlpha": 0,
                "position": "left",
                "ignoreAxisWidth":true
            }],
            "graphs": <?= json_encode($array_graphs); ?>,
            "legend": {
                "useGraphSettings": true
            },
            "chartScrollbar": {
                "graph": "g1",
                "oppositeAxis":false,
                "offset":90,
                "scrollbarHeight": 80,
                "backgroundAlpha": 0,
                "selectedBackgroundAlpha": 0.2,
                "selectedBackgroundColor": "#888888",
                "graphFillAlpha": 0,
                "graphLineAlpha": 0.5,
                "selectedGraphFillAlpha": 0,
                "selectedGraphLineAlpha": 1,
                "autoGridCount":false,
                "color":"#AAAAAA"
            },
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha":1,
                "cursorColor":"#BC1142",
                "limitToGraph":"g1",
                "valueLineAlpha":0.2
            },
            "valueScrollbar":{
              "oppositeAxis":false,
              "offset":60,
              "scrollbarHeight":8
            },
            "categoryField": "date",
            "categoryAxis": {
                "dashLength": 1,
                "minorGridEnabled": true,
                "labelRotation": 45
            },
            "dataLoader": {
                "url": "generateChartDatas.php?action=getDatas&group=<?= $group_id; ?>",
                "format": "json"
            }
        } );

        chart.addListener("init", zoomChart);
        function zoomChart(){
            $.getJSON('generateChartDatas.php?action=zoomDatas&group=<?= $group_id; ?>', function(datas){
                chart.zoomToCategoryValues(datas.first.date, datas.last.date);
            });
        }
        </script>

    <?php endif; ?>


</div>


<?php require 'assets/footer.php'; ?>