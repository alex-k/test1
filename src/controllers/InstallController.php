<?php
class InstallController extends Controller {

    public function installAction() {
        $view=new ErrorView;

        $wpservice=new WebpageService;
        $wpservice->install();

        $cmntservice=new CommentService;
        $cmntservice->install();

        $view->setMessage("tables installed");

        return $view;
    }
    
}
