<?php

/* 
 * Класс для работы с пользователем;
 * 
 */ 
class User 
{
    #public $userMoney; 
    
    /* 
     * Пользовательськая информация;
     * 
     */
    public function userInfo ($userId) 
    {
        global $Filter; 
        
        $userId = $Filter->clearInt($userId);
        $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]);
        
        $this->userDefend = $Filter->clearInt($profile['defend']);                                            // Защита;
        $this->userHealth = $Filter->clearInt($profile['health']);                                            // Здоровье;
        $this->userStrike = $Filter->clearInt($profile['strike']);                                            // Сила;
        $this->userParam = $Filter->clearInt($this->userDefend + $this->userHealth + $this->userStrike);      // Сумма параметров; 
        #$this->userClass = $this->userClass($userId);                                                        // Класс;
        $this->userMoney = $Filter->clearInt($profile['money']);                                              // Деньги; 
        $this->userLogin = $Filter->output($profile['login']);                                                // Логин;
    } 
    /* 
     * Обработка классов;
     * 
     */ 
    public function userClass ($userId) 
    {
        global $Filter; 
        global $settings;
        
        $userId = $Filter->clearInt($userId);
        $profile = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$userId]); 
        
        if ($profile['class'] == 1) 
        {
            $class = $Filter->output($settings['class_1_name']);
        } 
        elseif ($profile['class'] == 2) 
        {
            $class = $Filter->output($settings['class_2_name']);
        } 
        
        return $class;
    } 
    
    public function getQuery($query, $id) 
    {
        $this->userInfo($id);
        return $this->$query;
    } 
    
    /* 
     * Применение дизайна;
     */
    public function setDesign() 
    { 
        global $user;
        
        if ($user['design'] == 'touch') 
        {
            $setDesign = 'lite';
        } 
        else 
        {
            $setDesign = 'touch';
        } 
        
        DB::$dbs->query("UPDATE ".USERS." SET `design` = ? WHERE `id` = ? ", [$setDesign, $user['id']]); 
        
        $deleteText = '?setDesign';
        
        $headerUri = $_SERVER['REQUEST_URI']; 
        $headerUri = str_replace($deleteText, '', $headerUri);
        header("Location: ".HOME. $headerUri."");
    }
    
    /*
     * Обработка должностей;
     */ 
    public function userAccess ($userId, $type = null) 
    {  
        global $Filter;
        
        $userId = $Filter->clearInt($userId);
        
        $ank = DB::$dbs->queryFetch("SELECT `access` FROM ".USERS." WHERE `id` = ?", array($userId));
        
        if ($type == NULL) 
        {
            if ($ank['access'] == 0) $access = 'Игрок'; 
            elseif ($ank['access'] == 1) $access = '<font color="orange">Модератор</font>';
            elseif ($ank['access'] == 2) $access = '<font color="orange">Администратор</font>';
        } 
        elseif ($type == 1) 
        {
            if ($ank['access'] == 0) $access = ''; 
            elseif ($ank['access'] == 1) $access = '<font color="orange">[m]</font>'; 
            elseif ($ank['access'] == 2) $access = '<font color="orange">[a]</font>';
        } 
        
        return $access;
    }
    
    /*
     * Проверка почты;
     */ 
    function checkMail () 
    {
        global $user, $Filter;
        
        $all = DB::$dbs->querySingle("SELECT COUNT(*) FROM ".MAIL." WHERE `given` = ? AND `read` = ? ", [$user['id'], 1]); 
        
        if ($all > 0) 
        {
            echo '<a href="'.HOME.'/modules/mail/index.php" class="link-touch">'.ico('mail.png', '[m-ico]', '16', '16').' Почта <span class="count">+ '.$Filter->clearInt($all).'</span></a>';
        }
    }
}