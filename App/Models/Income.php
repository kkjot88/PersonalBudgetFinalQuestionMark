<?php

namespace App\Models;

use App\Config;

class Income extends Finance {    
    public function __construct($data = []) {
        Parent::__construct($data);
        $this->tableName = Config::INCOMES_TABLE;        
        $this->categories = new IncomeCategories();
    }
}