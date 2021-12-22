<?php

namespace App\Models;

use App\Config;

class Expenses extends Finances {
    
    public function __construct($data = []) {
        Parent::__construct($data);        
        $this->tableName = Config::EXPENSES_TABLE;
    }
}