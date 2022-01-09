<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'personal_budget';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const SHOW_ERRORS = true;
    
    const SECRET_KEY = '58C1DE2D452CBC5C2BB183D4C171A';

    //TABLES
    const INCOMES_TABLE = 'incomes';
    const EXPENSES_TABLE = 'expenses';

    const EXPENSES_CATEGORIES = 'expensecategories';
    const EXPENSES_CATEGORIES_DEFAULTS = 'expensecategories_default';
    const USERS_EXPENSECATEGORIES_RELATIONS = 'users_expensecategories';

    const INCOMES_CATEGORIES = 'incomecategories';
    const INCOMES_CATEGORIES_DEFAULTS = 'incomecategories_default';
    CONST USERS_INCOMESCATEGORIES_RELATIONS = 'users_incomecategories';

    const PAYMENT_METHODS = 'paymentmethods';
    const PAYMENT_METHDOS_DEFAULTS = 'paymentmethods_default';
    const USERS_PAYMENTMETHODS_RELATIONS = 'users_paymentmethods';    

    // method implementation needs to be added to Balance model along with period
    const DATE_PERIODS =   ["Bieżący miesiąc" => "getCurrentMonthFinances",
                            "Poprzedni miesiąc" => "getPreviousMonthFinances",
                            "Bieżący rok" => "getCurrentYearFinances",
                            "Niestandardowy" => "getCustomPeriodFinances"];
}
