<?php
abstract class Helper {
    protected $view;
    protected $request;
    public function __construct(View $view, Request $request) {
        $this->view=$view;
        $this->request=$request;
    }
    protected function body() {
        return "";
    }
    protected function headers() {
        switch ($this->view->getCode()) {
            case RESPONSE_OK:
                header("HTTP/1.0 200 OK");
                break;
            case RESPONSE_CREATED:
                header("HTTP/1.0 201 Created");
                break;
            case RESPONSE_BADREQUEST:
                header("HTTP/1.0 400 Bad Request");
                break;
            case RESPONSE_NOTFOUND:
                header("HTTP/1.0 404 Not Found");
                break;
            case RESPONSE_NOTACCEPTABLE:
                header("HTTP/1.0 406 Not Acceptable");
                break;
            case RESPONSE_SERVERERROR:
                header("HTTP/1.0 500 Server Error");
                break;
            case RESPONSE_NOTIMPLEMENTED:
                header("HTTP/1.0 501 Not Implemented");
                break;
        }
    }
    public function display() {
        $this->headers();
        $this->body();

    }
}
