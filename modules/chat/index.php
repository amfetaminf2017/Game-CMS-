<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';

/* 
 * Connect logic files;  
 */
require_once '../../logic/chatLogic.php'; // Chat ban and other;
$chatLogic = new ChatLogic(); 

checkAuth();
head('Чат', 'Чат');

if (isset($_POST['try'])) 
{
    $data = $_POST;
    $data['text'] = $Filter->clearString($data['text']); 
    
    if (empty($data['text'])) 
    {
        errorNoExit('Пустое сообщение.');
    } 
    elseif (strlen($data['text']) < 1 || strlen($data['text']) > $settings['chat_max']) 
    {
        errorNoExit('Длина сообщения должна быть в пределе [1-'.$Filter->clearInt($settings['chat_max']).']');
    } 
    else 
    { 
        if (!empty($_GET['otv']) && $_GET['otv'] != $user['id']) 
        {
            $_GET['otv'] = $Filter->clearInt($_GET['otv']);
            $ank = DB::$dbs->queryFetch("SELECT `id`, `login` FROM ".USERS." WHERE `id` = ? ",[$_GET['otv']]); 
            if (!empty($ank)) 
            {
                $data['text'] = '' . $Filter->output($ank['login']) . ', ' . $data['text'];
            }
        } 
        $idKont = (!empty($ank) ? $ank['id'] : 0);
        DB::$dbs->query("INSERT INTO ".CHAT." (`id_user`, `id_kont`, `time`, `text`) VALUES (?, ?, ?, ?)", [$user['id'], $idKont, time(), $data['text']]);
        success('Сообщение отправлено.', '/modules/chat/', 'Вернуться в чат');
    }
} 
if (isset($_POST['postDelete']) && $user['access'] == 2) 
{
    $data = $_POST; 
    $data['postDelete'] = $Filter->clearInt($data['postDelete']); 
    foreach ($data as $name => $value) 
    {
        DB::$dbs->query("DELETE FROM ".CHAT." WHERE `id` = ?", [$name]); 
    } 
    success('Удалено.', '/modules/chat/', 'В чат');
} 
if (isset($_POST['clean']) && $user['access'] == 2) 
{ 
    $data = $_POST; 
    $data['clean'] = $Filter->clearInt($data['clean']); 
    DB::$dbs->query("DELETE FROM ".CHAT."");
    DB::$dbs->query("INSERT INTO ".CHAT." (`id_user`, `id_kont`, `time`, `text`) VALUES (?, ?, ?, ?)", [$user['id'], 0, time(), 'Чат очищен.']);
    success('Очищено.', '/modules/chat/', 'В чат');
} 
if (isset($_POST['postUpdate']) && $user['access'] == 2) 
{
    $data = $_POST; 
    $data['postUpdate'] = $Filter->clearInt($data['postUpdate']); 
    foreach ($data as $name => $value) 
    {
        DB::$dbs->query("UPDATE ".CHAT." SET `text` = ? WHERE `id` = ?", [NULL, $name]);
    } 
    success('Текст удален.', '/modules/chat/', 'В чат');
}
?>
<a href="<?=HOME?>/modules/chat/" class="link-touch"> Обновить</a> 
<?php
if (!empty($_GET['otv']) && $_GET['otv'] != $user['id']) 
{
    $_GET['otv'] = $Filter->clearInt($_GET['otv']);
    $ank = DB::$dbs->queryFetch("SELECT `id`, `login` FROM ".USERS." WHERE `id` = ? ",[$_GET['otv']]);
    if (!empty($ank)) 
    {
        ?> 
<form action="?otv=<?=$Filter->clearInt($ank['id'])?>" method="POST"> 
    Сообщение пользователю <b><?=$Filter->output($ank['login'])?></b><br/> 
    <?php
    }
} 
else 
{
    ?> 
    <form action="" method="POST"> 
        <?php
} 
?> 
        <textarea name="text"></textarea><br/>
        <input type="submit" name="try" value="Отправить"> </form> 
    <?php
    $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".CHAT.""); 
    
    if (empty($all)) 
    { 
        errorNoExit('Сообщений нет...');
    } 
    else 
    { 
        ?>
    <form action="#" method="POST">
    <?php 
        $n = new Navigator($all,10,'');
        $sql = DB::$dbs->query("SELECT * FROM ".CHAT." ORDER BY `id` DESC LIMIT {$n->start()}, 10");
        while ($post = $sql ->fetch()) 
        { 
            #$chatLogic->chatBan($post['text'], $post['id']);
            $ank = DB::$dbs->queryFetch("SELECT `id`, `login` FROM ".USERS." WHERE `id` = ?",array($post['id_user'])); 
            if ($user['access'] == 2) 
            {
                ?> 
    <input type="checkbox" name="<?=$Filter->clearInt($post['id'])?>"> 
    <?php
            } 
            if ($post['id_user'] != $user['id']) 
            {
                ?> 
    <?=userLink($Filter->clearInt($post['id_user']))?><font color="#909090">[<?=dataTime($Filter->clearInt($post['time']))?>]</font><a href="?otv=<?=$Filter->clearInt($post['id_user'])?>">(отв)</a><br/> 
    <?=outputText($post['text'], $post['id_user'])?><hr> 
    <?php
            } 
            else 
            {
                ?> 
    <?=userLink($Filter->clearInt($post['id_user']))?><font color="#909090">[<?=dataTime($Filter->clearInt($post['time']))?>]</font><br/> 
    <?=outputText($post['text'], $post['id_user'])?><hr> 
    <?php
            }
        } 
        if ($user['access'] == 2) 
        {
            ?> 
    <input type="submit" name="postDelete" value="Удалить выделенные посты"><input type="submit" name="postUpdate" value="Удалить текст выделенных постов"><input type="submit" name="clean" value="Очистить чат"></form>
    <?php
        } 
        ?> 
    <?=$n->navi()?>
    <?php
    } 
    require_once '../../core/foot.php';