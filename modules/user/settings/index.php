<?php
define('_CONSTANT_', 1);
require_once '../../../core/start.php';
checkAuth();
head('Настройки', 'Настройки');

switch ($do) 
{
    default : 
        ?> 
<a href="<?=HOME?>/modules/user/settings/index.php?do=editLogin" class="link-touch"> Изменить логин</a> 
<?php 
        break; 
        case'editLogin': 
            $cost = $settings['login_cost'];
            if (isset($_POST['try'])) 
            {
                $data = $_POST; 
                $data['login'] = $Filter->clearString($data['login']); 
                
                if (empty($data['login'])) 
                {
                    errorNoExit('Обнаружено пустое поле.');
                } 
                elseif ($data['login'] == $user['login']) 
                {
                    errorNoExit('Вы уже используете данный логин.');
                } 
                elseif (DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." WHERE `login` = ?", [$data['login']]) == TRUE) 
                {
                    errorNoExit('Данный логин уже занят.');
                }
                elseif (strlen($data['login']) < 4 || strlen($data['login']) > 10) 
                {
                    errorNoExit('Длина логина должна быть в пределе [4-10].');
                } 
                elseif ($user['money'] < $cost) 
                {
                    errorNoExit('Недостаточно '.moneyName());
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".USERS." SET `login` = ?, `money` = ? WHERE `id` = ?", [$data['login'], ($user['money'] - $cost), $user['id']]);
                    success('Логин изменен.', '/modules/user/settings/index.php', 'Вернуться в настройки');
                }
            } 
            ?> 
<form action="" method="POST"> 
Ваш текущий логин: <?=$Filter->output($user['login'])?><br/> 
Стоимость смены логина <?= moneyIcon().$Filter->clearInt($cost).' '.moneyName()?><br/> 
Новый логин[4-10]:<br/> 
<input type="text" name="login" maxlength="10" placeholder="Введите логин" value="<?=$Filter->output($user['login'])?>"><br/> 
<input type="submit" name="try" value="Сменить">
</form> 
<?php 
            $array = ['Настройки']; 
            navPanel($array);
            break; 
} 
require_once '../../../core/foot.php';