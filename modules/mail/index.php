<?php
define('_CONSTANT_', 1);
require_once '../../core/start.php';
checkAuth();
head('Почта', 'Почта');

$userId = (!empty($_GET['userId']) ? $Filter->clearInt($_GET['userId']) : NULL); 

if ($userId) 
{
    $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]); 
    
    if (!$profile) 
    {
        error('Персонаж не найден.');
    } 
    else 
    {
        if (isset($_POST['try']) && $user['lvl'] >= $settings['mail_lvl']) 
        {
            $data = $_POST;
            $data['text'] = $Filter->clearString($data['text']); 
            
            if (empty($data['text'])) 
            {
                errorNoExit('Пустое сообщение.');
            } 
            elseif (strlen($data['text']) < 1 || strlen($data['text']) > $settings['mail_max']) 
            {
                errorNoExit('Длина сообщения должна быть в пределе [1-'.$Filter->clearInt($settings['mail_max']).']');
            } 
            elseif ($user['money'] < $settings['mail_cost']) 
            {
                errorNoExit('Недостаточно '.moneyName());
            } 
            elseif ($user['id'] == $userId) 
            {
                errorNoExit('Нельзя писать себе.');
            }
            else 
            { 
                $kontakt = DB::$dbs->queryFetch("SELECT * FROM ".MAIL_KONT." WHERE `id_user` = ? and `id_kont` = ? LIMIT 1", [$user['id'], $profile['id']]); 
                if ($kontakt['id_kont'] != $profile['id']) 
                {
                    DB::$dbs->query("INSERT INTO ".MAIL_KONT." (`id_user`, `id_kont`, `time`) VALUES (?, ?, ?)", [$user['id'], $profile['id'], time()]);
                    DB::$dbs->query("INSERT INTO ".MAIL_KONT." (`id_user`, `id_kont`, `time`) VALUES (?, ?, ?)", [$profile['id'], $user['id'], time()]);
                } 
                else 
                {
                    DB::$dbs->query("UPDATE ".MAIL_KONT." SET `time` = ? WHERE `id_user` = ? and `id_kont` = ?", [time(), $user['id'], $profile['id']]);
                    DB::$dbs->query("UPDATE ".MAIL_KONT." SET `time` = ? WHERE `id_user` = ? and `id_kont` = ?", [time(), $profile['id'], $user['id']]);
                }
                DB::$dbs->query("UPDATE ".USERS." SET `money` = ? WHERE `id` = ?", [($user['money'] - $settings['mail_cost']), $user['id']]); 
                DB::$dbs->query("INSERT INTO ".MAIL." (`sender`, `given`, `text`, `time`) VALUES (?, ?, ?, ?)", [$user['id'], $profile['id'], $data['text'], time()]);
                success('Сообщение отправлено.', '/modules/mail/index.php?userId='.$userId.'', 'Вернуться в почту');
            }
        } 
        ?> 
Сообщение для <?=$Filter->output($profile['login'])?><br/>
<a href="<?=HOME?>/modules/mail/index.php?userId=<?=$profile['id']?>" class="link-touch"> Обновить</a>
<a href="#" class="link-touch"> Смайлы</a>
<?php
if ($user['lvl'] >= $settings['mail_lvl']) 
{
    ?>
<form action="" method="POST">
    <textarea name="text" placeholder="Введите сообщение"  maxlength="<?=$Filter->clearInt($settings['mail_max'])?>" value="<?=(!empty($data['text']) ? $data['text'] : NULL)?>"></textarea>
    <input type="submit" name="try" value="Отправить">
</form> 
<?php
} 
else 
{
    errorNoExit('Писать в почте можно с '.$settings['mail_lvl'].' уровня.');
} 
?> 
<div style="text-align: center;">История переписки</div><br/>
<?php 
$all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".MAIL." WHERE `sender` = ? and `given` = ? or `sender` = ? and `given` = ?", [$profile['id'], $user['id'], $user['id'], $profile['id']]);
$n = new Navigator($all,10,'');
$sql = DB::$dbs->query("SELECT * FROM ".MAIL." WHERE `sender` = ? and `given` = ? or `sender` = ? and `given` = ? ORDER BY `time` DESC LIMIT {$n->start()}, 10", [$profile['id'], $user['id'], $user['id'], $profile['id']]);
if ($all == 0) 
{
    errorNoExit('Сообщений нет...');
} 
else 
{ 
    DB::$dbs->query("UPDATE ".MAIL." SET `read` = ? WHERE `sender` = ? and `given` = ?", [0, $profile['id'], $user['id']]);
    while ($mail = $sql -> fetch()) 
    {
        $ank = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$mail['sender']]);
        ?> 
        <?=userLink($ank['id']).dataTime($mail['time'])?><br/>
<?php 
if ($mail['read'] == 1) 
{
    $textColor = 'green'; 
    $bCode = TRUE;
} 
else 
{
    $textColor = 'white';
    $bCode = FALSE;
} 
?> 
        <?=($bCode = TRUE ? '<font color="'.$textColor.'"><b>' : '<font color="'.$textColor.'">').outputText($mail['text'], $ank['id']).($bCode = TRUE ? '</b></font>' : '</font>')?><hr> 
        <?php 
    } 
    ?> 
        <?=$n->navi()?>
        <?php
} 
$array = ['Почта'];
navPanel($array);
    }
} 
else 
{
    $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".MAIL_KONT." WHERE `id_user` = ?", [$user['id']]);
    $n = new Navigator($all,10,'');
    $sql = DB::$dbs->query("SELECT * FROM ".MAIL_KONT." WHERE `id_user` = ? ORDER BY `time` DESC LIMIT {$n->start()}, 10", [$user['id']]);
    
    if ($all == 0) 
    {
        ?> 
        Контактов нет. 
        <?php 
    } 
    else 
    {
        while ($kont = $sql -> fetch()) 
        {
            $ank = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ? LIMIT 1", [$kont['id_kont']]); 
            $newMess = DB::$dbs->querySingle("SELECT COUNT(*) FROM ".MAIL." WHERE `sender` = ? and `given` = ? and `read` = ?", [$ank['id'], $user['id'], 1]);
            ?> 
        <?=userLink($ank['id']).($newMess > 0 ? ' + <font color="green">'.$newMess.'</font>' : NULL)?><br/>
        <a href="<?=HOME?>/modules/mail/index.php?userId=<?=$ank['id']?>" class="link-touch"> Перейти к диалогу</a><hr>
        <?php
        } 
        ?> 
        <?=$n->navi()?> 
        <?php
    } 
} 
require_once '../../core/foot.php';