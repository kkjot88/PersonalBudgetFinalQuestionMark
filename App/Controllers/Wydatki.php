<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Expenses;

use Core\Error;

class Wydatki extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function indexAction () {
        View::renderTemplate('Online/Budget/wydatki.html', [
            'user' => $this->user
        ]);
    }  

    public function addAction () {   
        echo "booom";
        
        $expenses = new Expenses($_POST);
        exit();
        $expenses->Add();
        
        Error::console($expenses);
        exit();
    }
}