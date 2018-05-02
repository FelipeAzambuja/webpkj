Vue.load = function (page,name) {
    $.ajax('./pkj.vue.component', {
        data: {
            name: name,
            page: page
        },
        async: false,
        cache: true,
        complete: function (component) {
            eval(component.responseText);
        }
    });
};