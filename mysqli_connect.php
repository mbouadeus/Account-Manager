<?php
/**
 * Created by PhpStorm.
 * User: smbouadeu
 * Date: 3/3/19
 * Time: 2:42 PM
 */

$dbc = mysqli_connect('localhost', 'siteuser', 'happyme9')
    OR die('Could not connect to MySQL: '.mysqli_connect_error());
mysqli_select_db($dbc, 'account_manager');

mysqli_set_charset($dbc, 'utf8');