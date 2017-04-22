<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkAuth();
head('Выход', 'Выход'); 
?>
Вы действительно хотите выйти из игры?<br/><a href="<?=HOME?>/modules/login/exit.php?exitNow" class="link-touch"> Да</a><a href="<?=HOME?>/" class="link-touch"> Нет</a>
<?php 
if (isset($_GET['exitNow']) && $user) 
{
    unset($_SESSION['id']);
    setcookie('login', '', time() - 30);
    setcookie('password', '', time() - 30);
    header("Location: ".HOME."/");
    exit();
}
