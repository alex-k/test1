This is a solution for the "comments system" task implemented by Alexei Kochetov.


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

To run this script under Apache web server please be ensured you have .htaccess files enabled and rewrite module installed.
If you run nginx web server you may use config provided below.

    server {
        listen   80;
        server_name test1.phpbee.org;


        location / {
            root   /home/www/test1.phpbee.org/public_html;
            index  index.php;
            if (!-e $request_filename) {
                rewrite  ^(.*)$  /index.php last;
            }
        }

        location ~ \.php$ {
            root   /home/www/test1.phpbee.org/public_html;
            if (!-f $request_filename) {
                rewrite  ^(.*)/(.+?)$  $1/ redirect;
            }
            fastcgi_pass   unix:/var/run/php5-fpm.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  /home/www/test1.phpbee.org/public_html$fastcgi_script_name;
            include fastcgi_params;
        }
    }
