<?php

/*
 * Класс для работы с атакой;
 * 
 */
class Attack 
{
    public $userId;
    public $enemyId; 
    
    function __construct($enemyId) 
    {
        global $Filter;                                                                                  // Фильтр;
        
        $this->enemyId = $Filter->clearInt($enemyId);                                                    // ID противника;
    } 
    
    /*
     * Обработка противника;
     */
    public function checkEnemy ($enemyId) 
    { 
        global $Filter;                                                                                  // Фильтр;

        $enemy = DB::$dbs->queryFetch("SELECT * FROM ".USERS." WHERE `id` = ?", [$this->enemyId]);       // Противник;
        
        $this->enemyDefend = $Filter->clearInt($enemy['defend']);                                        // Защита;
        $this->enemyHealth = $Filter->clearInt($enemy['health']);                                        // Здоровье;
        $this->enemyStrike = $Filter->clearInt($enemy['strike']);                                        // Сила;
        $this->enemyParam = $this->enemyDefend + $this->enemyHealth + $this->enemyStrike;                // Сумма параметров;
        
        $attackParam = round($this->enemyParam * 5 / 100);                                               // Просчет 5% от суммы парамеров(пока не используется. Дальше понадобиться);
        $attackStrike = round($this->enemyStrike * 5 / 100);                                             // Просчет 5% от силы;
        
        $randAttack = rand($this->enemyStrike - $attackStrike, $this->enemyStrike + $attackStrike);      // Просчет атаки;
        
        return $randAttack;
    }
}