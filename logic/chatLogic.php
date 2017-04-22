<?php

class ChatLogic 
{
    public function chatBan ($string, $id) 
    { 
        /*global $Filter;
        
        #$searchMat = ['шлюха','Шлюха','тварь','Тварь','ебля','Ебля','сцука','Сцука','бальник','Бальник','дарас','пида','Пида','гнида','Гнида','мудо','сран','суче','отху','Отху','залупa','Залупa','гонд','Гонд','пидо','Пидо','пизда','Пизда','хер','Хер','едри','падонак','уеб','уёб','Уеб','Уёб','блеадь','блять','Блять','сука','Cука','долбо','долбае','долбаё','пезда','аху','оху','хуя','хуй','Долбо','Долба','Пезда','Бля','Аху','Оху','Хуя','Хуй'];
        #$searchUrl = ['http', 'http://', '.ru', '.su']; 
        
        $idText = $Filter->clearInt($id);
        
        $chat = DB::$dbs->queryFetch("SELECT * FROM ".CHAT." WHERE `id` = ?", [$idText]);
        
        
        $string = $Filter->output($string);
        $string = nl2br($string);
        #$string = str_replace($searchMat, '', $string); 
        if (!str_replace($searchMat, '', $string)) 
        {
            DB::$dbs->query("UPDATE ".CHAT." SET `text` = ? WHERE `id` = ?", ['[Обнаружен мат. Вам выдано предупреждение!!!]', $chat['id']]);
        }*/
    }
}

