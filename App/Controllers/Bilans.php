<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

use \App\Mail;

use \Core\Error;

class Bilans extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function indexAction () {
        View::renderTemplate('Online/Budget/bilans.html', [
            'user' => $this->user
        ]);
    }  
}