<?php

define('_CONSTANT_', 1);

require_once 'core/start.php';

checkIn();

head();

?>
<a href="<?=HOME?>/modules/login/registration.php" class="link-touch"> Регистрация</a>
<a href="<?=HOME?>/modules/login/login.php" class="link-touch"> Вход</a><br/>
<?php

require_once 'core/foot.php';