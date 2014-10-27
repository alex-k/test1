This comment system provide beckend with REST api which allow users to post comment on a web page. Frontend is implemented on the javascript and uses XMLHttpRequest to communicate with backend. Users can add and answer comments anonymously. New comments are marked as unchecked unless user fill anti-spam form. I think that it's a good idea to allow user make an action as easy as possible avoiding them from terrible pre-checks. Please find sources attached in the zip file. You may find some links and howto below. 


to add comment script on your page please add this code into it



    <div id="Comments"> </div>
    <script type="text/javascript" src="http://test1.phpbee.org/js/comments.js"></script>
    <script type="text/javascript">
        Comments.comments();
    </script>


you may pass extra options on your need




    <div id="commentsplacefolder"> </div>

    <script type="text/javascript" src="http://test1.phpbee.org/js/comments.js"></script>
    <script type="text/javascript" >
        Comments.init({
                page_id: "ytwrweytrweyt",
                target: document.getElementById("commentsplacefolder"),
                data: "http://test1.phpbee.org/",
                data_list : "/comments/list.json?url=",
                data_post : "/comments/post.json?url=",
                tpl: "js/tpl",
                tpl_list : "/list.html",
                tpl_post : "/post.html",
                tpl_form : "/form.html"
                });
        Comments.comments();
    </script>

Backend Installation

You will need PHP 5 and MySQL to run backend on your server. Connection strings and passwords are stored into config.php file. You have to create database and access, all needed data tables will be created by the script automatically.

To run this script under Apache web server please be ensured you have rewrite module installed, .htaccess files enabled or manually add public_html/apache.conf to your apache.
If you run nginx web server you may use public_html/nginx.conf.
