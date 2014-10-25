<?php

class PagenotfoundController extends Controller{
    function getAction() {
        $response=new PagenotfoundView;
        $response->setMessage('404 page not found');
        return $response;
    }

}
