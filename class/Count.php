<?php
defined('_CONSTANT_') or die('Error. You don`t have permision to access.');
class Count 
{ 
    /*
     * Количество пользователей;
     */
    public static function userCount() 
    { 
        global $Filter;
        $count = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS.""); 
        return $Filter->clearInt($count);
    }
    
    /*
     * Количество пользователей онлайн;
     * $setLimit - Сколько времени после бездействия отображается в онлайне;
     */ 
    public static function userCountOnline() 
    {
        global $Filter;
        $setLimit = 600; // 10 мин.
        $count = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".USERS." WHERE `last_time` > ?", [(time() - $setLimit)]);
        return $Filter->clearInt($count);
    }
    
    /*
     * Количество банов;
     */ 
    public static function userCountBan($userId) 
    {
        global $Filter;
        $count = DB::$dbs->querySingle("SELECT COUNT(`id`) FROM ".BAN." WHERE `user_id` = ?", [$userId]);
        
        return $Filter->clearInt($count);
    }
}
