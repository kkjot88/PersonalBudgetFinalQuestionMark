<?php

namespace App\Models;

use Exception;

abstract class Finances extends \Core\Model {

    protected $tableName;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function Add () {
        echo $this->amount.'</br>';        
        echo $this->date.'</br>';
        echo $this->tableName.'</br>';
        
        $sql = "INSERT INTO $this->tableName (userid, amount, date, categoryid, comment)
                VALUES (:userid, :amount, :date, :categoryid, :comment)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
/*
        foreach ($defaults as $category) {
            $stmt->bindValue(':category', $category['category'], PDO::PARAM_STR);
            try {                
                $stmt->execute();
            } catch (Exception $e) {}
        }*/
    }
}