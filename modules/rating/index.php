<?php
define('_CONSTANT_', 1); 

require_once '../../core/start.php';

checkAuth(); 

switch ($do) 
{
    default : 
        head('Рейтинг игроков', 'Рейтинг игроков');
        ?> 
<a href="<?=HOME?>/modules/rating/index.php?do=money" class="link-touch"> Рейтинг по основной валюте сайта</a> 
<a href="<?=HOME?>/modules/rating/index.php?do=level" class="link-touch"> Рейтинг по уровню</a> 
<a href="<?=HOME?>/modules/rating/index.php?do=timeOnline" class="link-touch"> Рейтинг по времени в игре</a> 
<?php 
        break; 
        case'money': 
            head('Рейтинг по основной валюте', 'Рейтинг по основной валюте'); 
            
            $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." ORDER BY `money` DESC");
            $n = new Navigator($all,10,'');
            $sql = DB::$dbs->query("SELECT * FROM ".USERS." ORDER BY `money` DESC LIMIT {$n->start()}, 10"); 
            
            while ($ank = $sql -> fetch()) 
            {
                ?> 
                <?=userLink($ank['id'], 'link-touch')?> 
                <?php
            } 
            ?>  
                <?=$n->navi()?> 
                <?php 
                $array = ['Рейтинг игроков'];
                navPanel($array); 
                break; 
                
        case'level': 
            head('Рейтинг по уровню', 'Рейтинг по уровню'); 
            
            $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." ORDER BY `lvl` DESC");
            $n = new Navigator($all,10,'');
            $sql = DB::$dbs->query("SELECT * FROM ".USERS." ORDER BY `lvl` DESC LIMIT {$n->start()}, 10"); 
            
            while ($ank = $sql -> fetch()) 
            {
                ?> 
                <?=userLink($ank['id'], 'link-touch')?> 
                <?php
            } 
            ?>  
                <?=$n->navi()?> 
                <?php 
                $array = ['Рейтинг игроков'];
                navPanel($array); 
                break; 
                
        case 'timeOnline': 
            head('Рейтинг по времени в игре', 'Рейтинг по времени в игре');
            
            $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." ORDER BY `online_time` DESC");
            $n = new Navigator($all,10,'');
            $sql = DB::$dbs->query("SELECT * FROM ".USERS." ORDER BY `online_time` DESC LIMIT {$n->start()}, 10"); 
            
            $position = 0;
            
            while ($ank = $sql -> fetch()) 
            { 
                $position ++;
                
                ?>
                <?=DIV_CONTENT?>

Позиция: <?=$position?><br/>Игрок: <?=userLink($ank['id'])?><br/> Провел в игре: <?=countTime($ank['online_time'])?> 

<?=CLOSE_DIV?>
                <?php
            }
            ?>  
                <?=$n->navi()?> 
                <?php 
                $array = ['Рейтинг игроков'];
                navPanel($array); 
                break;
                
        case 'usersOnline': 
            head('Кто онлайн', 'Кто онлайн'); 
            
            $all = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." WHERE `last_time` > ?", [(time() - 600)]);
            $n = new Navigator($all,10,'');
            $sql = DB::$dbs->query("SELECT * FROM ".USERS." WHERE `last_time` > ? ORDER BY `lvl` DESC LIMIT {$n->start()}, 10", [(time() - 600)]); 
            
            while ($ank = $sql -> fetch()) 
            {
                ?> 
                <?=userLink($ank['id'], 'link-touch')?> 
                <?php
            } 
            ?>  
                <?=$n->navi()?> 
                <?php  
                break;
} 
        
require_once '../../core/foot.php';