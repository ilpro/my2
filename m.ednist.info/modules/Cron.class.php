<?php

class Cron {

    public function checkPreparedNews()
    {
        $news = new NewsAdmin();
        $news->updateStatuses();
    }
    
    
}