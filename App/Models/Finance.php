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

        $amount = $this->validateAmount($this->amount);

        if ($amount) {
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

            $categoryId = $this->categories->fetchCategoryIdByName($this->category);

            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);        
            $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            if (isset($this->method)) {
                $methodId = (new PaymentMethods())->fetchMethodIdByName($this->method);
                $stmt->bindValue(':methodid', $methodId, PDO::PARAM_INT);
            }
            $stmt->bindValue(':categoryid', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':comment',$this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;        
    }

    protected function validateAmount($amountInput) {
        $errorMsg = "Invalid amount format";        

        try {            
            $amountInput = str_replace([' ',','],['','.'], $amountInput);
            $amountInput = bcdiv($amountInput,1,2);
        } catch (\Throwable $e) { 
            $this->error = $errorMsg;             
            return false;  
        }       

        if (preg_match('/^\d+[\.\,]\d{0,2}$/', $amountInput)) {
            if (strlen($amountInput) < 67) {                
                return $amountInput;
            } else {
                $this->error = "Amount to large";
            }
            return false;
        }
        $this->error = $errorMsg;
        return false;
    }
}