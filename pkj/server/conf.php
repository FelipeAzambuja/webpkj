<?php

class conf {

    public static $pkjHome = "/webpkj/pkj";
    public static $dateFormat = "d/m/Y";
// mysql postgre mssql odbc oledb sqlite
    public static $servidor = "postgre";
//    public static $endereco = "../../banco.sqlite";
    public static $endereco = "localhost";
    public static $usuario = "postgres";
    public static $senha = "123";
    public static $base = "apinfe";
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
