<?php

class HtmlHelper extends Helper {
    protected function body() {
        $tplname=$this->view->getViewName().'.html';
        include dirname(__DIR__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$tplname;
    }

}
