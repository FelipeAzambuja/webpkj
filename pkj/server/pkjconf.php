<?php

class conf {
    public static $dateFormat = "d/m/Y";
// mysql pgsql mssql odbc oledb sqlite
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
    /**
     *
     * @var Db 
     */
    public static $pkj_bd_sis_conexao = null;
    public static $lastError = "";
    public static $random = "";
    public static $pkj_uid_comp = 0;
    public static $pkj_row = true;
    public static $resource = null;

}
//$home  = replace(ini_get("auto_prepend_file"),$_SERVER["DOCUMENT_ROOT"],"");
//$home = replace(__DIR__, "\\", "/");
//$home  = replace($home,"/server/pkjall.php","");
//conf::$pkjHome = $home;
function fix_loadht($file) {
    $lines = explode("\n", file_get_contents($file));
    $requires = [];
    foreach ($lines as $l) {
        if (trim($l) === "") {
            continue;
        }
        if ($l[0] === "#") {
            continue;
        }
        $arg = explode(" ", $l);
        for ($index = 0; $index < count($arg); $index++) {
            $arg[$index] = str_replace("\"", "", trim($arg[$index]));
        }
        $cmd = $arg[0];
        switch ($cmd) {
            case "php_value":
                if ($arg[1] === "auto_prepend_file") {
                    $requires[] = $arg[2];
                } else {
                    ini_set($arg[1], $arg[2]);
                }
                break;
            case "setenv":
                $_SERVER[$arg[1]] = $arg[2];
                break;
            default:
//                echo "ENV não reconhecida";
                break;
        }
    }

}

if (!isset($_SERVER["pkj_sessao"])) {
    fix_loadht('.htaccess');
}
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

function pkj_get_home($dir = __DIR__) {
    $root = "";
    $dir = str_replace('\\', '/', realpath($dir));
    if (!empty($_SERVER['CONTEXT_PREFIX'])) {
        $root .= $_SERVER['CONTEXT_PREFIX'];
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $root .= substr($dir, strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']));
        } else {
            $root .= substr($dir, strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']));
    }
    } else {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $root .= substr($dir, strlen($_SERVER['DOCUMENT_ROOT']));
        } else {
            $root .= substr($dir, strlen(realpath($_SERVER['DOCUMENT_ROOT']))+1);
    }
}
    return '/' . $root . '/';
}