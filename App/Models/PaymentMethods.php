<?php

namespace App\Models;

use App\Config;
use PDO;
use Core\Error;
use Exception;

class PaymentMethods extends \Core\Model {

    protected $methodsTableName = Config::PAYMENT_METHODS;
    protected $defaultsTableName = Config::PAYMENT_METHDOS_DEFAULTS;
    protected $relationsTableName = Config::USERS_PAYMENTMETHODS_RELATIONS;

    public function getMethodsTableName() {
        return $this->methodsTableName;
    }

    public function fetchAll() {
        $sql = "SELECT * 
                FROM $this->methodsTableName";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();

        $methods = $stmt->fetchAll();
        $methods = $this->formatMethodsAssoc($methods);

        return $methods;
    }

    public function setDefaults ($db, $user) {        
        if ($defaults = $this->fetchDefaults()) {            
            $insertedMethodsIds = $this->insertDefaultMethods($db, $defaults);
            $this->insertDefaultMethodsReferences($db, $defaults, $user->userid, $insertedMethodsIds);
            return true;
        } else {
            return false;
        }
    }

    protected function fetchDefaults() {   
        $sql = "SELECT * 
                FROM $this->defaultsTableName";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();        

        return $stmt->fetchAll();
    }

    protected function insertDefaultMethods($db, $defaults) {
        $sql = "INSERT INTO $this->methodsTableName (method)
                VALUES (:method)";

        $stmt = $db->prepare($sql);

        $insertedMethodsIds = [];

        foreach ($defaults as $method) {
            $stmt->bindValue(':method', $method['method'], PDO::PARAM_STR);
            try {                
                $stmt->execute();
                $insertedMethodsIds[$method['method']] = $db->lastInsertId();
            } catch (Exception $e) {}
        }
        
        return $insertedMethodsIds;
    }

    protected function insertDefaultMethodsReferences ($db, $defaults, $userid, $insertedMethodsIds) {
        $sql = "INSERT INTO $this->relationsTableName (userid, methodid)
                VALUES (:userid, :methodid)";

        $stmt = $db->prepare($sql);   

        foreach ($defaults as $default) {
            if (array_key_exists($default['method'], $insertedMethodsIds)) {
                $methodId = $insertedMethodsIds[$default['method']];
            } else {
                $methodId = $this->fetchMethodIdByName($default['method']);
            }
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT );
            $stmt->bindValue(':methodid', $methodId, PDO::PARAM_INT);
            try {
                $stmt->execute();
            } catch (Exception $e) {}
        }
    }

    public function fetchMethodIdByName($method) {
        $sql = "SELECT methodid
                FROM $this->methodsTableName
                WHERE method = :method";      
                
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':method', $method, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();        

        return $stmt->fetch()['methodid'] ?? false;
    }

    public function formatMethodsAssoc($methods) {
        if ($methods) {
            foreach($methods as $row => $value) {
                $methodsFormated[$value['methodid']] = $value['method'];
            }
            return $methodsFormated;
        }
        return $methods;
    }
}