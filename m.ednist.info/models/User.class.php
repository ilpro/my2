<?php

/**
 * Работа с юзерами
 */
class User {

    protected $table = '`tbl_user`';
    public $db_salt;
    public $dbEntropy;
    public $transferEntropy; // Соль используемая при передаче пароля в сети
    protected $usr_id;
    protected $usr_email;
    protected $usr_password;
    protected $usr_role;
    protected $usr_name;
    protected $usr_active;
    protected $itms_in_page = 15;
    protected $db_iteration;
    protected $transfer_salt;
    protected $transfer_iteration;
    

// Конструктор класса
    public function __construct($usr_email = '', $usr_password = '') {
        $this->usr_email = Clear::dataS($usr_email);
        $this->usr_password = Clear::dataS($usr_password);
        $this->dbEntropy = new Entropy();
        $this->db_salt = $this->dbEntropy->salt;
        $this->db_iteration = $this->dbEntropy->iterationCount;
        $this->transferEntropy = new Entropy();
        $this->transfer_salt = $this->transferEntropy->salt;
        $this->transfer_iteration = $this->transferEntropy->iterationCount;
    }

    /**
     * Выборка с базы юзеров, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
    public function getUsers($conditions = '') {

        db::sql("SELECT * FROM " . $this->table . " " . $conditions);
        $query_res = db::query();
        if (!empty($query_res) && is_array($query_res))
            return $query_res;
        else
            return false;

    }
    /**
     * Выборка с базы юзеров, согласно заданым условиям для статистика главному редактору
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
     public function getUsersStatistics($value=1) {

        db::sql("SELECT * FROM " . $this->table );
        $query_res = db::query();
        if (!empty($query_res) && is_array($query_res)){
            $newsAdmin=new NewsAdmin();
            if($value==1){
                $condition=' WHERE year(`newsTimePublic`) = year(current_timestamp) AND month(`newsTimePublic`) = month(current_timestamp) AND day(`newsTimePublic`) = day(current_timestamp) AND `newsStatus`=4 AND `userId`=';
            }else if($value==2){
                $condition=' WHERE `newsTimePublic` >= (CURDATE()-1) AND `newsTimePublic` < CURDATE() AND `newsStatus`=4 AND `userId`='; 
            }else if($value==3){
               $condition=' WHERE `newsTimePublic` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND `newsStatus`=4 AND `userId`=';  
            }else if($value==4){
               $condition=' WHERE `newsTimePublic` >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) AND `newsStatus`=4 AND `userId`='; 
            }
            foreach ($query_res as $key=> $value) {
              $query_res[$key]['newsCount']=  $newsAdmin->getNewsAdminStatistics($condition.$value['userId'])[0]; 
            }
            return $query_res;
        }
        else
            return false;

    }
    
       /**
     * Выборка с базы юзеров, согласно заданым условиям для статистика главному редактору(сума всех юзеров)
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
     public function getUsersStatisticsAll($value=1) {
     $newsAdmin=new NewsAdmin();
      if($value==1){
                $condition=' WHERE year(`newsTimePublic`) = year(current_timestamp) AND month(`newsTimePublic`) = month(current_timestamp) AND day(`newsTimePublic`) = day(current_timestamp) AND `newsStatus`=4 ';
            }else if($value==2){
                $condition=' WHERE `newsTimePublic` >= (CURDATE()-1) AND `newsTimePublic` < CURDATE() AND `newsStatus`=4 '; 
            }else if($value==3){
               $condition=' WHERE `newsTimePublic` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) AND `newsStatus`=4 ';  
            }else if($value==4){
               $condition=' WHERE `newsTimePublic` >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) AND `newsStatus`=4 '; 
            }
     $result=  $newsAdmin->getNewsAdminStatistics($condition)[0]; 
     if(!empty($result)){
         return $result;
     }else{
         return false;
     }
    }
    
    /**
     * Поиск юзера по Id
     * @param type $userId -условие запроса
     */
    public function getUserById($userId){
            $id=Clear::dataI($userId);
            db::sql("SELECT * FROM tbl_user WHERE userId = '$id'");
            $result=db::query();
            return $result[0];
    } 
    /**
     * Сохранения нового юзера
     * @param string $conditions -условия для INSERT
     * @param array $info -массив даних для вкладивания
     */
    public function saveUser($info, $conditions = '') {

        db::sql("INSERT INTO " . $this->table . " " . $conditions);
        db::addParameters($info);
        $result = db::execute();
        if (isset($result)) {
            return $result;
        } else {
            return false;
        }
    }

