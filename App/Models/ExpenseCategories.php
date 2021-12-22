<?php

namespace App\Models;

use App\Config;

class ExpenseCategories extends Categories {
    public function __construct()
    {
        $this->categoriesTableName = Config::EXPENSES_CATEGORIES;
        $this->defaultsTableName = Config::EXPENSES_CATEGORIES_DEFAULTS;
        $this->relationsTableName = Config::USERS_EXPENSECATEGORIES_RELATIONS;
    }
}