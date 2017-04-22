<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkAuth();
head('Панель управления');
if ($user['access'] != 2) 
{
    header("Location: ".HOME."/game/");
    exit();
} 
/*
 * Подсказки и т.д.;
 */
$randomArray = ["Для связи с создателем вы можете использовать email: amfetaminf@gmail.com", "Поддержка этой cms будет выполнятся еще долгое время. По этому можете смело использовать ее в разработке своей игры и не переживать об обновлениях.", 
    "Вы можете заказать платные модули для game-cms у автора. Для связи используйте email: amfetaminf@gmail.com"];
shuffle($randomArray);

if ($settings['admin_help'] == 1) 
{
    echo DIV_CONTENT . $randomArray[0] . '<br/>' . ico('0.png') . '<a href="?hide_help">Скрыть</a>' . CLOSE_DIV;
}

if (isset($_GET['hide_help']) && $settings['admin_help'] == 1 && $user['access'] == 2) 
{
    DB::$dbs->query("UPDATE ".SETTINGS." SET `admin_help` = ? WHERE `id` = ?", [0, 1]);
    successNoExit('Вы успешно скрыли подсказки.');
}
/*_________________________________*/
switch ($do) 
{
    default: 
        ?> 
<a href="<?=HOME?>/modules/administrative/index.php?do=editSiteName" class="link-touch"> Изменить название сайта</a>
<a href="<?=HOME?>/modules/administrative/index.php?do=updateRegistration&amp;update" class="link-touch"><?=($settings['registration'] == 0 ? '[открыть]' : '[закрыть]')?> регистрацию</a>
<a href="<?=HOME?>/modules/administrative/index.php?do=editRegistration" class="link-touch"> Настройки регистрации</a>
<a href="<?=HOME?>/modules/administrative/index.php?do=editSiteMoney" class="link-touch"> Настройки валюты</a>
<?=DIV_TITLE?>Пользователи<?=CLOSE_DIV?> 
<?=DIV_CONTENT?>
Зарегистрировано: (<a href="<?=HOME?>/modules/rating/index.php"><?=$Count->userCount()?></a>)<br/>
В игре: (<a href="<?=HOME?>/modules/rating/index.php?do=usersOnline"><?=$Count->userCountOnline()?></a>)<br/>
<?=CLOSE_DIV?>
<a href="<?=HOME?>/modules/administrative/index.php?do=editCostPassword" class="link-touch"> Стоимость смены пароля</a>
<?php 
        break;
        case 'editSiteName': 
            if (isset($_POST['try']) && $user['access'] == 2) 
            {
                $data = $_POST;
                $data['siteName'] = $Filter->clearString($data['siteName']); 
                
                if (empty($data['siteName']) || strlen($data['siteName']) > 20) 
                {
                    errorNoExit('Название должно быть в пределе [1-20].');
                } 
                elseif ($data['siteName'] == $settings['site_name']) 
                {
                    errorNoExit('Такое название уже используется.');
                }
                else 
                {
                    DB::$dbs->query("UPDATE ".SETTINGS." SET `site_name` = ? WHERE `id` = ?", [$data['siteName'], 1]);
                    success('Название сайта успешно сменено.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            ?> 
<form action="" method="POST">
    Название [1-20]<br/>
    <input type="text" name="siteName" maxlength="20" placeholder="Название сайта..." value="<?=$Filter->output($settings['site_name'])?>"><br/> 
    <input type="submit" name="try" value="Изменить">
</form> 
<?php 
$array = ['Панель Управления'];
navPanel($array);
            break; 
        case 'updateRegistration':  
            if (isset($_GET['update']) && $user['access'] == 2) 
            { 
                if ($settings['registration'] == 1) 
                {
                    $registration = 0;
                } 
                else 
                {
                    $registration = 1;
                } 
                DB::$dbs->query("UPDATE ".SETTINGS." SET `registration` = ? WHERE `id` = ?", [$registration, 1]);
                success('Статус регистрации успешно сменен.', '/modules/administrative/index.php', 'Вернуться в панель управления');
            } 
            else 
            {
                header("Location: ".HOME."/");
            }
            break; 
        case 'editRegistration': 
            if (isset($_POST['try']) && $user['access'] == 2) 
            {
                $data = $_POST; 
                $data['money'] = $Filter->clearInt($data['money']);
                
                if (empty($data['money']) || strlen($data['money']) > 11) 
                {
                    errorNoExit('Длина полей должна быть в пределе [1-11]');
                } 
                elseif (!is_numeric($data['money'])) 
                {
                    errorNoExit('Некоторые из полей могут иметь только числовое значение.');
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".SETTINGS." SET `registration_money` = ? WHERE `id` = ?", [$data['money'], 1]);
                    success('Настройки сохранены.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                } 
            }
                ?> 
<form action="" method="POST">
     Основная валюта[2-11(число)]<br/>
    <input type="text" name="money" maxlength="11" value="<?=$Filter->output($settings['registration_money'])?>"><br/>
    <input type="submit" name="try" value="Готово">
</form>  
<?php 
$array = ['Панель Управления'];
navPanel($array);
                break;
        case 'editSiteMoney': 
            if (isset($_POST['try']) && $user['access'] == 2) 
            {
                $data = $_POST;
                $data['moneyName'] = $Filter->clearString($data['moneyName']); 
                $data['moneyImg'] = $Filter->clearString($data['moneyImg']); 
                
                if (empty($data['moneyName']) || empty($data['moneyImg'])) 
                {
                    errorNoExit('Все поля должны быть заполнены.');
                }  
                elseif (strlen($data['moneyName']) > 15 || strlen($data['moneyName']) < 2 || strlen($data['moneyImg']) > 10 || strlen($data['moneyImg']) < 2) 
                {
                    errorNoExit('Длина полей должна быть в пределе [2-10]');
                }
                elseif (!preg_match("#^([A-zА-я0-9\-\_.\ ])+$#ui", $data['moneyName']) || !preg_match("#^([A-zА-я0-9\-\_.\ ])+$#ui", $data['moneyImg'])) 
                {
                    errorNoExit('Найдены недопустимые символы.');
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".SETTINGS." SET `money_name` = ?, `money_img` = ? WHERE `id` = ?", [$data['moneyName'], $data['moneyImg'], 1]);
                    success('Настройки сохранены.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            ?> 
<form action="" method="POST">
    Название основной валюты [2-10]<br/>
    <input type="text" name="moneyName" maxlength="10" value="<?=$Filter->output($settings['money_name'])?>"><br/>
    Изображение основной валюты [2-10] Например: [icon.png]<br/>
    <input type="text" name="moneyImg" maxlength="10" value="<?=$Filter->output($settings['money_img'])?>"><br/> 
    <input type="submit" name="try" value="Готово">
</form> 
<?php 
$array = ['Панель Управления'];
navPanel($array);
            break; 
        case 'editCostPassword': 
            if (isset($_POST['try']) && $user['access'] == 2) 
            {
                $data = $_POST; 
                $data['cost'] = $Filter->clearInt($data['cost']); 
                
                if (empty($data['cost'])) 
                {
                    errorNoExit('Обнаружено пустое поле.');
                } 
                elseif (!is_numeric($data['cost'])) 
                {
                    errorNoExit('Обнаружено неверное значение.');
                } 
                elseif (strlen($data['cost']) > 11) 
                { 
                    errorNoExit('Обнаружено неверное значение.');
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".SETTINGS." SET `password_cost` = ? WHERE `id` = ?", [$data['cost'], 1]);
                    success('Настройки сохранены.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            ?> 
<form action="" method="POST">
    Стоимость[1-11]:<br/> 
    <input type="number" name="cost" placeholder="Введите стоимость" value="<?=$Filter->clearInt($settings['password_cost'])?>"><br/>
    <input type="submit" name="try" value="Сменить">
</form> 
<?php 
$array = ['Панель Управления'];
navPanel($array);
            break; 
            
            case 'editCostLogin': 
            if (isset($_POST['try']) && $user['access'] == 2) 
            {
                $data = $_POST; 
                $data['cost'] = $Filter->clearInt($data['cost']); 
                
                if (empty($data['cost'])) 
                {
                    errorNoExit('Обнаружено пустое поле.');
                } 
                elseif (!is_numeric($data['cost'])) 
                {
                    errorNoExit('Обнаружено неверное значение.');
                } 
                elseif (strlen($data['cost']) > 11) 
                { 
                    errorNoExit('Обнаружено неверное значение.');
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".SETTINGS." SET `login_cost` = ? WHERE `id` = ?", [$data['cost'], 1]);
                    success('Настройки сохранены.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            ?> 
<form action="" method="POST">
    Стоимость[1-11]:<br/> 
    <input type="number" name="cost" placeholder="Введите стоимость" value="<?=$Filter->clearInt($settings['login_cost'])?>"><br/>
    <input type="submit" name="try" value="Сменить">
</form> 
<?php 
$array = ['Панель Управления'];
navPanel($array); 
break;

        case 'editUser': 
            
            if (empty($_GET['userId'])) 
            {
                error('Не получен идентификатор пользователя.', '/modules/administrative/index.php', 'Вернуться в панель управления');
            }
            
            $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$_GET['userId']]);
            
            if (isset($_POST['try']) && isset($_GET['userId']) && $user['access'] == 2) 
            {
                $data = $_POST;
                
                $data['userId']    = $Filter->clearInt($data['userId']);
                $data['login']     = $Filter->output($data['login']);
                
                if (empty($data['login'])) 
                {
                    errorNoExit('Обнаружено пустое поле.');
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".USERS." SET `login` = ? WHERE `id` = ?", [$data['login'], $data['userId']]);
                    success('Настройки сохранены.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            
            ?> 
<form action="" method="POST">
    <input type="hidden" name="userId" value="<?=$Filter->clearInt($_GET['userId'])?>">
    Логин: <br/>
    <input type="text" name="login" value="<?=$Filter->output($profile['login'])?>"><br/>
    <input type="submit" name="try" value="Подтвердить">
</form>
<?php 
$array = ['Панель Управления'];
navPanel($array); 
break; 

        case 'banedUser': 
            
            if (empty($_GET['userId'])) 
            {
                error('Не получен идентификатор пользователя.', '/modules/administrative/index.php', 'Вернуться в панель управления');
            } 
            
            $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$_GET['userId']]);
            
            if (isset($_POST['try']) && isset($_GET['userId']) && $user['access'] == 2) 
            { 
                $data = $_POST;
                
                $timeBan = [0 => 999999999, 1 => 300, 2 => 600, 3 => 900, 4 => 1800, 5 => 3600, 6 => 7200, 7 => 21600, 8 => 43200, 9 => 86400, 10 => 259200, 11 => 604800, 12 => 864000, 13 => 1209600, 14 => 2592000, 15 => 7776000, 16 => 15552000];
                
                $data['userId']    = $Filter->clearInt($data['userId']);
                $data['timeBan']   = $Filter->clearInt($data['timeBan']); 
                $data['text']      = $Filter->clearString($data['text']);
                $data['ipBan']     = (!empty($data['ipBan']) ? $Filter->clearInt($data['ipBan']) : NULL);
                
                $timeEnd           = time() + $timeBan[$data['timeBan']]; 
                
                if (empty($data['text'])) 
                {
                    errorNoExit('Не указана причина бана.');
                } 
                else 
                {
                    DB::$dbs->query("INSERT INTO ".BAN." (`user_id`, `moderator_id`, `time_ban`, `text`, `time`) VALUES (?, ?, ?, ?, ?)", array($profile['id'], $user['id'], $timeEnd, $data['text'], time()));
                    
                    if ($data['ipBan'] == TRUE) 
                    {
                        DB::$dbs->query("INSERT INTO ".BLACKLIST." (`ua`, `ip`) VALUES (?, ?)", array($profile['browser'], $profile['ip']));
                    }
                    
                    success('Операция выполнена успешно.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            } 
            ?> 
<form action="" method="POST">
    <input type="hidden" name="userId" value="<?=$Filter->clearInt($_GET['userId'])?>">
    <b>Заблокировать доступ к сайту, на:</b><br/>
    <select name="timeBan">
        <option value="0">На всегда</option>
        <option value="1">5 мин.</option>
        <option value="2">10 мин.</option>
        <option value="3">15 мин.</option>
        <option value="4">30 мин.</option>
        <option value="5">1 ч.</option>
        <option value="6">2 ч.</option>
        <option value="7">6 ч.</option>
        <option value="8">12 ч.</option>
        <option value="9">1 ст.</option>
        <option value="10">3 ст.</option>
        <option value="11">1 нед.</option>
        <option value="12">10 ст.</option>
        <option value="13">2 нед.</option>
        <option value="14">1 мес.</option>
        <option value="15">2 мес.</option>
        <option value="15">6 мес.</option>
        <option value="16">1 год.</option>
    </select><br/><br/>
    Добавить User Agent и IP в черный список: <input type="checkbox" name="ipBan" value="1"/><br/><br/>
    Причина:<br/>
    <input type="text" name="text" placeholder="Введите текст..." value="<?=(!empty($data['text']) ? $Filter->output($data['text']) : NULL)?>"/><br/><br/>
    <input type="submit" name="try" value="Подтвердить" />
</form>
<?php 
$array = ['Панель Управления'];
navPanel($array); 
break;

        case 'deleteUser': 
            
            if (empty($_GET['userId'])) 
            {
                error('Не получен идентификатор пользователя.', '/modules/administrative/index.php', 'Вернуться в панель управления');
            } 
            
            $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$_GET['userId']]);
            
            if (isset($_POST['try']) && isset($_GET['userId']) && $user['access'] == 2) 
            {
                $_GET['userId'] = $Filter->clearInt($_GET['userId']);
                
                if ($profile) 
                {
                    DB::$dbs->query("DELETE FROM ".USERS." WHERE `id` = ?", $profile['id']); // Удаляем юзера; 
                    /* 
                     * Здесь должны быть запросы на удаление сообщений юзера из чата, почты, записи банов и т.д.
                     */ 
                    success('Операция выполнена успешно.', '/modules/administrative/index.php', 'Вернуться в панель управления');
                }
            }
            ?> 
<div class="content" style="text-align: center">
    <b>
        Если вы уверены что хотите удалить пользователя <?=userLink($profile['id'])?> нажмите продолжить.
    </b>
</div>
<form action="" method="POST">
    <input type="hidden" name="userId" value="<?=$_GET['userId']?>">
    <input type="submit" name="try" value="Продолжить">
</form> 
<?php
$array = ['Панель Управления'];
navPanel($array); 
break;
} 
require_once '../../core/foot.php';