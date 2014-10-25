<?php
class WebpagesController extends Controller {

    public function getAction() {
        $service=new WebpageService;
        $pages=array();
        foreach ($service->listAll() as $page) {
            $pages[]=$page->getValues();
        }
        
        $view=new WebpageslistView;
        $view->setData('items',$pages);
        return $view;
    }
    
}
