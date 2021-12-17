<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

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
}