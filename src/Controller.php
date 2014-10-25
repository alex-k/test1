<?php

abstract class Controller {
    protected $request;

    public function __construct(Request $request) {
        $this->request=$request;
    }

    public function notfoundAction() {
        $response=new ErrorView;

        //$response->setCode(RESPONSE_NOTIMPLEMENTED);
        $response->setCode(RESPONSE_BADREQUEST);
        $response->setMessage(get_class($this).': action not implemented');

        return $response;
    }

}
