<?php

namespace App\Models;

use App\Auth;
use App\Config;

class Incomes extends Finances {    
    public function __construct($userId, $data = []) {
        Parent::__construct($data);
        $this->tableName = Config::INCOMES_TABLE;        
        $this->categories = new IncomeCategories();
        $this->userId = $userId;
    }
}