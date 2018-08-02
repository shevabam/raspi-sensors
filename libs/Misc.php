<?php

class Misc
{
    /**
     * Generates navigation menu
     * 
     * @param string $active Name of the active item (key)
     * 
     * @return string Returns <nav>
     */
    public static function makeNav($active = null)
    {
        $items = array(
            'home' => array(
                'title' => 'Home',
                'url'   => 'index.php',
            ),
            'sensors' => array(
                'title' => 'Sensors',
                'url'   => 'sensors.php',
            ),
            'groups' => array(
                'title' => 'Groups',
                'url'   => 'groups.php',
            ),
        );

        $r = '';

        // If user is logged in, show menu
        if (User::isAuthenticated())
        {
            $r .= '
            <nav class="pure-menu pure-menu-horizontal">
                <ul class="pure-menu-list">';

            foreach ($items as $k => $item)
            {
                $r .= '<li class="pure-menu-item'.($k == $active ? ' pure-menu-selected' : '').'"><a class="pure-menu-link" href="'.$item['url'].'">'.$item['title'].'</a></li>';
            }

            $r .= '
                </ul>
            </nav>';
        }

        return $r;
    }


    /**
     * Redirect to URL
     * 
     * @param string $to URL to redirect
     */
    public static function redirect($to)
    {
        header('Location: '.$to);
        exit;
    }


    /**
     * HTTP Header
     * 
     * @param string $text
     */
    public static function httpHeader($text)
    {
        header('HTTP/1.0 '.$text);
        exit;
    }


    /**
     * Generate a "random" alpha-numeric-special string
     * 
     * Use Passwordizer class to generate more efficient strings
     * 
     * @param  int   $length
     * @param  bool  $specialChars
     * @return string
     */
    public static function randomString($length = 10, $specialChars = false)
    {
        $pool = 'abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        if ($specialChars)
            $pool .= '_?!:@';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}