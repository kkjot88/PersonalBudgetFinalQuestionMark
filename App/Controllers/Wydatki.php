<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Expense;
use \App\Models\ExpenseCategories;
use Core\Error;

use App\Flash;
use App\Models\PaymentMethods;

class Wydatki extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
        $this->expense = new Expense($_POST);
        $this->methods = new PaymentMethods();
        $this->expenseCategories = new ExpenseCategories();
    }

    public function indexAction () {
        View::renderTemplate('Online/Budget/wydatki.html', [
            'user' => $this->user,
            'methods' => $this->methods->fetchAll(),
            'categories' => $this->expenseCategories->fetchAll(),
            'finance' => $this->expense
        ]);
        Error::console($this->methods->fetchAll());
    }  

    public function addAction () {   
        if ($_POST) {
            if ($this->expense->Add($this->user->userid)) {
                Flash::addMessage('Wydatek dodany pomyślnie');
                $this->redirect('/wydatki/index');
            } else {
                $this->user->errors[] = $this->expense->error;
                $this->indexAction();
            }
        } else {
            $this->redirect('/wydatki/index');
        }
    }
}