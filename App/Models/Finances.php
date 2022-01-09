<?php

namespace App\Models;

use Exception;

use Core\Error;
use App\Config;

use PDO;

abstract class Finances extends \Core\Model {

    protected $tableName;
    protected $categories;
    protected $methods;
    protected $userId;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function Add () {

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

            $stmt->bindValue(':userid', $this->userId, PDO::PARAM_INT);        
            $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            if (isset($this->method)) {
                $methodId = $this->methods->fetchMethodIdByName($this->method);
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
    
    public function getFinancesForGivenPeriod($dateFrom, $dateTo) {       

        $methodTable = ($this->tableName == Config::EXPENSES_TABLE) ? $this->methods->getMethodsTableName() : "";
        $returnMethodValueSQL = "m.method AS method,";
        $joinMethodTableSQL = "LEFT OUTER JOIN $methodTable m USING (methodid)";
        
        $sql = "SELECT ".
                    (($this->tableName == Config::EXPENSES_TABLE) ? "f.expenseid AS id," : "f.incomeid AS id,").
                    "fc.category AS category,
                    f.amount AS amount,"
                    .(($this->tableName == Config::EXPENSES_TABLE) ? $returnMethodValueSQL : "").
                    "f.date AS date,                    
                    f.comment AS comment                 
                FROM $this->tableName f
                LEFT OUTER JOIN ".$this->categories->getCategoriesTableName()." fc
                USING (categoryid)"
                .
                (($this->tableName == Config::EXPENSES_TABLE) ? $joinMethodTableSQL : "")                
                ."
                WHERE f.userid = :userid AND f.date >= :dateFrom AND f.date <= :dateTo";


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':userid', $this->userId, PDO::PARAM_INT);        
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);        
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);        

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSumOfEachFinanceCategory($dateFrom, $dateTo) {
        $sql = "SELECT 
                    fc.category AS category,
                    SUM(f.amount) AS amount
                FROM $this->tableName f
                INNER JOIN ".$this->categories->getCategoriesTableName()." fc
                USING (categoryid)
                WHERE f.userid = :userid AND f.date >= :dateFrom AND f.date <= :dateTo
                GROUP BY f.categoryid 
                ORDER BY SUM(f.amount) DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':userid', $this->userId, PDO::PARAM_INT);        
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);        
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);        

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $this->formatFinancesSumArray($stmt->fetchAll());
    }

    protected function formatFinancesSumArray($finances) {
        if ($finances) {
            foreach ($finances as $finance) {
                $IncomesFormated[$finance['category']] = $finance['amount'];
            }
            return $IncomesFormated;
        }
        return $finances;
    }
}