<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkAuth();
head('Профиль', 'Профиль');

$userId = (!empty($_GET['userId']) ? $Filter->clearInt($_GET['userId']) : NULL); 

#$UserInfo = $User->userInfo($userId);

if ($userId == $user['id']) 
{
    ?> 
<?=DIV_CONTENT?>
Ваш логин: <?=$User->getQuery('userLogin', $userId)?><br/> 
<?=moneyName()?>: <?=moneyIcon().$User->getQuery('userMoney', $userId)?><br/> 
<a href="<?=HOME?>/modules/user/settings/index.php" class="link-touch"> Настройки</a> 
<a href="<?=HOME?>/modules/mail/index.php" class="link-touch"> Почта</a>
<?=DIV_TITLE?> 
Параметры
<?=CLOSE_DIV?>
<table style="width: 355px;border: 1px solid #284440;">
        <thead>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #284440;">Сила</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userStrike', $userId)?></font></td>
            </tr>
            <tr>
                <td style="border: 1px solid #284440;">Защита</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userDefend', $userId)?></font></td>
            </tr>
            <tr>
                <td style="border: 1px solid #284440;">Здоровье</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userHealth', $userId)?></font></td>
            </tr>
        </tbody>
    </table>
<?=CLOSE_DIV?>
<?php
/*
 * Проверка на бан;
 */
$ban = DB::$dbs->queryFetch("SELECT * FROM ".BAN." WHERE `user_id` = ? && `time_ban` > ?", array($userId, time()));

if ($ban != NULL) 
{
    echo DIV_ERROR;
        
    echo 'Вы заблокированы!<br/> Причина: ' . $Filter->output($ban['text']) . ' <br/>Дата окончания: ' . dataTime($Filter->clearInt($ban['time_ban'])) . '<br/> Заблокировал: ' . userLink($ban['moderator_id']);
        
    echo CLOSE_DIV;
}

} 
elseif ($userId != $user['id'] && $userId != null) 
{
    $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]); 
    if ($profile) 
    { 
        ?> 
<?=DIV_CONTENT?>
Логин: <?=$User->getQuery('userLogin', $userId)?><br/>
<?=moneyName()?>: <?=moneyIcon().$User->getQuery('userMoney', $userId)?><br/>
<a href="<?=HOME?>/modules/mail/index.php?userId=<?=$Filter->clearInt($userId)?>" class="link-touch"> Написать сообщение</a>
<?=DIV_TITLE?> 
Параметры
<?=CLOSE_DIV?>
<table style="width: 355px;border: 1px solid #284440;">
        <thead>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #284440;">Сила</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userStrike', $userId)?></font></td>
            </tr>
            <tr>
                <td style="border: 1px solid #284440;">Защита</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userDefend', $userId)?></font></td>
            </tr>
            <tr>
                <td style="border: 1px solid #284440;">Здоровье</td>
                <td style="border: 1px solid #284440;"><font color="green"><?=$User->getQuery('userHealth', $userId)?></font></td>
            </tr>
        </tbody>
    </table>
<?=CLOSE_DIV?>

<?php 
/*
 * Проверка на бан;
 */ 
$ban = DB::$dbs->queryFetch("SELECT * FROM ".BAN." WHERE `user_id` = ? && `time_ban` > ?", array($profile['id'], time()));

if ($ban != NULL) 
{
    echo DIV_ERROR;
        
    echo 'Пользователь заблокирован!<br/> Причина: ' . $Filter->output($ban['text']) . ' <br/>Дата окончания: ' . dataTime($Filter->clearInt($ban['time_ban'])) . '<br/> Заблокировал: ' . userLink($ban['moderator_id']);
        
    echo CLOSE_DIV;
}

if ($user['access'] == 2) 
{
    ?>
<?=DIV_TITLE?>
Для администратора
<?=CLOSE_DIV?> 
<?=DIV_CONTENT?> 
Сейчас находится: <?=$Filter->output($profile['where'])?><br/><hr>
Точное местонахождение: <?=$Filter->output($profile['where_link'])?><br/><hr>
IP: <?=$profile['ip']?><br/><hr>
Браузер: <?=$Filter->output($profile['browser'])?><br/><hr> 
Последнее действие: <?= dataTime($Filter->clearInt($profile['last_time']))?><br/><hr> 
Провел на сайте: <?= countTime($Filter->clearInt($profile['online_time']))?><br/><hr> 
Баны: (<a href="<?=HOME?>/modules/administrative/log.php?do=banUser&amp;userId=<?=$Filter->clearInt($profile['id'])?>"><?=$Count->userCountBan($profile['id'])?></a>)<br/><hr>
<a href="<?=HOME?>/modules/administrative/index.php?do=editUser&amp;userId=<?=$Filter->clearInt($profile['id'])?>" class="link-touch"> Редактировать игрока</a>
<a href="<?=HOME?>/modules/administrative/index.php?do=banedUser&amp;userId=<?=$Filter->clearInt($profile['id'])?>" class="link-touch"> Заблокировать игрока</a>
<a href="<?=HOME?>/modules/administrative/index.php?do=deleteUser&amp;userId=<?=$Filter->clearInt($profile['id'])?>" class="link-touch"> Удалить игрока</a>
<?=CLOSE_DIV?>

<?php
}
    } 
    else 
    {
        errorNoExit('Пользователь не найден!');
    }
}  
else 
{
    errorNoExit('Пользователь не найден!');
}
require_once '../../core/foot.php';