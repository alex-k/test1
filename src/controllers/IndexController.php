<?php

/**
    Default (index) controller

**/

class IndexController extends Controller {

    public function getAction() {
        $this->response=new IndexView;
        $this->response->setMessage('index page');
        return $this->response;
    }
    
}
