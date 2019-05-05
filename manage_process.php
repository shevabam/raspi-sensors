<?php
require 'assets/_checker.php';
User::checkIfIsAuthenticated();

$action = $_GET['action'];

switch ($action)
{
    // -- Edit
    case 'edit':

        $result = array('errors' => '', 'datas' => array());

        $datas = array();

        // Updates list of parameters, stored in DB
        foreach ($_POST['param'] as $key => $value)
        {
            $getParam = $Param->get($key);

            if ($getParam !== false)
            {
                $value = trim($value);
                
                $Param->update($key, $value);
            }
        }

        // Update password, if wanted
        if (isset($_POST['passwd_chk']) && $_POST['passwd_chk'] == 'on')
        {
            if (isset($_POST['passwd']) && !empty($_POST['passwd']))
            {
                $passwd = trim($_POST['passwd']);

                if (mb_strlen($passwd) <= 8 || !preg_match('/\d+/', $passwd))
                    $result['errors'] = 'Password must have at least 8 characters and 1 number';
                
                if ($result['errors'] == '')
                {
                    // Change password in config file
                    $Config->setPassword($passwd);

                    // Logout
                    $_SESSION = array();
                    session_destroy();
                }
            }
            else
            {
                $result['errors'] = 'Please fill a new password';
            }
        }


        echo json_encode($result);

    break;
}