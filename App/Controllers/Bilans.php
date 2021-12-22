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
        $this->balance = new Balance();
    }

    public function indexAction () {
        View::renderTemplate('Online/Budget/bilans.html', [
            'user' => $this->user,
            'datePeriodCategories' => $this->balance->datePeriodCategories
        ]);
        Error::console($this->balance);
        Error::console($this->user);
    }  
}