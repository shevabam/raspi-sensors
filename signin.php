<?php
require 'assets/_checker.php';

$logged_in = false;
if (User::isAuthenticated())
    $logged_in = true;

$notifications = array();

// Login form submitted
if (isset($_POST['submit']))
{
    if (isset($_POST['passwd']) && !empty($_POST['passwd']))
    {
        $passwd = trim($_POST['passwd']);

        // Check if password is correct
        if (User::hashPassword($passwd) == $Config->get('passwd'))
        {
            $logged_in = true;
        }
        else
        {
            $notifications[] = array(
                'type'      => 'error',
                'content'   => 'The password is not correct !',
            );
        }
    }
    else
    {
        $notifications[] = array(
            'type'      => 'error',
            'content'   => 'Please enter a password !',
        );
    }
}

if ($logged_in)
{
    $_SESSION['logged_in'] = true;

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
</head>
<body>


<div class="container">

    <div class="login-form">

        <h1><?= $Config->get('app:name'); ?></h1>
        
        <form action="signin.php" method="post" class="pure-form">
            <div class="form-row">
                <input type="password" name="passwd" id="passwd" placeholder="Password required" required="required" autofocus>
            </div>

            <div class="form-row">
                <input type="submit" name="submit" class="pure-button pure-button-primary" value="Let me in">
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