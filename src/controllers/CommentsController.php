<?php
class CommentsController extends Controller {

    public function getAction() {
        $view=new CommentslistView;
        $pageservice=new WebpageService;

        $page=$pageservice->getPageByUrl($this->request->getData('url'));
        if (!$page) {
            $page=new WebpageModel;
            $page->setUrl($url);
            $pageservice->submitNewItem($page);
        }

        $view->setData('page',$page);

        $commentsservice=new CommentService;
        $comments=$commentsservice->getCommentsForPage($page);

        $view->setData('comments',$comments);

        return $view;
    }

    public function postAction() {
        $pageservice=new WebpageService;

        $page=$pageservice->getPageByUrl($this->request->getData('url'));

        if (!$page) {
            $view=new ErrorView;
            $view->setCode(RESPONSE_BADREQUEST);
            $view->setMessage(get_class($this).': url not registered');
            return $view;
        }

        $comment = new CommentModel;
        $comment->setValues($this->request->getData());
        $comment->setPage($page);
        //$comment->setDate('now');

        $commentsservice=new CommentService;

        try {
            $commentsservice->submitNewItem($comment);
        } catch (ModelValidateException $e) {
            $view=new ErrorView;
            $view->setCode(RESPONSE_BADREQUEST);
            $view->setMessage($e->getMessage());
            $view->setData('errors',$comment->getValidateErrors());
            return $view;
        }
        $view=new CommentView;
        $view->setData('comment',$comment);

        return $view;

    }
    
}
