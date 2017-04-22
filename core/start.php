<?php
defined('_CONSTANT_') or die('Error. You don`t have permision to access.');
session_start();
ob_start();

require_once 'function.php';
require_once inc . 'class/Filter.php';
require_once inc . 'class/Count.php';
require_once inc . 'class/Nav.php';
require_once inc . 'class/User.php';
require_once inc . 'class/Browser.php';

genTimerStart();

$Filter = new Filter;
$Count = new Count;
$User = new User;

/*
 * Authorization;
 */
if (empty($_SESSION['id'])) 
{
    if (!empty($_COOKIE['login']) && !empty($_COOKIE['password'])) 
    {
        $login = $Filter->clearString($_COOKIE['login']);
        $password = $Filter->clearString($_COOKIE['password']);
        $sql = DB::$dbs->queryFetch("SELECT `id` FROM ".USERS." WHERE `login` = ? && `password` = ?", [$login, $password]);
        if ($sql) 
        {
            $_SESSION['id'] = $Filter->clearInt($sql['id']);
        }
    } 
} 
if (isset($_SESSION['id'])) 
{
    $user = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$_SESSION['id']]);

    if (!$user) 
    {
        unset($_SESSION['id']); 
        header("Location: ".HOME."/");
    }
    /*
     * Отсчет времени в онлайне;
     */ 
    if (($user['last_time'] + 1) < time()) 
    {
        if ($user['last_time'] > (time() - 600)) 
        {
            $plusTime = time() - $user['last_time'];
        } 
        else 
        {
            $plusTime = NULL;
        }
        DB::$dbs->query("UPDATE ".USERS." SET `online_time` = ? WHERE `id` = ?", [($user['online_time'] + $plusTime), $user['id']]);
    }
    /*
     * Browser/ip;
     */ 
    $browser = new Browser();
    $userAgent = $browser->getBrowser() . ' (Версия: ' .  $browser->getVersion() . ')';
    $ip = getIP();
    DB::$dbs->query("UPDATE ".USERS." SET `browser` = ?, `ip` = ?, `last_time` = ? WHERE `id` = ?", [$userAgent, $ip, time(), $_SESSION['id']]);
    /*____________*/
    
    /*
     * Проверка на бан;
     */
    $ban = DB::$dbs->queryFetch("SELECT * FROM ".BAN." WHERE `user_id` = ? && `time_ban` > ?", array($user['id'], time()));
    
    if ($ban != NULL) 
    {
        head('Вы заблокированы!');
        
        echo DIV_ERROR;
        
        echo 'Вы заблокированы!<br/> Причина: ' . $Filter->output($ban['text']) . ' <br/>Дата окончания: ' . dataTime($Filter->clearInt($ban['time_ban'])) . '<br/> Заблокировал: ' . userLink($ban['moderator_id']);
        
        echo CLOSE_DIV;
        
        require_once inc . 'core/foot.php';
        exit();
    }
    
}
/*
 * Switch;
 */ 
if (isset($_GET['do'])) 
{
    $do = $Filter->clearFullSpecialChars($_GET['do']); 
} 
else 
{
    $do = NULL;
} 
/*
 * If authorization user;
 */ 
if (!empty($_SESSION['id'])) 
{ 
    
}