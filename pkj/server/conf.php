<?php

class conf {

    public static $pkjHome = "/webpkj/pkj";
    public static $dateFormat = "d/m/Y";
// mysql postgre mssql odbc oledb sqlite
//    public static $servidor = "sqlite";
    public static $servidor = "mysql";
//    public static $endereco = "../../banco.sqlite";
    public static $endereco = "newbgp.com.br";
    public static $usuario = "c2felipe";
    public static $senha = "newbgpSucesso";
    public static $base = "c2sistema";
    public static $quick = true;
    //session default,database,javascript
    public static $session = "database";
    public static $pkj_bd_sis_conexao = null;
    public static $lastError = "";
    public static $random = "";
    public static $pkj_uid_comp = 0;
    public static $pkj_row = true;

}

?>
