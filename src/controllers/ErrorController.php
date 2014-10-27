<?php

class ErrorController extends Controller{
    function exceptionAction(Exception $e) {
        $response=new ErrorView;
        $response->setCode(RESPONSE_SERVERERROR);
        $response->setMessage($e->getMessage());
        return $response;
    }

}
