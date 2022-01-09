<?php

namespace App\Models;

use PDO;
use Core\Error;
use Exception;

abstract class Categories extends \Core\Model {

    protected $categoriesTableName;
    protected $defaultsTableName;
    protected $relationsTableName;

    public function getCategoriesTableName() {
        return $this->categoriesTableName;
    }
    
    public function fetchAll() {
        $sql = "SELECT * 
                FROM $this->categoriesTableName";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();

        $categories = $stmt->fetchAll();
        $categories = $this->formatCategoriesAssoc($categories);
        
        return $categories;
    }

    public function setDefaults($db, $user) {
        if ($defaults = $this->fetchDefaults()) {            
            $insertedDefaultCategories = $this->insertDefaultCategories($db, $defaults);
            $this->insertDefaultCategoriesReferences($db, $defaults, $user->userid, $insertedDefaultCategories);
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

        $insertedCategoriesIds = [];

        foreach ($defaults as $category) {
            $stmt->bindValue(':category', $category['category'], PDO::PARAM_STR);
            try {                                
                $stmt->execute();
                $insertedCategoriesIds[$category['category']] = $db->lastInsertId();
            } catch (Exception $e) {}
        }

        return $insertedCategoriesIds;
    }

    protected function insertDefaultCategoriesReferences ($db, $defaults, $userid, $insertedDefaultCategories = []) {
        $sql = "INSERT INTO $this->relationsTableName (userid, categoryid)
                VALUES (:userid, :categoryid)";    

        $stmt = $db->prepare($sql);   

        foreach ($defaults as $default) {
            if (array_key_exists($default['category'], $insertedDefaultCategories)) {
                $categoryId = $insertedDefaultCategories[$default['category']];
            } else {
                $categoryId = $this->fetchCategoryIdByName($default['category']);
            }
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT );
            $stmt->bindValue(':categoryid', $categoryId, PDO::PARAM_INT);
            try {
                $stmt->execute();
            } catch (Exception $e) {}
        }
    }

    public function fetchCategoryIdByName($category) {
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

    public function formatCategoriesAssoc($categories) {
        if ($categories) {
            foreach($categories as $row => $value) {
                $categoriesFormated[$value['categoryid']] = $value['category'];
            }
            return $categoriesFormated;
        }
        return $categories;
    }

    public function insertCategory($category) {

    }
    
}