function RemoteConnection(url,user,pass) {
    this.url = url;
    this.user = user;
    this.pass = pass;
    this.isOpen = false;

    this.open = function () {
        this.isOpen = true;
    }
    this.close = function () {
        this.isOpen = false;
    }
    this.execute = function (sql, parameters) {
        if (!this.isOpen) {
            this.open();
        }
        if (parameters === undefined) {
            parameters = [];
        }
        $.post(this.url,{
          "user":this.user,
          "pass":this.pass,
          "method":"execute",
          "sql":sql,
          "parameters":parameters
        });
    }

    this.query = function (sql, parameters, handle) {
        if (!this.isOpen) {
            this.open();
        }
        if (typeof (parameters) === "function") {
            handle = parameters;
            parameters = [];
        }
        $.post(this.url,{
          "user":this.user,
          "pass":this.pass,
          "method":"query",
          "sql":sql,
          "parameters":parameters
        },function(json){
          handle($.parseJSON(json));
        });
    }
}
