<?php

define('_CONSTANT_', 1);

require_once '../../core/start.php';

checkAuth();

head('История нарушений', 'История нарушений');

if ($user['access'] != 2) 
{
    header("Location: ".HOME."/game/");
    exit();
} 


switch ($do) 
{
    default : 
        break; 
        case 'banUser': 
            if (!isset($_GET['userId'])) 
            {
                error('Не получен идентификатор пользователя.', '/modules/administrative/index.php', 'Вернуться в панель управления');
            } 
            
            $data = $_GET;
            
            $data['userId'] = $Filter->clearInt($data['userId']);
            
            $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".BAN." WHERE `user_id` = ?", [$data['userId']]);
            $n = new Navigator($all,10,'');
            $sql = DB::$dbs->query("SELECT * FROM ".BAN." WHERE `user_id` = ? ORDER BY `id` DESC LIMIT {$n->start()}, 10", [$data['userId']]);
            
            echo DIV_CONTENT;
            
            if (empty($all)) 
            {
                successNoExit('Нарушений не обнаружено.');
            } 
            else 
            {
                while ($ban = $sql -> fetch()) 
                {
                    echo 'ID: ' . $ban['id'] . '<br/> Модератор: ' . userLink($ban['moderator_id']) . '<br/>Окончание: ' . dataTime($ban['time_ban']).'<br/>Причина:' . $ban['text'] . '<hr>';
                } 
                
                echo $n->navi();
            }
            
            echo CLOSE_DIV;
            
            $array = ['Панель Управления'];
            navPanel($array); 
            break;
} 

require_once '../../core/foot.php';