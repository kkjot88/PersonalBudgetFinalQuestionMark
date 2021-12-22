<?php

namespace App\Models;

use Core\Error;

use App\Incomes;
use App\Expenses;

use App\Config;

class Balance extends \Core\Model {

    public $incomeCategories;
    public $expenseCategories;
    public $datePeriodCategories;

    public function __construct()
    {
        $this->incomeCategories = new IncomeCategories();
        $this->expenseCategories = new ExpenseCategories();
        $this->datePeriodCategories = Config::DATE_PERIOD_CATEGORIES;
    }

}