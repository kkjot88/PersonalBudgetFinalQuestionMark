<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Expense;
use App\Models\ExpenseCategories;
use Core\Error;

class Wydatki extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function indexAction () {
        View::renderTemplate('Online/Budget/wydatki.html', [
            'user' => $this->user,
            'categories' => (new ExpenseCategories())->fetchAll()
        ]);
    }  

    public function addAction () {   
        
        $expense = new Expense($_POST);
        $expense->Add($this->user->userid);
        
        Error::console($expense);
        exit();
    }
}