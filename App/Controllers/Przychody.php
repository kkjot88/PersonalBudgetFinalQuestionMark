<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \Core\Error;

use App\Models\Incomes;
use App\Models\IncomeCategories;

use App\Flash;

class Przychody extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
        $this->incomes = new Incomes($this->user->userid, $_POST);
        $this->incomeCategories = new IncomeCategories();
    }

    public function indexAction () {        
        View::renderTemplate('Online/Budget/przychody.html', [
            'user' => $this->user,
            'categories' => $this->incomeCategories->fetchAll(),
            'finance' => $this->incomes
        ]);
    }

    public function addAction () {        
        if ($_POST) {            
            if ($this->incomes->Add($this->user->userid)) {                
                Flash::addMessage('Przychód dodany pomyślnie');
                $this->redirect('/przychody/index');                
            } else {
                $this->user->errors[] = $this->incomes->error;
                $this->indexAction();
            }
        } else {
            $this->redirect('/przychody/index');
        }
    }
}