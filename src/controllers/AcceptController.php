<?php

class AcceptController extends Controller {

    public function errorAction() {
        $response=new AccepterrorView;
        $response->setMessage('406 Not Acceptable');
        $response->setData('accept',array('text/json','application/json'));
        return $response;
    }

}
