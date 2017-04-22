<?php
/*
 * Php include;
 */
defined('_CONSTANT_') or die('Error. You don`t have permision to access.');
/*
 * Settings php;
 */
ini_set('php_flag display_errors', 'On');     // Show errors;
ini_set('display_errors', 'On');              // Show errors;
ini_set('register_globals', FALSE);           // Register globals; - recomend FALSE;
/*
 * Site constants;
 */
define('inc', $_SERVER['DOCUMENT_ROOT'].'/'); // Home directory;
define("HOME", "");                           // Domain;
/*
 * Connect to database;
 */
define('DBHOST', 'localhost');                // Host;
define('DBPORT', '3306');                     // Port;
define('DBNAME', 'game');                     // Name;
define('DBUSER', 'root');                     // User;
define('DBPASS', '');                         // Password;
require_once ''.inc.'/class/Pdo.php';         // Require pdo class;
/*
 * Database tables;
 */
define("USERS", "`users`");
define("SETTINGS", "`settings`");
define("MAIL", "`mail`");
define("MAIL_KONT", "`mail_kont`");
define("CHAT", "`chat`");
define("BAN", "`ban`");
define("BLACKLIST", "`blacklist`");
/*
 * Div css;
 */
define("DIV_BLOCK", '<div class="block">');
define("DIV_ERROR", '<div class="error">');
define("DIV_SUCCESS", '<div class="success">'); 
define("DIV_USPANEL", '<div class="user-panel">'); 
define("DIV_TITLE", '<div class="title">'); 
define("DIV_CONTENT", '<div class="content">'); 
define("DIV_LINK_TOUCH", '<div class="link-touch">');
/*___________________________________________*/
define("CLOSE_DIV", '</div>');
/*
 * Site settings;
 */
$settings = DB::$dbs->queryFetch("SELECT * FROM ".SETTINGS." WHERE `id` = ?", [1]);
/*
 * Functions;
 */
/*________________ Check Auth() _________________*/
function checkAuth () 
{
    if (empty($_SESSION['id'])) 
    {
        header("Location: ".HOME."/");
    } 
    return FALSE;
}
/*________________ Check In()   _________________*/ 
function checkIn () 
{
    if (!empty($_SESSION['id'])) 
    {
        header("Location: ".HOME."/game/");
    } 
    return FALSE;
} 
/*________________ head()       _________________*/ 
function head ($title = NULL, $whereUser = NULL) 
{ 
    global $settings;
    global $user; 
    global $Filter;
    
    /*
     * title;
     */
    if (empty($title)) 
    {
        $title = $settings['site_name'];
    } 
    
    $designUser = (!empty($user) ? $Filter->output($user['design']) : 'touch'); // Design;
    ?> 
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"><head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon.png">
<link rel="shortcut icon" href="img/apple-touch-icon.png">
<link rel="stylesheet" type="text/css" href="<?=HOME?>/style/<?=$designUser?>.css">
<title><?=$Filter->output($title)?></title></head><body>
    <?=DIV_BLOCK?>
<?php 
if ($user) 
{ 
    global $Filter, $User;
    userPanel();
    $User->checkMail();
    title($title);
    
    /*
     * Update where_link;
     */ 
    DB::$dbs->query("UPDATE ".USERS." SET `where_link` = ? WHERE `id` = ?", [$_SERVER['REQUEST_URI'], $user['id']]);
}

if (!empty($user) && $whereUser != NULL) 
{  
    $whereUser = $Filter->clearString($whereUser);
    DB::$dbs->query("UPDATE ".USERS." SET `where` = ? WHERE `id` = ? ",array($whereUser, $_SESSION['id']));
} 
elseif (!empty ($user) && $whereUser == NULL) 
{
    DB::$dbs->query("UPDATE ".USERS." SET `where` = ? WHERE `id` = ? ",array('Неизвестно', $_SESSION['id']));
}

} 
/*________________ error()           _________________*/ 
function error ($string, $linkUrl = NULL, $linkName = NULL) 
{
    echo DIV_ERROR . '<b>'.$string.'</b><br/>';
    if (!empty($linkUrl)) 
    {
        echo '<br/><a href="'.HOME. $linkUrl.'" class="big-but">'.(!empty($linkName) ? $linkName : 'Перейти по ссылке').'</a><br/>';
    } 
    echo CLOSE_DIV;
    include_once ''.inc.'/core/foot.php';
    exit();
} 
/*________________ error no exit()   _________________*/ 
function errorNoExit ($string) 
{
    echo DIV_ERROR . '<b>'.$string.'</b><br/>' . CLOSE_DIV;
} 
/*________________ success()         _________________*/ 
function success ($string, $linkUrl = NULL, $linkName = NULL) 
{
    echo DIV_SUCCESS . '<b>'.$string.'</b><br/>';
    if (!empty($linkUrl)) 
    {
        echo '<br/><a href="'.HOME. $linkUrl.'" class="big-but">'.(!empty($linkName) ? $linkName : 'Перейти по ссылке').'</a><br/>';
    }
    echo CLOSE_DIV;
    include_once ''.inc.'/core/foot.php';
    exit();
} 
/*________________ success no exit() _________________*/ 
function successNoExit ($string) 
{
    echo DIV_SUCCESS . '<b>'.$string.'</b><br/>' . CLOSE_DIV;
} 
/*________________ money icon() _________________*/ 
function moneyIcon () 
{ 
    global $Filter;
    global $settings;
    $icon = '<img src="'.HOME.'/ico/'.$Filter->output($settings['money_img']).'" width="12px" height="12px" alt="'.$Filter->output($settings['money_name']).'" />'; 
    return $icon;
} 
/*________________ money name() _________________*/ 
function moneyName () 
{ 
    global $Filter;
    global $settings;
    $moneyName = $Filter->output($settings['money_name']); 
    return $moneyName;
}
/*________________ user link() _________________*/ 
function userLink ($userId, $hrefClass = NULL, $plusText = null) 
{
    global $Filter, $User;
    
    $userId = $Filter->clearInt($userId);
    
    $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]);
    
    $url = '<a href="'.HOME.'/modules/user/index.php?userId='.$profile['id'].'" '.(!empty($hrefClass) ? 'class="'.$hrefClass.'"' : NULL).'>'.$Filter->output($profile['login']) . ' ' . $User->userAccess($profile['id'], 1).' '.(!empty($plusText) ? $Filter->output($plusText) : NULL).'</a>';
    
    return $url;
} 


