<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

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
}