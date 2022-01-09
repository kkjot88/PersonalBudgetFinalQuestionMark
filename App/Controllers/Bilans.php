<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

use \App\Mail;
use App\Models\Balance;
use \Core\Error;

class Bilans extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
        $this->balance = new Balance($_POST);
    }

    public function indexAction () {
        $FinancesInDatePeriod = $this->balance->getFinancesByDates();
        if ($_POST) {
            View::renderTemplate('Online/Budget/bilans.html', [
                'user' => $this->user,
                'datePeriodCategories' => $this->balance->getDatePeriodsTexts(),
                'incomes' => $FinancesInDatePeriod['incomes'],
                'incomesSum' => $FinancesInDatePeriod['incomesSum'],
                'expenses' => $FinancesInDatePeriod['expenses'],
                'expensesSum' => $FinancesInDatePeriod['expensesSum'],
                'postData' => $FinancesInDatePeriod['post']
            ]);

            Error::console($FinancesInDatePeriod['incomes']);
            Error::console($FinancesInDatePeriod['incomesSum']);
            Error::console($FinancesInDatePeriod['expenses']);
            Error::console($FinancesInDatePeriod['expensesSum']);
            Error::console($FinancesInDatePeriod['post']);

        } else {
            View::renderTemplate('Online/Budget/bilans.html', [
                'user' => $this->user,
                'datePeriodCategories' => $this->balance->getDatePeriodsTexts(),                
            ]); 
        }                
    }  
}