/*
 * Вывод времени;
 */
function dataTime ($time = null) 
{
    if ($time == NULL)$time = time();
    $timep="".date("j M yг. в H:i", $time)."";
    $time_p[0]=date("j n Y", $time);
    $time_p[1]=date("H:i", $time);

    if ($time_p[0] == date("j n Y"))$timep = date("H:i:s", $time);
    if ($time_p[0] == date("j n Y", time()-60*60*24))$timep = "Вчера в $time_p[1]";

    $timep=str_replace("Jan","Янв",$timep);
    $timep=str_replace("Feb","Фев",$timep);
    $timep=str_replace("Mar","Мар",$timep);
    $timep=str_replace("May","Мая",$timep);
    $timep=str_replace("Apr","Апр",$timep);
    $timep=str_replace("Jun","Июня",$timep);
    $timep=str_replace("Jul","Июля",$timep);
    $timep=str_replace("Aug","Авг",$timep);
    $timep=str_replace("Sep","Сент",$timep);
    $timep=str_replace("Oct","Окт",$timep);
    $timep=str_replace("Nov","Нояб",$timep);
    $timep=str_replace("Dec","Дек",$timep);
    return $timep;
}

/*
 * Отсчет времени;
 */
function countTime ($count) 
{
    $d                   = 3600 * 24; 
    $day                 = floor($count / $d);
    $count               = $count - ($d * $day);
    
    $hour                = floor($count / 3600);
    $count               = $count - (3600 * $hour);
    
    $minute              = floor($count / 60);
    $count               = $count - (60 * $minute);
    
    $second              = floor($count);
    
    $dayt                = "" . ($day > 0 ? "$day д. " : null) . "";
    $hourt               = "" . ($hour > 0 ? "$hour ч. " : null) . "";
    $minutet             = "" . ($minute > 0 ? "$minute м. " : null) . "";
    $secondt             = "" . ($second > 0 ? "$second с. " : null) . "";
    
    if ($day > 0) 
    {
        $minutet         = NULL;
	$secondt         = NULL;
    } 
    
    if ($hour > 0 && $day == 0) 
    {
        $secondt         = NULL;
	$dayt            = NULL;
    }
    
    return "$dayt$hourt$minutet$secondt";
}
/*________________ output text() _________________*/ 
function outputText($string, $userId) 
{
    global $Filter;
    
    $string = $Filter->output($string);
    $string = nl2br($string);
    $string = specialTags($string, $userId);
    
    return $string;
}
/*________________ navigation panel() _________________*/ 
/*
 * Description; 
 * Панель навигации;
 * Используется для быстрого вывода ссылок навигации;
 * navigationUrl() - Используется;
 * Пример использования: $array = ['Почта']; navPanel($array);;
 */
