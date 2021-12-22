<?php

namespace App\Models;

use \App\Token;
use PDO;

class RememberedLogin extends \Core\Model {
    public static function findByToken($token) {
        $token = new Token($token);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM remembered_logins 
                WHERE tokenhash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();;
    }

    public function getUser() {
        return User::findByID($this->userid);
    }

    public function hasExpired() {
        return strtotime($this->expiresat) < time();
    }

    public function delete() {
        $sql = 'DELETE FROM remembered_logins
                WHERE tokenhash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $this->tokenhash, PDO::PARAM_STR);

        $stmt->execute();
    }
}