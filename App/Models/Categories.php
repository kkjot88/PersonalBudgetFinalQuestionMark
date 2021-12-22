<?php

namespace App\Models;

use PDO;
use Core\Error;
use Exception;

abstract class Categories extends \Core\Model {

    protected $categoriesTableName;
    protected $defaultsTableName;
    protected $relationsTableName;
    
    public function fetchAll() {
        $sql = "SELECT * 
                FROM $this->categoriesTableName";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();

        $categoriesRaw = $stmt->fetchAll();

        if ($categoriesRaw) {
            foreach($categoriesRaw as $row => $value) {
                $categories[$value['categoryid']] = $value['category'];
            }
            return $categories;
        }
        return $categoriesRaw;
    }

    public function setDefaults($db, $user) {
        if ($defaults = $this->fetchDefaults()) {            
            $this->insertDefaultCategories($db, $defaults);
            $this->insertDefaultCategoriesReferences($db, $defaults, $user->userid);
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

    protected function insertDefaultCategories($db, $defaults) {
        $sql = "INSERT INTO $this->categoriesTableName (category)
                VALUES (:category)";

        $stmt = $db->prepare($sql);

        foreach ($defaults as $category) {
            $stmt->bindValue(':category', $category['category'], PDO::PARAM_STR);
            try {                
                $stmt->execute();
            } catch (Exception $e) {}
        }
    }

    protected function insertDefaultCategoriesReferences ($db, $defaults, $userid) {
        $sql = "INSERT INTO $this->relationsTableName (userid, categoryid)
                VALUES (:userid, :categoryid)";

        $stmt = $db->prepare($sql);   

        foreach ($defaults as $default) {
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT );
            $stmt->bindValue(':categoryid', $this->fetchCategoryIdByName($default['category']), PDO::PARAM_INT);
            try {
                $stmt->execute();
            } catch (Exception $e) {}
        }
    }

    public function fetchCategoryIdByName($category) {

        Error::console($category);

        $sql = "SELECT categoryid
                FROM $this->categoriesTableName
                WHERE category = :category";      
                
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();        

        return $stmt->fetch()['categoryid'] ?? false;
    }

    // DOKONCZYC
    public function insertReference($user) {
        $sql = "SELECT categoryid
                FROM $this->relationsTableName
                WHERE userid = :userid";                

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userid', $user->userid, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();        

        return $stmt->fetchAll();
    } //////////////////////////////////////////////////////////    

    public function insertCategory($category) {

    }
}