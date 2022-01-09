<?php

namespace App\Models;

use App\Auth;
use Core\Error;

use App\Incomes;
use App\Expenses;

use App\Config;

use App\PreviousMonth;
use App\CurrentMonth;
use App\Period;
use App\Date;
use PDO;

class Balance extends \Core\Model {

    protected $incomesTable = Config::INCOMES_TABLE;
    protected $incomeCategories = Config::INCOMES_CATEGORIES;
    protected $expensesTable = Config::EXPENSES_TABLE;
    protected $expenseCategories = Config::EXPENSES_CATEGORIES;    
    protected $datePeriods = Config::DATE_PERIODS;

    protected $userid;
    protected $postData;

    public function __construct($postData)
    {
        $this->userid = Auth::getUser()->userid;
        $this->postData = $postData;
    }

    public function getFinancesByDates() {
        if ($this->postData) {            
            $method = $this->getMethodByPeriodText($this->postData["period"]);
            return $this->$method();
        }
        return false;
    }
    
    public function getDatePeriodsTexts() {
        $datePeriodsTexts = [];
        foreach ($this->datePeriods as $periodText => $methodCall) {
            array_push($datePeriodsTexts, $periodText);
        }
        return $datePeriodsTexts;
    }    
    
    protected function getMethodByPeriodText ($periodText) {
        return $this->datePeriods[$periodText];
    }
    
    protected function getGivenPeriodFinances($financesTable, $financesCategory, $dateFrom, $dateTo) {

        $methodTable = Config::PAYMENT_METHODS;

        $sql = "SELECT
                    f.amount AS amount,
                    f.date AS date,
                    f.comment AS comment," 
                    .(($financesTable == Config::EXPENSES_TABLE) ? "m.method AS method," : "").
                    "fc.category AS category                    
                FROM $financesTable f
                LEFT OUTER JOIN $financesCategory fc
                USING (categoryid)"
                .
                (($financesTable == Config::EXPENSES_TABLE) ? "LEFT OUTER JOIN $methodTable m
                USING (methodid)" : "")                
                ."
                WHERE f.userid = :userid AND f.date >= :dateFrom AND f.date <= :dateTo";


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':userid', $this->userid, PDO::PARAM_INT);        
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);        
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);        

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    protected function getGivenPeriodFinancesSum($financesTable, $financesCategory, $dateFrom, $dateTo) {        
        $sql = "SELECT 
                    fc.category AS category,
                    SUM(f.amount) AS amount
                FROM $financesTable f
                INNER JOIN $financesCategory fc
                USING (categoryid)
                WHERE f.userid = :userid AND f.date >= :dateFrom AND f.date <= :dateTo
                GROUP BY f.categoryid 
                ORDER BY SUM(f.amount) DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':userid', $this->userid, PDO::PARAM_INT);        
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);        
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);        

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $this->formatFinancesSumArray($stmt->fetchAll());
    }    

    protected function formatFinancesSumArray($finances) {
        if ($finances) {
            foreach ($finances as $finance) {
                $IncomesFormated[$finance['category']] = $finance['amount'];
            }
            return $IncomesFormated;
        }
        return $finances;
    }

    protected function appendFinancesWithPostData($incomes, $incomesSum, $expenses, $expensesSum, $dateFrom, $dateTo) {
        $financesWithPostData = [];
        $financesWithPostData['incomes'] = $incomes;
        $financesWithPostData['incomesSum'] = $incomesSum;
        $financesWithPostData['expenses'] = $expenses;
        $financesWithPostData['expensesSum'] = $expensesSum;
        $this->postData['dateFrom'] = $dateFrom;
        $this->postData['dateTo'] = $dateTo;
        $financesWithPostData['post'] = $this->postData;
        return $financesWithPostData;
    }

    protected function getCurrentMonthFinances() {
        $dateFrom = Date::getFirstDayOfCurrentMonth();
        $dateTo = Date::getLastDayOfCurrentMonth();
        $incomes = $this->getGivenPeriodFinances($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $incomesSum = $this->getGivenPeriodFinancesSum($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $expenses = $this->getGivenPeriodFinances($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        $expensesSum = $this->getGivenPeriodFinancesSum($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        return $this->appendFinancesWithPostData($incomes, $incomesSum, $expenses, $expensesSum, $dateFrom, $dateTo);
    }

    public function getPreviousMonthFinances() {        
        $dateFrom = Date::getFirstDayOfPreviousMonth();
        $dateTo = Date::getLastDayOfPreviousMonth();
        $incomes = $this->getGivenPeriodFinances($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $incomesSum = $this->getGivenPeriodFinancesSum($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $expenses = $this->getGivenPeriodFinances($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        $expensesSum = $this->getGivenPeriodFinancesSum($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        return $this->appendFinancesWithPostData($incomes, $incomesSum, $expenses, $expensesSum, $dateFrom, $dateTo);
    }

    public function getCurrentYearFinances() {
        $dateFrom = Date::getFirstDayOfCurrentYear();
        $dateTo = Date::getLastDayOfCurrentYear();
        $incomes = $this->getGivenPeriodFinances($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $incomesSum = $this->getGivenPeriodFinancesSum($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $expenses = $this->getGivenPeriodFinances($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        $expensesSum = $this->getGivenPeriodFinancesSum($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        return $this->appendFinancesWithPostData($incomes, $incomesSum, $expenses, $expensesSum, $dateFrom, $dateTo);
    }

    public function getCustomPeriodFinances() {
        $dateFrom = $this->postData['dateFrom'];
        $dateTo = $this->postData['dateTo'];
        $incomes = $this->getGivenPeriodFinances($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $incomesSum = $this->getGivenPeriodFinancesSum($this->incomesTable, $this->incomeCategories, $dateFrom, $dateTo);
        $expenses = $this->getGivenPeriodFinances($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        $expensesSum = $this->getGivenPeriodFinancesSum($this->expensesTable, $this->expenseCategories, $dateFrom, $dateTo);
        return $this->appendFinancesWithPostData($incomes, $incomesSum, $expenses, $expensesSum, $dateFrom, $dateTo);
    }

}