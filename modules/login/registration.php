<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkIn();
head('Регистрация', 'Регистрация'); 
if ($settings['registration'] == 0) 
{
    error('Регистрация временно закрыта администратором сайта.');
} 
else 
{
    if (isset($_POST['try'])) 
{ 
    $data = $_POST;
    $data['login'] = $Filter->clearString($data['login']);
    if (empty($data['login'])) 
    {
        errorNoExit('Введите логин.');
    } 
    elseif (empty ($data['password'])) 
    {
        errorNoExit('Введите пароль.');
    } 
    elseif ($data['password'] != $data['rePassword']) 
    {
        errorNoExit('Пароли не совпали.');
    }
    elseif (strlen($data['login']) > 15 || strlen($data['login']) < 4) 
    {
        errorNoExit('Длина логина должна быть в пределе [4-15].');
    } 
    elseif (strlen($data['password']) > 20 || strlen($data['password']) < 6) 
    {
        errorNoExit('Длина пароля должна быть в пределе [6-20].');
    } 
    else 
    {
        DB::$dbs->query("INSERT INTO ".USERS." (`login`, `password`, `money`) VALUES (?, ?, ?)", [$data['login'], crypt($data['password'], '$1$game$'), $settings['registration_money']]);
        $_SESSION['id'] = DB::$dbs->lastInsertId();
        setcookie("login", $data['login'], time()+9999999);
        setcookie("password", $data['password'], time()+9999999);
        success('Вы успешно зарегистрированы. <a href="'.HOME.'/game/"> В игру</a>');
    }
} 
?> 
<form action="" method="POST"> 
    Логин[4-15]<br/> <br/>
    <input class="dark" type="text" name="login" maxlength="15" placeholder="Введите логин" value="<?=(!empty($data['login']) ? $data['login'] : null)?>"><br/> 
    Пароль[6-20]<br/><br/> 
    <input class="dark" type="password" name="password" maxlength="20" placeholder="Введите пароль"><br/>
    Повтор пароля<br/><br/> 
    <input class="dark" type="password" name="rePassword" maxlength="20" placeholder="Повторите пароль"><br/>
    <input type="submit" name="try" value="Зарегистрироваться">
</form> 
<?php
}
require_once '../../core/foot.php';