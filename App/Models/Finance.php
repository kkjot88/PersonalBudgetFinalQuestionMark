<?php

namespace App\Models;

use Exception;

use Core\Error;

use PDO;

abstract class Finance extends \Core\Model {

    protected $tableName;
    protected $categories;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function Add ($userid) {
        $methodRow = '';
        $methodValue = '';
        if (isset($this->method)) {
            $methodRow = 'methodid,';
            $methodValue = ':methodid,';
        }
        
        $sql = "INSERT INTO $this->tableName (userid, amount, date, $methodRow categoryid, comment)
                VALUES (:userid, :amount, :date, $methodValue :categoryid, :comment)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        echo $userid.'</br>';        
        echo $this->amount.'</br>';
        echo $this->date.'</br>';
        echo $this->categories->fetchCategoryIdByName($this->category).'</br>';
        echo $this->comment.'</br>';

        $categoryId = $this->categories->fetchCategoryIdByName($this->category);

        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);        
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        if (isset($this->method)) {
            $methodId = (new PaymentMethods())->fetchMethodIdByName($this->method);
            $stmt->bindValue(':methodid', $methodId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':categoryid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':comment',$this->comment, PDO::PARAM_STR);

        return $stmt->execute();
    }
}