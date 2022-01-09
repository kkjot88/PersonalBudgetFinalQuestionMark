<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

use \App\Config;

use App\Models\Balance;

class Bilans extends Authenticated {

    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
        $this->balanceFinal = new Balance($this->user->userid);
    }

    public function indexAction () {        
        View::renderTemplate('Online/Budget/bilans.html', [
            'user' => $this->user,
            'datePeriods' => Config::DATE_PERIODS_FINAL,                
        ]);                    
    }  

    public function datePeriodMethodCallAction () {     
        $methodCall = $_POST["periodMethodCall"];
        $returnData = $this->balanceFinal->$methodCall($_POST);
        echo json_encode($returnData);
    }
}