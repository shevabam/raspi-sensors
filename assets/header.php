<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $Config->get('app:name'); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="32x32">

    <script src="assets/js/jquery-2.1.3.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/jquery.gNotifier2.js"></script>
    <script src="assets/js/jquery.passwordstrength.js"></script>

    <script src="assets/js/amcharts/amcharts.js"></script>
    <script src="assets/js/amcharts/amcharts_serial.js"></script>
    <script src="assets/js/amcharts/plugins/dataloader/dataloader.min.js"></script>

    <script src="assets/js/app.js"></script>

    <link rel="stylesheet" href="assets/css/pure.css">
    <link rel="stylesheet" href="assets/css/pure-extended.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/jquery.gNotifier2.css">
    <link rel="stylesheet" href="assets/css/jquery.passwordstrength.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


<header>
    <h1><a href="index.php"><?= $Config->get('app:name'); ?></a></h1>

    <?php if (User::isAuthenticated()): ?>
        <ul>
            <li><a href="manage.php" class="popup pure-button pure-button-brown pure-button-small">Manage</a></li>
            <li><a href="logout.php" class="pure-button pure-button-brown pure-button-small">Logout</a></li>
        </ul>
    <?php else: ?>
        <ul>
            <li><a href="signin.php" class="pure-button pure-button-brown pure-button-small">Sign in</a></li>
        </ul>
    <?php endif; ?>
</header>

<div class="cls"></div>

