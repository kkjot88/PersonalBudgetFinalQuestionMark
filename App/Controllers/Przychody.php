<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \Core\Error;
use App\Models\Incomes;
use App\Models\PaymentMethods;

class Przychody extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function indexAction () {        
        View::renderTemplate('Online/Budget/przychody.html', [
            'user' => $this->user
        ]);
    }

    public function addAction () {

        $incomes = new Incomes($_POST);
        $incomes->Add();

        Error::console($incomes);
        exit();
    }
}