var bind = new Object();
var eventos = new Object();
$(function () {
    setInterval(function () {
        bind.refresh();
    }, 100);

});
bind.loading = false;
bind.refresh = function () {
    bind.loading = true;
    $('input,select,a,button,img,textarea,form,li').each(function (i, e) {
        var el = $(e);
        var event = '';
        var router = '';
        var page = '';
        if (el.attr('bind') === undefined) {
            if (el.prop('tagName') === 'FORM') {
                el.keydown(function (event) {
                    if (event.keyCode === 13 && event.target.tagName !== 'TEXTAREA') {
                        event.preventDefault();
                        var elemento = $(event.target);
                        if (elemento.prop('tagName') === 'INPUT' || elemento.prop('tagName') === 'SELECT') {
                            $(elemento.parents('form')).find('*[click]').trigger('click');
                        }
                        return false;
                    }
                });
            }            
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
            el.attr('bind', 'true');
        }
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
    data.PAGE = page;
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
    data.vue = {};
    for (var i in window) {
        if (window[i] !== null) {
            try {
                if (window[i].$data !== undefined) {
                    data.vue[i] = JSON.parse(JSON.stringify(window[i].$data));
                }
            } catch (e) {

            } finally {

            }
        }
    }
    if (el.attr('lock') !== undefined) {
        el.addClass('disabled');
    }
    var handler = function (response) {
        if (el.attr('lock') !== undefined) {
            el.removeClass('disabled');
        }
    };
    $(form).ajaxSubmit({
        url: router,
        type: 'POST',
        data: data,
        dataType: 'script',
        success: handler
    });
};
bind.call = function (router, page, function_name, args) {
    //preguiçaaaa
};
function bindCall(page,fnc,data){
    data.HOST = window.location.href;
    data.CMD = fnc;
    if(page === ""){
        page = data.HOST;
    }
    
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },
        cache: false
    });
    $.post(page,data,function(response){
        //talvez pelo header ele já execute o eval
        //eval(response);
    });

}