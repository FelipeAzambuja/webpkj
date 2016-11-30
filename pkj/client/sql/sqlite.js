function SqliteConnection(fileName, size, version) {
    this.file = fileName;
    this.isOpen = false;
    this.base = null;
    if (size === undefined) {
        this.size = 2;
    } else {
        this.size = size;
    }
    if (version === undefined) {
        this.version = 1;
    } else {
        this.version = version;
    }

    this.open = function () {
        this.base = openDatabase(this.file, this.version, 'pkj' + this.fileName, this.size * 1024 * 1014);
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
        this.base.transaction(function (tx) {
            tx.executeSql(sql, parameters);
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
        this.base.transaction(function (tx) {
            tx.executeSql(sql, [], function (tx, resultados) {
                var retorno = [];
                for (var i = 0; i < resultados.rows.length; i++) {
                    var row = resultados.rows.item(i);
                    retorno.push(row);
                }
                handle(retorno);
            });
        });
    }
}

