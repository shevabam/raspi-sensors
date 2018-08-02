<?php
require 'assets/_checker.php';

if (User::isAuthenticated())
{
    $_SESSION = array();
    session_destroy();
}

Misc::redirect('index.php');