<?php
set_time_limit(600);

require 'autoload.php';

session_start();

$Config = new Config();

function getRandomTemp()
{
    $random = rand(-10, 35);
    $temp = $random.'.'.rand(0, 9);

    return $temp;
}

// ------- ------- ------- ------- ------- ------- ------- \\


$is_installed  = false;
$notifications = array();

// If already installed
if ($Config->get('passwd') != '' && $Config->get('db') != '' && file_exists($Config->get('db')))
{
    $is_installed = true;
}


// Form submit
if (isset($_POST['submit']))
{
    if (isset($_POST['passwd'], $_POST['passwd2']) && !empty($_POST['passwd']) && !empty($_POST['passwd2']))
    {
        $passwd   = trim($_POST['passwd']);
        $passwd2  = trim($_POST['passwd2']);
        $fixtures = isset($_POST['fixtures']) && strtolower($_POST['fixtures']) == 'on' ? true : false;

        if ($passwd != $passwd2)
        {
            $notifications[] = array(
                'type'      => 'error',
                'content'   => 'Passwords are not equal',
            );
        }
        elseif (mb_strlen($passwd) <= 8 || !preg_match('/\d+/', $passwd))
        {
            $notifications[] = array(
                'type'      => 'error',
                'content'   => 'Password must have at least 8 characters and 1 number',
            );
        }
        else
        {
            // Set password
            if ($Config->get('passwd') == '')
            {
                $Config->setPassword($passwd);
            }

            if ($Config->get('db') == '' || !file_exists($Config->get('db')))
            {
                // Set DB name
                $Config = new Config();
                $Config->setDbName();

                $Config = new Config();
                $db = new Database($Config->get('db'));

                // Enable foreign keys
                $db->query("PRAGMA foreign_keys = ON;");
                $db->execute();


                $install_fixtures = $fixtures;

                // Create tables
                if (!file_exists('datas/db_files/tables.sql'))
                {
                    $notifications[] = array(
                        'type'      => 'error',
                        'content'   => 'SQL file for creating tables does not exist!',
                    );

                    $install_fixtures = false;
                }
                else
                {
                    $sql = file_get_contents('datas/db_files/tables.sql');

                    $db->exec($sql);
                }

                // Insert parameters datas
                if (count($notifications) == 0)
                {
                    if (!file_exists('datas/db_files/parameters.sql'))
                    {
                        $notifications[] = array(
                            'type'      => 'error',
                            'content'   => 'SQL file for inserting parameters does not exist!',
                        );
                    }
                    else
                    {
                        $sql = file_get_contents('datas/db_files/parameters.sql');

                        $db->exec($sql);

                        // Changing api_key value
                        $api_key = Misc::randomString(50);
                        $Param = new Param($Config->get('db'));
                        $Param->update('api_key', $api_key);
                    }
                }

                // Insert fixtures
                if (count($notifications) == 0 && $install_fixtures)
                {
                    if (!file_exists('datas/db_files/fixtures.sql'))
                    {
                        $notifications[] = array(
                            'type'      => 'error',
                            'content'   => 'SQL file for creating fixtures does not exist!',
                        );
                    }
                    else
                    {
                        $sql = file_get_contents('datas/db_files/fixtures.sql');

                        $db->exec($sql);

                        // Insert some fixtures for table temperature
                        $nb = 100;
                        $date = '2018-07-01 14:00:00';

                        for ($i = 0; $i < $nb; $i++)
                        {
                            $dt = new Datetime($date);
                            $dt->add(new DateInterval('PT1H'));

                            $date = $dt->format('Y-m-d H:i:s');

                            $db->query("
                            INSERT INTO temperature(sensor_id, value, date, created_at) VALUES
                            (1, ".getRandomTemp().", '".$date."', '".$date."'),
                            (2, ".getRandomTemp().", '".$date."', '".$date."'),
                            (3, ".getRandomTemp().", '".$date."', '".$date."');
                            ");
                            $db->execute();
                        }
                    }
                }
            }

            $is_installed = true;
        }
    }
    else
    {
        $notifications[] = array(
            'type'      => 'error',
            'content'   => 'Please fill the passwords fields !',
        );
    }
}


if ($is_installed)
{
    Misc::redirect('index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $Config->get('app:name'); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="32x32"> 
    <link rel="stylesheet" href="assets/css/pure.css">
    <link rel="stylesheet" href="assets/css/pure-extended.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/jquery.passwordstrength.css">

    <script src="assets/js/jquery-2.1.3.min.js"></script>
    <script src="assets/js/jquery.passwordstrength.js"></script>
    <script>
    $(document).ready(function(){
        $('input#passwd').passwordstrength({
            'minlength': 8,
            'number'   : true,
            'capital'  : false,
            'special'  : false
        });
    });
    </script>
</head>
<body>


<div class="container">

    <div class="login-form">

        <h1><?= $Config->get('app:name'); ?></h1>
        
        <form action="install.php" method="post" class="pure-form install-form">
            <h2>Installation</h2>

            <div class="form-row">
                <input type="password" name="passwd" id="passwd" placeholder="Choose a strong password" required="required" autofocus>
            </div>

            <div class="form-row">
                <input type="password" name="passwd2" id="passwd2" placeholder="Retype your password" required="required">
            </div>

            <div class="form-row">
                <label for="fixtures" class="pure-checkbox">
                    <input type="checkbox" name="fixtures" id="fixtures" checked="checked"> Install examples? <span class="mention">(may take few more seconds)</span>
                </label>
            </div>

            <div class="form-row">
                <input type="submit" name="submit" class="pure-button pure-button-success" value="Start install">
            </div>
        </form>


        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="pure-message pure-message-<?= $notification['type']; ?>"><?= $notification['content']; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="footer pure-g">
        <div class="pure-u-1-2">
            <a href="<?= $Config->get('app:website'); ?>"><?= $Config->get('app:name'); ?> v<?= $Config->get('app:version'); ?></a>
        </div>

        <div class="pure-u-1-2 links">
            <li><a href="<?= $Config->get('app:github'); ?>">GitHub</a></li>
            
            <li><a href="https://twitter.com/<?= $Config->get('app:twitter'); ?>">Twitter</a></li>
            
            <li><a href="<?= $Config->get('app:author_www'); ?>">ShevArezo.fr</a></li>
        </div>
    </div>

</div>

</body>
</html>