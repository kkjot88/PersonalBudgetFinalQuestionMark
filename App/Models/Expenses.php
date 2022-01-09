<?php

namespace App\Models;

use App\Auth;
use App\Config;

class Expenses extends Finances {
    public function __construct($userId, $data = []) {
        Parent::__construct($data);        
        $this->tableName = Config::EXPENSES_TABLE;
        $this->categories = new ExpenseCategories();
        $this->methods = new PaymentMethods();
        $this->userId = $userId;
    }
}