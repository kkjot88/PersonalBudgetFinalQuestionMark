<?php

namespace App\Models;

class ExpenseCategories extends Categories {

    public function __construct()
    {
        $this->categoriesTableName = 'expensecategories';
        $this->defaultsTableName = 'expensecategories_default';
        $this->relationsTableName = 'users_expensecategories';
    }
}