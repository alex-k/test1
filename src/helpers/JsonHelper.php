<?php

class JsonHelper extends Helper {
    protected function headers() {
        parent::headers();
        header("Content-Type: text/json");
    }
    protected function body() {
        $data=$this->view->getData();

        array_walk_recursive($data,function(&$i) { 
                if (is_object($i) && is_a($i,'Model')) $i=$i->getValues();
            });

        if($this->view->getMessage()) $data['message']=$this->view->getMessage();
        echo json_encode($data);

    }

}
