function SqliteConnection(fileName, size, version) {
    this.mode;
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

    this.createTable = function (name, fields) {
        var field = "";
        var sql = "create table if not exists " + name + "( id integer primary key autoincrement,";
        for (var i = 0; i < fields.length; i++) {
            field = fields[i];
            if (i === (fields.length - 1)) {
                sql += field + ")";
            } else {
                sql += field + ",";
            }
        }
        this.execute(sql);
    };

    this.replace = function (table, fields, where) {
        var self = this;
        this.query("select id from " + table + " where " + where, function (linhas) {
            if (linhas.length > 0) {
                self.update(table,fields,where);
            } else {
                self.insert(table, fields);
            }
        });
    }

    this.delete = function (table, where) {
        var sql = "delete from " + table + " where" + where;
        this.execute(sql);
    }

    this.insert = function (table, fields) {
        var fieldsName = Object.keys(fields);
        var sql = "insert into " + table + " (";
        var p = " (";
        for (var i = 0; i < fieldsName.length; i++) {
            field = fieldsName[i];
            if (i === (fieldsName.length - 1)) {
                sql += field + ")";
                p += "?)";
            } else {
                sql += field + ",";
                p += "?,";
            }
        }
        sql += " values " + p;
        this.execute(sql, Object.values(fields));
    }

    this.update = function (table, fields, where) {
        var fieldsName = Object.keys(fields);
        var sql = "update " + table + " set ";
        for (var i = 0; i < fieldsName.length; i++) {
            field = fieldsName[i];
            if (i === (fieldsName.length - 1)) {
                sql += field + " = ? ";
            } else {
                sql += field + " = ? , ";
            }
        }
        sql += "where " + where;
        this.execute(sql, Object.values(fields));
    }

    this.open = function () {
        if (typeof (window.sqlitePlugin) !== "undefined") {
            this.base = window.sqlitePlugin.openDatabase({name: this.file, location: 'default'});
            this.mode = "cordova plugin";
        } else {
            this.base = openDatabase(this.file, this.version, 'pkj' + this.fileName, this.size * 1024 * 1014);
            this.mode = "chrome native";
        }
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
            tx.executeSql(sql, parameters, function (tx, resultados) {
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