function navPanel ($array) 
{ 
    echo '<div class="border-green"><div class="navigation-head-green">Панель навигации</div>';
    if (empty($array)) 
    {
        echo '<table style="width:100%" cellspacing="0" cellpadding="0"><tr>
        <td style="vertical-align:top;width:20%;white-space: nowrap;"><a href="'.HOME.'/" class="link-touch">Главная</a> </td> 
        </tr></table>';
    } 
    else 
    {
        echo '<table style="width:100%" cellspacing="0" cellpadding="0"><tr>
        <td style="vertical-align:top;width:20%;white-space: nowrap;"><a href="'.HOME.'/" class="link-touch">Главная</a> </td>'; 
        foreach ($array as $value) 
        {
            echo '<td style="vertical-align:top;width:20%;white-space: nowrap;">'.navURL($value).'</td>';
        } 
        echo '</tr></table>'; 
    } 
    echo '</div><br/>';
} 
/*________________ navigation Url() _________________*/ 
function navUrl ($value) 
{
    $string = str_replace('Панель Управления', '<a href="'.HOME.'/modules/administrative/index.php" class="link-touch"/>' . $value . '</a>', $value); 
    $string = str_replace('Почта', '<a href="'.HOME.'/modules/mail/index.php" class="link-touch"/>' . $value . '</a>', $string); 
    $string = str_replace('Рейтинг игроков', '<a href="'.HOME.'/modules/rating/index.php" class="link-touch"/>' . $value . '</a>', $string); 
    $string = str_replace('Настройки', '<a href="'.HOME.'/modules/user/settings/index.php" class="link-touch"/>' . $value . '</a>', $string);
    return $string;
} 
/*________________ special Tags() _________________*/ 
function specialTags ($string, $userId) 
{
    global $Filter;

    $userId = $Filter->clearInt($userId);
    
    $ank = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]);
    
    if ($ank['access'] == 2) 
    {
        $return = str_replace('@mail@', '[<a href="'.HOME.'/modules/mail/index.php">Mail</a>]', $string);
        $return = str_replace('@test@', '[<a href="'.HOME.'/modules/mail/index.php">test</a>]', $return);
        
        return $return;
    } 
    else 
    {
        return $string;
    }
}
/*________________ user Panel() _________________*/ 
function userPanel ($type = NULL) 
{
    if ($type == null) 
    {
        ?>  
    <?=DIV_USPANEL?>10<span style="float: right;">10</span><?=CLOSE_DIV?><div class="line"></div>
    <?php
    } 
    else 
    {
        ?> 
    <?php
    }
} 
/*________________ title() _________________*/ 
function title ($title) 
{ 
    global $Filter;
    ?> 
<?=DIV_TITLE.$Filter->output($title).CLOSE_DIV?>
<?php
}

/*
 * Get IP;
 */ 
function getIP () 
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } 
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } 
    else 
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    } 
    
    return $ip;
}

/*
 * Вывод иконки;
 */
function ico ($uri, $alt = NULL, $width = NULL, $height = NULL) 
{
    $ico = '<img src="/ico/'.$uri.'" width="'.($width == null ? '12' : $width).'px" height="'.($height == null ? '12' : $height).'px" alt="'.($alt == null ? 'ico' : $alt).'">';
    return $ico;
}

/*
 * Вычисление времени генерации страницы;
 */ 
function genTimerStart () 
{
    global $timerStartTime; 
    $startTime = microtime();
    $startArray = explode(" ",$startTime);
    $timerStartTime = $startArray[1] + $startArray[0];
    return $timerStartTime;
} 

function genTimerStop () 
{
    global $timerStartTime;
    $endTime = microtime();
    $endArray = explode(" ",$endTime);
    $timerStopTime = $endArray[1] + $endArray[0];
    $time = $timerStopTime - $timerStartTime;
    $time = substr($time,0,5);
    return "$time сек.";
}