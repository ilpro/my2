<?php

class Time{

  public static $prev_time = 0;

  public static function timestampToTimezone($timestamp){

      $timestamp =  strtotime($timestamp);

        $dt = new DateTime();
        $dt->setTimestamp($timestamp);
        $dt->setTimezone(new DateTimeZone("Europe/Kiev"));

        return $dt->format('H:i d.m.Y');
    }

  public static function microtime()
  {
      list($usec, $sec) = explode(" ", microtime());
      $time = (float)$usec + (float)$sec;

      $difference =  $time - self::$prev_time;

      self::$prev_time = $time;

      return substr(round($time,3),-6). '('.round($difference,3).')';
  }

}  