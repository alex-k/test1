<?php

class HtmlHelper extends Helper {
    protected function body() {
        $tplname=$this->view->getViewName().'.html';
        include APP_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$tplname;
    }

}
