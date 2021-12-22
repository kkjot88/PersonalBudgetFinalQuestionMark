<?php

namespace App\Models;

use App\Config;

class IncomeCategories extends Categories {
    public function __construct()
    {
        $this->categoriesTableName = Config::INCOMES_CATEGORIES;
        $this->defaultsTableName = Config::INCOMES_CATEGORIES_DEFAULTS;
        $this->relationsTableName = Config::USERS_INCOMESCATEGORIES_RELATIONS;
    }
}