    //РЕГИСТРАЦИЯ НОВОГО ПОЛЬЗОВАТЕЛЯ

    /**
     * Функция проверки существования пользователя и наполнения переменных из базы, в случае позитива         
     * @param type $restore -условия для запроса
     * @return boolean
     */
    public function userExists($restore = 0) {
        $info['usr_email']=$this->usr_email;
        db::sql("SELECT * FROM tbl_user WHERE userEmail=_1");
        db::addParameters($info);
        $result=db::query();
        if ($result == false) {
            return false;
        } elseif ($restore == 0) {
            $this->usr_id = $result[0]['userId'];
            $this->usr_password = $result[0]['userPass'];
            $this->db_salt = $result[0]['dbSalt'];
            $this->db_iteration = $result[0]['dbIteration'];
            $this->usr_role = $result[0]['userRole'];
            $this->usr_name = $result[0]['userName'];
            $this->usr_active = $result[0]['userActive'];
            return true;
        } else {
            return true;
        }
    }

    /**
     * Функция проверки существования пользователя и наполнения переменных из базы, в случае позитива  
     * @param $id -условия для запроса
     * @return boolean
     */
    public function emailExistsOther($id) {
        $info = $this->usr_email;
        $result = $this->getUsers(' WHERE `userEmail`=' . $info . ' AND `userId`<>' . $id);
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Функция добавления нового пользователя в базу с засолкой пароля и активацией по умолчанию(Использовать,если админу нужно дабавить массив пользователей)   
     */
	public function registerPhp() {
        if (!$this->userExists()) {
            $this->usr_password = $this->dbEntropy->getSaltedHash($this->usr_password);
            $this->usr_rights = 1;
            $info = $this->userArrayToDb();
            $result = $this->saveUser($info, '(userEmail, userPass, dbSalt, dbIteration,userRole,userActive) VALUES (_1,_2,_3,_4,_5,1)');
            return $this->usr_id = $result;
        } else {
            return 'exists';
        }
    }
    /**
     * Формирование массива для запроса в базу
     * @return array
     */
    protected function userArrayToDb() {
        $result['usr_email']=$this->usr_email;
        $result['usr_password']=$this->usr_password;
        $result['db_salt']=$this->db_salt; 
        $result['db_iteration']=$this->db_iteration;
        $result['usr_rights']=$this->usr_rights;               
        $result['username']=$this->usr_name;   
        $result['usr_active']=  $this->usr_active;
        return $result;
    }

////////////////////////////////////////////////////////////////////////////////////////JS
        /**
         * Функция генерации базовых соли и итераций для регистрации 
         * @return array
         */
	public function registerJs1(){              
        if(!$this->userExists()){ 
            $hash['db_salt']=$this->db_salt;
            $hash['db_iteration']=$this->db_iteration;                      
        }
        else{
            $hash['db_iteration']='exists';
        }
         return $hash;               
	} 
        /**
         * Функция генерации базовых соли и итераций для регистрации(востановления) 
         * @return array
         */
	public function restoreJs1(){               
        if($this->userExists(1)){ 
            $hash['db_salt']=$this->db_salt;
            $hash['db_iteration']=$this->db_iteration;                      
        }
        else{
            $hash['db_iteration']='exists';
        }
         return $hash;                
	} 
        /**
         * Функция генерации базовых соли и итераций для редактироваия 
         * @param type $id -условие запроса
         * @return array
         */
	public function edituser1($id){               
        if(!$this->emailExistsOther(Clear::dataI($id))){ 
            $hash['db_salt']=$this->db_salt;
            $hash['db_iteration']=$this->db_iteration;                      
        }
        else{
            $hash['db_iteration']='exists';
        }
        return $hash;               
	} 
        /**
         * Функция добавления нового пользователя в базу,если работаем через AJAX  
         * Условие запроса:
         * @param type $dbSalt
         * @param type $dbIteration
         * @param type $userrole
         * @param type $username
         * @return array
         */
	public function registerJs2($dbSalt,$dbIteration,$userrole,$username,$active){
            $this->db_salt=Clear::dataS($dbSalt); 
            $this->db_iteration=Clear::dataS($dbIteration);
            $this->usr_rights=Clear::dataS($userrole); 
            $this->usr_name=Clear::dataS($username);      
            $this->usr_active=Clear::dataI($active);
            $info=$this->userArrayToDb();
            $result = $this->saveUser($info, '(userEmail, userPass, dbSalt, dbIteration,userRole, userName,userActive) VALUES (_1,_2,_3,_4,_5,_6,_7)');
            if($this->userExists()){
                if($this->usr_active==0){
                    UserEmail::emailActivation($this->usr_email,$this->db_salt);
                }
                $this->usr_id=$result; 
                $hash['register']=$this->usr_id;
                $hash['active']=$this->usr_active;
            }
            else{
	            $hash['register']=0;
            }           
           return $hash; 
	}
        /**
         * функция отправления email
         * @return array
         */
	public function sendEmailActivation(){   
            if($this->userExists()){
                UserEmail::emailActivation($this->usr_email,$this->db_salt);
                $hash['send']=1;
            }else{
                $hash['send']=0;
            }
            return $hash;
    }
 ///////////////////////////////////////////////////////////////////////////////MAIL  
 
//////////////////////////////////////////////////////////////////////////LOGIN 
        /**
         * Функция передачи базовых и трансферных соли и итераций  
         * @return array
         */
	public function login1(){             
        if($this->userExists()){ 
            $hash['db_salt']=$this->db_salt;
            $hash['db_iteration']=$this->db_iteration;
            $hash['transfer_salt']=$this->transfer_salt; 
            $hash['transfer_iteration']=$this->transfer_iteration; 
            $hash['user_active']=$this->usr_active;                     
        }
        else{
            $hash['db_iteration']='notExists';
        }
         return $hash;           
	} 
        /**
         * Функция аутентефикации JS,coockie
         * Условие запроса
         * @param type $pass
         * @param type $transferSalt
         * @param type $transferIteration
         * @param type $remember 
         * @return array
         */
	public function login2($pass,$transferSalt,$transferIteration,$remember=0){         
        if($this->userExists()){
            $remember=Clear::dataI($remember);
            $pass=Clear::dataS($pass);
            $this->transfer_salt=Clear::dataS($transferSalt);
            $this->transfer_iteration=Clear::dataS($transferIteration);
            if($this->isValidPassword($pass)){
                if(1==$remember){
                    setcookie('email',$this->usr_email,time()+60*60*24*365,'/'); 
                    setcookie('pass',$pass,time()+60*60*24*365,'/'); 
                    setcookie('salt',$this->transfer_salt,time()+60*60*24*365,'/'); 
                    setcookie('iteration',$this->transfer_iteration,time()+60*60*24*365,'/');                                    
                }
                $_SESSION['user']['id']=$this->usr_id;
                $_SESSION['user']['role']=$this->usr_role;                 
                  $hash['login']=1;
            }
            else{
                $hash['login']=0;
            }
        }
        else{
            $hash['login']='notExists';
        }
         return $hash;                
	}   
        /**
         * 
         * @param type $jsPass -условие запроса
         * @return boolean
         */
    public function isValidPassword($jsPass){
            $entropy = new Entropy($this->transfer_salt,$this->transfer_iteration);
            if($entropy->getSaltedHash($this->usr_password)==$jsPass){
                return true;    
            }
            else{
                return false;
            }          
    	}
        /**
             * Активация юзера
             * @param type $db_salt
             */
            public function activate($db_salt){
            db::sql("UPDATE tbl_user SET userActive=1 WHERE dbSalt = _1");
            db::addParameters($db_salt);
            $result=db::execute();
            return $result;        
     }           
        /**
         * Функция виходу 
         * @return array
         */
	public function logout(){            
                unset($_SESSION['user']);
                unset($_COOKIE['email']);
                unset($_COOKIE['pass']);
                unset($_COOKIE['salt']);
                unset($_COOKIE['iteration']);
                setcookie('email', '', time()-1,'/');
                setcookie('pass', '', time()-1,'/');
                setcookie('salt', '', time()-1,'/');
                setcookie('iteration', '', time()-1,'/');
                /**/
                $hash['logout']=1;
                return $hash;
	} 
        /**
         * сброс пароля
         * @return array
         */
    public function resetPass() {
        if ($this->userExists(0)) {
            $newPass= rand(100, 100000);
            $this->dbEntropy=new Entropy($this->db_salt,$this->db_iteration);
            $pass=$this->dbEntropy->getSaltedHash($newPass);
        //    $pass=$this->dbEntropy->getSaltedHashReset($newPass,$this->db_iteration,  $this->db_salt);
            db::sql("UPDATE tbl_user  SET userPass=_1 WHERE userEmail=_2");
            $info['userPass']=$pass;            
            $info['userEmail']=$this->usr_email;
            db::addParameters($info);
            db::execute();
            UserEmail::emailPassReset($newPass,$this->usr_email);
            $hash['reset'] = 1;
        } else {
            $hash['reset'] = 0;
        }
        return $hash;
    }	
    
     //    $newPass= rand(100, 100000);
       //     $pass=$this->dbEntropy->getSaltedHash($newPass);
         //   $pass
      //      $this->emailPassReset();
    
    // Пока не используетса
    /**
     * Установка прав юзера
     * $role -условие(userRoll)
     * @param boolean 
     */
    public function setRights($role){
            db::sql("UPDATE tbl_user  SET userRole=_2 WHERE userEmail=_1");
            $info['userEmail']=$this->usr_email;            
            $info['userRole']=$role;
            db::addParameters($info);
            $result=db::execute();
            if($result){
                return true;
            }
            else{
                return false;
            }
    } 
    
    /**
     * удаление юзера
     * @param type $userId -условие запроса
     */
   public function dellUser($userId){
            $id=Clear::dataI($userId);
            if(isset($_SESSION['user']['id']) and $_SESSION['user']['id']!=$id){
                db::sql("DELETE FROM tbl_user WHERE userId='$id'");
                $result=db::execute();
                return $result;
            }
    } 

                
////////////////////////////////////////////////////////////////////////////////////////////ДОРАБОТАТЬ!!!
        /**
         * Сброс пароля
         * @param type $oldSalt
         * @param type $password
         * @param type $dbSalt
         * @param type $dbIteration
         * @return array
         */
	public function resetPassDb($oldSalt,$password,$dbSalt,$dbIteration){ //Функция добавления нового пользователя в базу,если работаем через AJAX  
            $oldSalt=Clear::dataS($oldSalt);
            $this->db_salt=Clear::dataS($dbSalt); 
            $this->usr_password=Clear::dataS($password);
            $this->db_iteration=Clear::dataS($dbIteration);
            $info=$this->userArrayToDb();
            db::sql("UPDATE tbl_user SET userPass=_2, dbSalt=_3, dbIteration=_4 WHERE dbSalt='$oldSalt'");
            db::addParameters($info);
            $result=db::execute();
            
            if($this->userExists()){
                 $hash['restore']=1;
            }
            else{
	            $hash['restore']=0;
            }           
           return $hash;
	}     
        /**
         * Функция добавления нового пользователя в базу,если работаем через AJAX
         * @param type $userId
         * @param type $password
         * @param type $dbSalt
         * @param type $dbIteration
         * @param type $email
         * @param type $role
         * @param type $username
         * @param type $active
         * @return array
         */
	public function updateUserDb($userId,$password,$dbSalt,$dbIteration,$email,$role,$username,$active){   
           $userId=Clear::dataI($userId);
            $this->usr_email=Clear::dataS($email);
            $this->db_salt=Clear::dataS($dbSalt); 
            $this->usr_password=Clear::dataS($password);
            $this->db_iteration=Clear::dataI($dbIteration);
            $this->usr_rights=Clear::dataI($role);               
            $this->usr_name=Clear::dataS($username);
            $this->usr_active=$active;
            if($this->usr_active){
               $this->usr_active=1; 
            }
            else{
                $this->usr_active=0;  
            }
            $info=$this->userArrayToDb();
            if($password!='')
            db::sql("UPDATE tbl_user SET userEmail=_1, userPass=_2, dbSalt=_3, dbIteration=_4, userRole=_5, userName=_6, userActive=_7 WHERE userId='$userId'");
            else
            db::sql("UPDATE tbl_user SET userEmail=_1, userRole=_5, userName=_6, userActive=_7 WHERE userId='$userId'");
            db::addParameters($info);
            $result=db::execute();
            
            
            if($this->userExists()){
                 $hash['restore']=1;
            }
            else{
	            $hash['restore']=0;
            }           
            return $hash; 
	}                  
//---------------------------------------------------------------------------
  
                          
}// END OF class User


?>