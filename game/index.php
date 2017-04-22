<?php
define('_CONSTANT_', 1);

require_once '../core/start.php';

checkAuth();

head(NULL, 'Главная');

?>
<div class="table">
    <table border="0" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th width="7%"><img src="<?=HOME?>/img/game-menu-left.jpg" width="160"/></th>
            <th width="7%"><img src="<?=HOME?>/img/game-menu-right.jpg" width="160"/></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
        </tr>
        <tr>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
        </tr>
        <tr>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
        </tr>
        <tr>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
        </tr>
        <tr>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
            <td width="7%"><a href="#ссылка" class="big-but">Название</a></td>
        </tr>
        <tr>
            <td width="7%"><a href="<?=HOME?>/modules/rating/" class="big-but">Рейтинг игроков</a></td>
            <td width="7%"><a href="<?=HOME?>/modules/chat/" class="big-but">Чат</a></td>
        </tr>
    </tbody>
    </table>
</div>

<?php
require_once '../core/foot.php';