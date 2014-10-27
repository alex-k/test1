window.log =  function() { window.console && window.console.log && window.console.log.apply(window.console, arguments); }


var Comments = Comments || (function() {
    var page;
    var _args = { 
                    page_id: document.location.href, 
                    data : "http://test1.phpbee.org",
                    data_list : "/comments/list.json?url=",
                    data_post : "/comments/post.json?url=",
                    data_put  : "/comments/post.json?url=",
                    tpl: "http://test1.phpbee.org/js/tpl",
                    tpl_list : "/list.html",
                    tpl_comment : "/comment.html",
                    tpl_form : "/form.html",
                    tpl_form_verify : "/form_verify.html",
                    target: document.getElementById("Comments")
                };
    var init = function(args) {
        log("Comments.init");
        for(var key in args) { 
               if (args.hasOwnProperty(key)) {
                   _args[key]=args[key];
               }
        }

        _tplurl = {
            list: _args.tpl+_args.tpl_list,
            comment: _args.tpl+_args.tpl_comment,
            form: _args.tpl+_args.tpl_form,
            form_verify: _args.tpl+_args.tpl_form_verify
        }
        _tpl = {}

        _url= {
            list : _args.data+_args.data_list+_args.page_id,
            post : _args.data+_args.data_post+_args.page_id,
            put: _args.data+_args.data_put+_args.page_id
        }

    };
    var get_form_vars = function(form) {
            var post = {};
            for (k in form.elements) {
                var e = form.elements[k];
                post[e.name]=e.value;
            }
            return post;
    };
    var set_form_vars = function(target,data) {
                    for (k in data) {
                        //var inp=document.getElementsByName(k);
                        var inp=target.querySelectorAll('input[name="'+k+'"]');
                        for (i in inp) { inp[i].value=data[k]; }
                    }
    };

    var set_form_errors = function(form,data) {
                form.querySelector("#error").innerHTML=data.message;
                var spans=form.querySelectorAll(".fielderror") ;
                for(i in spans) {
                    spans[i].innerHTML="";
                }
                for (field in data.error) {
                    form.querySelector("#error_"+field).innerHTML="";
                    for (j in data.errors[field]) {
                        form.querySelector("#error_"+field).innerHTML+=data.errors[field][j]+" ";
                    }
                }
    };


    var make_url_links = function(text) {
            var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
            return text.replace(exp,"<a href='$1'>$1</a>"); 
    }

    var draw_form = function(tpl,data) {
            var doc= document.implementation.createHTMLDocument();
            doc.body.innerHTML = tpl;

            return doc.body.innerHTML;
    }

    var draw_list = function(tpl,data) {
            var doc= document.implementation.createHTMLDocument();
            doc.body.innerHTML = tpl;
            doc.getElementById("pageid").innerHTML=data.page.url;
            for (var id in data.comments) {
                doc.getElementById("commentsList").innerHTML+=draw_comment(_tpl['comment'],data.comments[id]);
            }
            return doc.body.innerHTML;

    }
    var draw_comment = function(tpl,c) {
            var doc= document.implementation.createHTMLDocument();
            doc.body.innerHTML = tpl;
            doc.getElementById("id").innerHTML=c.id;
            doc.getElementById("name").innerHTML=c.name;
            doc.getElementById("email").innerHTML=c.email;
            doc.getElementById("text").innerHTML=make_url_links(c.text);
            doc.getElementById("date").innerHTML=c.date;
            for (var id in c.replies) {
                var r=c.replies[id];
                doc.getElementById("replies").innerHTML+=draw_comment(tpl,r);
            }
            return doc.body.innerHTML;
    }

    return {
                init : function(args) {
                    init(args);
                },
                comments : function(args) {
                    init(args);


                    var sr= (window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
                    for( k in _tplurl) {
                        sr.open("GET",_tplurl[k],false);
                        sr.send(null);
                        _tpl[k]=sr.responseText;
                    }

                    var req = (window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
                    req.responseType = 'json';

                    req.open("GET",_url.list,true);
                    req.onload=function() {
                        var data=req.response;
                        page = data.page;
                        _args.target.innerHTML=draw_list(_tpl['list'],data);
                    }
                    req.send(null);
                },

                add : function(target,container,data) {
                    target.innerHTML=draw_form(_tpl['form'],data);
                    set_form_vars(target,data);
                    /*
                    for (k in data) {
                        var inp=document.getElementsByName(k);
                        for (i in inp) { inp[i].value=data[k]; }
                    }
                    */

                    target.querySelector("#submitComment").onclick=function(){ Comments.post(this.parentNode,target,container);};

                },

                post : function (form,target,container) {
                    var post = get_form_vars(form);
                    var req = (window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
                    req.responseType = 'json';
                    req.open("POST",_url.post,true);
                    req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                    req.onload=function() {
                        log(req);
                        var data = req.response;

                        if (req.status == 200) {
                            log(data);
                            target.innerHTML="";
                            var comment_html=draw_comment(_tpl['comment'],data.comment);
                            container.innerHTML+=comment_html;
                    
                            target.innerHTML=draw_form(_tpl['form_verify'],data.comment);
                            set_form_vars(target,data.comment_html);

                        } else {
                            set_form_errors(form,data);

                        }
                    }
                    req.send(JSON.stringify(post));

                }
    };

}());


