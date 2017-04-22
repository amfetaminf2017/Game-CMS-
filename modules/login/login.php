<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkIn();
head();
if (isset($_POST['try'])) 
{
    $data = $_POST; 
    $data['login'] = $Filter->clearString($data['login']);
    $data['password'] = $Filter->clearString($data['password']);
    $data['password'] = crypt($data['password'], '$1$game$');
    
    if (empty($data['login']) || empty($data['password'])) 
    {
        errorNoExit('Ошибка авторизации.');
    } 
    else 
    {
        $sql = DB::$dbs->queryFetch("SELECT `id` FROM ".USERS." WHERE `login` = ? && `password` = ?", [$data['login'], $data['password']]);
        
        if ($sql) 
        {
            $_SESSION['id'] = $sql['id']; 
            setcookie("login", $login, time()+9999999);
            setcookie("password", $password, time()+9999999);
            header("Location:".HOME."/game/");
        } 
        else 
        {
            errorNoExit('Ошибка авторизации.');
        }
    }
} 
?> 
<form action="" method="POST">
    <br/>
    <input class="dark" type="text" name="login" maxlength="15" placeholder="Введите логин"><br/><br/>
    <input class="dark" type="password" name="password" maxlength="20" placeholder="Введите пароль"><br/><br/>
    <input type="submit" name="try" value="Войти">
</form>
<?php 
require_once '../../core/foot.php';