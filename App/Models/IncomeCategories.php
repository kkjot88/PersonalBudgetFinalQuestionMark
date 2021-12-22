<?php

namespace App\Models;

class IncomeCategories extends Categories {

    public function __construct()
    {
        $this->categoriesTableName = 'incomecategories';
        $this->defaultsTableName = 'incomecategories_default';
        $this->relationsTableName = 'users_incomecategories';
    }

}