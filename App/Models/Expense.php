<?php

namespace App\Models;

use App\Config;

class Expense extends Finance {
    public function __construct($data = []) {
        Parent::__construct($data);        
        $this->tableName = Config::EXPENSES_TABLE;
        $this->categories = new ExpenseCategories();
    }
}