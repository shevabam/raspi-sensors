<?php

class User
{
    /**
     * Checks if the user is authenticated (session)
     * 
     * @return bool
     */
    public static function isAuthenticated()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
        {
            return true;
        }

        return false;
    }

    /**
     * Checks if the user is authenticated, else redirect to home
     * 
     * @return bool
     */
    public static function checkIfIsAuthenticated()
    {
        if (!self::isAuthenticated())
        {
            header('Location: signin.php');
            exit;
        }

        return true;
    }

    /**
     * Hash the password given with sha256 protocol
     * 
     * @param string $passwd Password to hash
     * @return string Password hashed
     */
    public static function hashPassword($passwd)
    {
        return hash('sha256', $passwd);
    }
}