<?php

class Settings {

    protected $table = '`tbl_settings`';

    function __construct() {
        
    }

    //Проверяем чи есть в бд страница настройок 
    public function getSettings() {
        db::sql("SELECT * FROM " . $this->table);
        $query_res = db::query();

        if (!empty($query_res))
            return $query_res;
        else
            return false;
    }

    //редактирование страници настройок
    public function update($data) {
        //  var_dump($data);die;
        if ($this->getSettings() == false) {
            db::sql('INSERT `tbl_settings` set `settingsId`=1 ');
            db::execute();
        }
        $parser = $this->checkboxValue($data['settingsParser']);
        $settingsTechWork = $this->checkboxValue($data['settingsTechWork']);
        db::sql("UPDATE tbl_settings SET "
                . "`settingsParser`=_1,"
                . "`settingsParserInterval`=_2,"
                . "`settingsParserDelete`=_3,"
                . "`settingsSiteName`=_4,"
                . "`settingsSiteTitle`=_5,"
                . "`settingsDescription`=_6,"
                . "`settingsTimeZona`=_7,"
                . "`settingsEmail`=_8,"
                . "`settingsPageFb`=_9,"
                . "`settingsPageTw`=_10,"
                . "`settingsPageVk`=_11,"
                . "`settingsPageGl`=_12,"
                . "`settingsPageIn`=_13,"
                . "`settingsPageOk`=_14,"
                . "`settingsPageYou`=_15,"
                . "`settingsPageRss`=_16,"
             //   . "`settingsCounter`=_12,"
                . "`settingsTechWork`=_17,"
                . "`settingsCopyText`=_18, "
                 . "`settingsPasteByCursor`=_19 "
                . " WHERE `settingsId`=1");

        //$data['settingsCounter'], -пока закоментовано счетчик и ссилки
        $info = array($parser, $data['settingsParserInterval'], $data['settingsParserDelete'], $data['settingsSiteName'], $data['settingsSiteTitle'], $data['settingsDescription'], 
                      $data['settingsTimeZona'], $data['settingsEmail'], $data['settingsPageFb'], $data['settingsPageTw'], $data['settingsPageVk'],$data['settingsPageGl'],$data['settingsPageIn'],
                      $data['settingsPageOk'],$data['settingsPageYou'],$data['settingsPageRss'], $settingsTechWork, $data['settingsCopyText'],$data['settingsPasteByCursor']);
        db::addParameters($info); 
        $result = db::execute();
        if (isset($result)) {
            return true;
        } else {
            return false;
        }
    }

    //узнаем значения чекбокса
    public function checkboxValue($value) {
        if ($value == 'true') {
            return 1;
        } else
            return 0;
    }

    //узнаем чи есть сохранение картинки
    public function getSettingImgsHTML() {

        global $ini;
        $result = [];
        $names = array('watermark.png', 'soclogo.jpg');
        foreach ($names as $name) {
            $filename = $ini['path.media'] . "settings/" . $name;
            if (file_exists($filename)) {
                $result[] = $name;
            }
        }
        return $result;
    }

}
