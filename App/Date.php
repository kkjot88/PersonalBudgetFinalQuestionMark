<?php

namespace App;

class Date {
    public static function getFirstDayOfCurrentMonth() {  
        return date('Y-m-01', strtotime("m"));
    }

    public static function getLastDayOfCurrentMonth() {
        return date('Y-m-t', strtotime("m"));
    }

    public static function getFirstDayOfPreviousMonth() {
        return date('Y-m-d', strtotime("First day of -1 month"));
    }

    public static function getLastDayOfPreviousMonth() {
        return date('Y-m-d', strtotime("last day of -1 month"));
    }

    public static function getFirstDayOfCurrentYear() {
        return date('Y-01-01', strtotime("Y"));
    }

    public static function getLastDayOfCurrentYear() {
        return date('Y-12-t', strtotime("Y"));
    }
}