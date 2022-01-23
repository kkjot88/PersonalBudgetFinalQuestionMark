<?php

namespace App\Models;

use App\Models\Incomes;
use App\Models\Expenses;

use App\Date;

class Balance extends \Core\Model {

    protected $userId;

    protected $incomes;
    protected $expenses;

    protected $givenPeriodFinances; 

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->incomes = new Incomes($this->userId);
        $this->expenses = new Expenses($this->userId);
    }

    protected function appendWithGivenPeriodFinances() {
        $dateFrom = $this->givenPeriodFinances['dateFrom'];
        $dateTo = $this->givenPeriodFinances['dateTo'];
        $incomes = $this->incomes->getFinancesForGivenPeriod($dateFrom, $dateTo);
        $expenses = $this->expenses->getFinancesForGivenPeriod($dateFrom, $dateTo);
        $sumOfEachIncomeCategory = $this->incomes->getSumOfEachFinanceCategory($dateFrom, $dateTo);
        $sumOfEachExpenseCategory = $this->expenses->getSumOfEachFinanceCategory($dateFrom, $dateTo);
        $balance = [
            'incomes' => $incomes,
            'expenses' => $expenses,
            'sumOfEachIncomeCategory' => $sumOfEachIncomeCategory,
            'sumOfEachExpenseCategory' => $sumOfEachExpenseCategory
        ];
        $this->givenPeriodFinances['balance'] = $balance;
    }

    public function getCurrentMonthFinances ($postData) {
        $this->givenPeriodFinances = [
            'dateFrom' => Date::getFirstDayOfCurrentMonth(),
            'dateTo' => Date::getLastDayOfCurrentMonth(),
            'datePickersDisabled' => true
        ];
        
        $this->appendWithGivenPeriodFinances();

        return $this->givenPeriodFinances;
    }

    public function getPreviousMonthFinances ($postData) {
        $this->givenPeriodFinances = [
            'dateFrom' => Date::getFirstDayOfPreviousMonth(),
            'dateTo' => Date::getLastDayOfPreviousMonth(),
            'datePickersDisabled' => true
        ];
        
        $this->appendWithGivenPeriodFinances();

        return $this->givenPeriodFinances;
    }

    public function getCurrentYearFinances ($postData) {
        $this->givenPeriodFinances = [
            'dateFrom' => Date::getFirstDayOfCurrentYear(),
            'dateTo' => Date::getLastDayOfCurrentYear(),
            'datePickersDisabled' => true
        ];
        
        $this->appendWithGivenPeriodFinances();

        return $this->givenPeriodFinances;
    }

    public function getCustomPeriodFinances ($postData) {
        $this->givenPeriodFinances = [ 
            'datePickersDisabled' => false
        ];
                                        
        if (isset($postData['dateFrom']) && isset($postData['dateTo'])) {
            $this->givenPeriodFinances['dateFrom'] = $postData['dateFrom'];
            $this->givenPeriodFinances['dateTo'] = $postData['dateTo'];            
            $this->appendWithGivenPeriodFinances();
        }

        return $this->givenPeriodFinances;
    }
}