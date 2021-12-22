<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

use \Core\Error;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;

class Signup extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Offline/Signup/new.html');
    }

    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $this->redirect('/signup/success');
        } else {
            View::renderTemplate('Offline/Signup/new.html', [
                'user' => $user
            ]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('Offline/Signup/success.html');
    }

    public function activateAction() {
        //User::activate($this->route_params['token']);
        $this->redirect('/signup/activated');
    }

    public function activatedAction() {
        View::renderTemplate('Offline/Signup/activated.html');
    }
}
