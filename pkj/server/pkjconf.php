<?php

class conf {

    public static $pkjHome = "/webpkj/pkj";
    public static $dateFormat = "d/m/Y";
// mysql postgre mssql odbc oledb sqlite
    public static $servidor = "sqlite";
//    public static $servidor = "mysql";
    public static $endereco = "../../banco.sqlite";
//    public static $endereco = "";
    public static $usuario = "";
    public static $senha = "";
    public static $base = "";
    public static $quick = true;
    //session default,database,javascript
    public static $session = "database";
    public static $pkj_bd_sis_conexao = null;
    public static $lastError = "";
    public static $random = "";
    public static $pkj_uid_comp = 0;
    public static $pkj_row = true;

}
$home  = replace(ini_get("auto_prepend_file"),$_SERVER["DOCUMENT_ROOT"],"");
$home  = replace($home,"/server/pkjall.php","");
conf::$pkjHome = $home;
conf::$dateFormat = $_SERVER["pkj_dateformat"];
conf::$servidor = $_SERVER["pkj_servidor"];
conf::$endereco = $_SERVER["pkj_endereco"];
conf::$usuario = $_SERVER["pkj_usuario"];
conf::$senha = $_SERVER["pkj_senha"];
conf::$base = $_SERVER["pkj_base"];
conf::$session = $_SERVER["pkj_sessao"];
putenv('pkj_servidor=');
putenv('pkj_endereco=');
putenv('pkj_usuario=');
putenv('pkj_senha=');
putenv('pkj_base=');
putenv('pkj_sessao=');
unset($_ENV["pkj_servidor"]);
unset($_ENV["pkj_endereco"]);
unset($_ENV["pkj_usuario"]);
unset($_ENV["pkj_senha"]);
unset($_ENV["pkj_base"]);
unset($_ENV["pkj_sessao"]);

unset($_SERVER["pkj_servidor"]);
unset($_SERVER["pkj_endereco"]);
unset($_SERVER["pkj_usuario"]);
unset($_SERVER["pkj_senha"]);
unset($_SERVER["pkj_base"]);
unset($_SERVER["pkj_sessao"]);
unset($home);
?>