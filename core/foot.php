<?php
defined('_CONSTANT_') or die('Error. You don`t have permision to access.');

global $user, $Count, $Filter;

/*
 * Обработка запроса на изменение дизайна;
 */
if (isset($_GET['setDesign'])) 
{
    $User->setDesign();
}

echo '<hr><div class="line"></div>';

if ($_SERVER['PHP_SELF'] != '/index.php' && $_SERVER['PHP_SELF'] != '/game/index.php' && $_SERVER['PHP_SELF'] != '/game/') 
{
    ?> 
<a href="<?=HOME?>/" class="link-touch"> На главную</a>
<?php
}
if ($user) 
{ 
    if ($user['access'] == 2) 
    {
        ?> 
<a href="<?=HOME?>/modules/administrative/" class="link-touch"> Панель Управления</a> 
<?php
    } 
    ?> 
<a href="<?=HOME?>/modules/user/index.php?userId=<?=$user['id']?>" class="link-touch"> Мой профиль <span class="count"><?=$Filter->clearInt($user['lvl'])?> ур.</span></a>
<hr><div class="line"></div><a href="<?=HOME?>/modules/login/exit.php" class="link-touch">Выход</a>
<a href="?setDesign" class="link-touch">Сменить дизайн</a> 
<?php
}
?>
<hr><div class="line"></div>
<a href="<?=(empty($user) ? '#' : ''.HOME.'/modules/rating/index.php?do=usersOnline')?>" class="link-touch">В игре <span class="count"><?=$Count->userCountOnline()?></span></a>
<a href="#" class="link-touch">Pgen <span class="count"><?=genTimerStop()?></span></a>
<a href="#" class="link-touch">На сервере <span class="count"><?=dataTime()?></span></a>
<br/><font style="font-size: 10px; color: #fff;">&COPY; Game-CMS 2017 by amfetaminf</font>
<?=CLOSE_DIV?>
</body></html>