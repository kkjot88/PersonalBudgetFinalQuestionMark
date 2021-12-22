<?php

namespace App\Models;

use App\Config;

class Incomes extends Finances {
    public function __construct() {
        Parent::__construct();
        $this->tableName = Config::INCOMES_TABLE;
    }
}