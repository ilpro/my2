<?
class MKdate{
    private static $_month = array(
				'Січня',
				'Лютого',
				'Березня',
				'Квітня',
				'Травня',
				'Червня',
				'Липня',
				'Серпня',
				'Вересня',
				'Жовтня',
				'Листопада',
				'Грудня'
				);
    private static $_week = array(
                'Неділя',
                'Понеділок',
				'Вівторок',
				'Середа',
				'Четвер',
				'П\'ятниця',
				'Субота'
				);

    private static $_nowMonth = NULL;
    private static $_nowWeek = NULL;
    private function __construct() {}
    private static function getInstance() {
        if (self::$_nowMonth==NULL){                        
            self::$_nowMonth=self::$_month[(int)date('m')-1];
        }
        if (self::$_nowWeek==NULL){                        
            self::$_nowWeek=self::$_week[(int)date('w')];
        }        
    }

    public static function getNowWeek() {
		if (self::$_nowWeek==NULL){
	        self::getInstance();
        }
		
	    return self::$_nowWeek;       
    }

    public static function getNowMonth() {

        if (self::$_nowMonth==NULL){
	      self::getInstance();  
        }
			
	    return self::$_nowMonth; 
	         
    }    
 
}