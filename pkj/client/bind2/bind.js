var bind = new Object();
$(function () {
    setInterval(function () {
        bind.refresh();
    }, 100);

});
bind.loading = false;
bind.refresh = function () {
    bind.loading = true;
    $('input,select,a,button,img,textarea,form').each(function (i, e) {
        var el = $(e);
        var event = '';
        var router = '';
        var page = '';
        if (el.attr('bind') === undefined) {
            router = el.attr('router');
            if (router === undefined) {
                router = window.location.href;
            }
            page = el.attr('page');
            if (page === undefined) {
                page = '';
            }
            if (el.attr('blur') !== undefined) {
                event = 'blur';
                el.attr('on' + event, 'bind.exec(this,"' + router + '","' + page + '","' + event + '")');
            }
            if (el.attr('click') !== undefined) {
                event = 'click';
                el.attr('on' + event, 'bind.exec(this,"' + router + '","' + page + '","' + event + '")');
            }
            if (el.attr('change') !== undefined) {
                event = 'change';
                el.attr('on' + event, 'bind.exec(this,"' + router + '","' + page + '","' + event + '")');
            }
            if (el.prop('tagName') === 'FORM') {
                if (el.attr('init') !== undefined) {
                    event = 'init';
                    $(function () {
                        setTimeout(function () {
                            bind.exec(el, router, page, event);
                        }, 1);
                    });
                }
            }
        }
        el.attr('bind', 'true');
    });
    bind.loading = false;
};
bind.exec = function (element, router, page, event) {
    var form = $(element).closest('form');
    var el = $(element);
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },
        cache: false
    });
    var data = {};
    data.GET = $_GET;
    data.HOST = window.location.href;
    var cmd = el.attr(event);
    if (cmd.indexOf('(') > -1) {
        var real_cmd = cmd.split('(')[0];
        var tmp = cmd.split('(')[1];
        var attr = tmp.split(')')[0];
        attr = attr.split(',');
        for (var i = 0; i < attr.length; i++) {
            data['post' + i] = attr[i];
        }
        cmd = real_cmd;
    }
    data.CMD = cmd;
    $(form).ajaxSubmit({
        url: router,
        type: 'POST',
        data: data,
        dataType: 'script'
    });
};
bind.call = function (router, page, function_name, args) {
    
};