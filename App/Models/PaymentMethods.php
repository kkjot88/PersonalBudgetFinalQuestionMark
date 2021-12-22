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

    public function setDefaults ($db, $user) {        
        if ($defaults = $this->fetchDefaults()) {            
            $this->insertDefaultMethods($db, $defaults);
            $this->insertDefaultMethodsReferences($db, $defaults, $user->userid);
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

        foreach ($defaults as $category) {
            $stmt->bindValue(':method', $category['method'], PDO::PARAM_STR);
            try {                
                $stmt->execute();
            } catch (Exception $e) {}
        }
    }

    protected function insertDefaultMethodsReferences ($db, $defaults, $userid) {
        $sql = "INSERT INTO $this->relationsTableName (userid, methodid)
                VALUES (:userid, :methodid)";

        $stmt = $db->prepare($sql);   

        foreach ($defaults as $default) {
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT );
            $stmt->bindValue(':methodid', $this->fetchMethodIdByName($default['method']), PDO::PARAM_INT);
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


}