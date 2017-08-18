<?php

include './vendor/autoload.php';

function isCLI() {
    return (PHP_SAPI == 'cli');
}

function loadht($file) {
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
                echo "ENV não reconhecida";
                break;
        }
    }
//    var_dump($_SERVER);
    if (!function_exists("query")) {
        require './pkj/server/pkjall.php';
    }
}

if (isCLI()) {
    loadht(dirname(__FILE__) . '/.htaccess');
}

switch ($argv[1]) {
    case "tables":
        $pdo = conf::$pkj_bd_sis_conexao;
        $climate = new League\CLImate\CLImate();
        $table = [];
        foreach ($pdo->database_tables() as $t) {
            $table[] = ["name" => $t];
        }
        $climate->table($table);
        break;
    case "config":
    case "configurar":
        $pkj = dirname(__FILE__) . DIRECTORY_SEPARATOR . "pkj" . DIRECTORY_SEPARATOR . "server" . DIRECTORY_SEPARATOR . "pkjall.php";
        $pkj = str_replace("\\", "/", $pkj);

        echo "Qual a banco de dados (sqlite,pgsql,mysql,sqlsrv)?" . PHP_EOL;
        $servidor = trim(fgets(STDIN));

        //sem acento :(
        echo "Qual o endereco do banco de dados ?" . PHP_EOL;
        $endereco = trim(fgets(STDIN));
        if ($servidor === "sqlite") {
            $endereco = "../../{$endereco}";
        }

        echo "Qual o usuario do banco de dados?" . PHP_EOL;
        $usuario = trim(fgets(STDIN));

        echo "Qual a senha do banco de dados?" . PHP_EOL;
        $senha = trim(fgets(STDIN));

        echo "Qual o database do banco de dados?" . PHP_EOL;
        $base = trim(fgets(STDIN));

        $s = '';
        $s .= 'php_value auto_prepend_file "' . $pkj . '"' . PHP_EOL;
        $s .= 'php_value output_buffering 0' . PHP_EOL;
        $s .= 'php_value date.timezone "America/Sao_Paulo"' . PHP_EOL;
        $s .= 'setenv pkj_dateformat "d/m/Y"' . PHP_EOL;
        $s .= 'setenv pkj_servidor "' . $servidor . '"' . PHP_EOL;
        $s .= 'setenv pkj_endereco "' . $endereco . '"' . PHP_EOL;
        $s .= 'setenv pkj_usuario "' . $usuario . '"' . PHP_EOL;
        $s .= 'setenv pkj_senha "' . $senha . '"' . PHP_EOL;
        $s .= 'setenv pkj_base "' . $base . '"' . PHP_EOL;
        $s .= 'setenv pkj_sessao "database"' . PHP_EOL;
        $s .= '' . PHP_EOL;
        file_put_contents(".htaccess", $s);
        break;
    case "top":
        $climate = new League\CLImate\CLImate();
        $table = $argv[2];
        if (count($argv) > 3) {
            $top = $argv[3];
        } else {
            $top = 10;
        }
        $r = query("select * from {$table} order by id desc limit {$top} ");
        if (!$r) {
            $climate->error("Vazio");
            exit();
        }
        $climate->table($r);
        break;
    case "insert":
        $climate = new League\CLImate\CLImate();
        $c = conf::$pkj_bd_sis_conexao;
        $f = col($c->table_fields($argv[2]), "name");
        $a = [];
        foreach ($f as $c) {
            if ($c === "id") {
                continue;
            }
            $climate->out("Valor do campo {$c}");
            $v = trim(fgets(STDIN));
            $a[$c] = $v;
        }
        $sql = SQLinsert($argv[2], $a);
        $query = query($sql);
        if (!$query) {
            $climate->error(db_get_error());
            exit();
        } else {
            echo "OK";
        }
        break;
    case "sql":
        $climate = new League\CLImate\CLImate();
        $r = query($argv[2]);
        if (!$r) {
            $climate->dump(db_get_error());
            exit();
        }
        $climate->table($r);
        break;
    case "table_info":
    case "table":
    case "tabela":
        if (!isset($argv[2])) {
            echo "Qual a tabela ?" . PHP_EOL;
            $argv[2] = fgets(STDIN);
        }
        conectar();
        $info = conf::$pkj_bd_sis_conexao->table_fields($argv[2]);
        $climate = new League\CLImate\CLImate();
        $climate->table($info);
        break;
    case "orm":
        if (!isset($argv[2])) {
            echo "Qual a tabela ?" . PHP_EOL;
            $argv[2] = fgets(STDIN);
        }
        if (isset($argv[3])) {
            orm($argv[2], $argv[3]);
        } else {
            orm($argv[2]);
        }
        break;

    case "ajuda":
    case "help":
        ajuda();
        break;

    default:
        ajuda();
        break;
}
exit();

function orm($tabela, $pasta = "pkj/db") {
    echo color("ORM", Colors::$yellow) . PHP_EOL;
    echo color("Tabela $tabela", Colors::$white) . PHP_EOL;
    echo color("Pasta $pasta", Colors::$white) . PHP_EOL;
    $classe = ucwords(str_replace("_", " ", $tabela));
    $classe = str_replace(" ", "", $classe);
    $con = conf::$pkj_bd_sis_conexao;
    $campos = $con->table_fields($tabela);
    $fields = col($campos, "name");
    $s = '';
    $s .= '<?php' . PHP_EOL;
    $s .= '/**' . PHP_EOL;
    $s .= ' * ' . PHP_EOL;
    $s .= ' * @return	' . $classe . PHP_EOL;
    $s .= ' */' . PHP_EOL;
    $s .= 'function orm_' . $tabela . '(){' . PHP_EOL;
    $s .= '	return new ' . $classe . '();' . PHP_EOL;
    $s .= '}' . PHP_EOL;
    $s .= 'class ' . $classe . ' extends DBTable {' . PHP_EOL;
    $s .= '	' . PHP_EOL;
    foreach ($fields as $f) {
        $s .= '	var $' . $f . ';' . PHP_EOL;
    }
    $s .= '	' . PHP_EOL;
    $s .= '	function getFields(){' . PHP_EOL;
    $s .= '		$campos = [];' . PHP_EOL;
    foreach ($campos as $i) {
        $s .= '		$campos[] = array("name"=>"' . lcase($i->name) . '","type"=>"' . lcase($i->detype) . '");' . PHP_EOL;
    }
    $s .= '		return $campos;' . PHP_EOL;
    $s .= '	}' . PHP_EOL;
    $s .= '	' . PHP_EOL;
    $s .= '	function getName(){' . PHP_EOL;
    $s .= '		return "' . $tabela . '";' . PHP_EOL;
    $s .= '	}' . PHP_EOL;
    $s .= '}' . PHP_EOL;
    $s .= '' . PHP_EOL;
    $s .= '' . PHP_EOL;

    file_put_contents($pasta . "/{$classe}.php", $s);
}

function crud($tabela) {
    
}

function ajuda() {
    echo color("Ajuda", Colors::$yellow) . PHP_EOL;
    echo color("orm tabela", "white");
    echo " Cria a estrutura basica do orm com base na tabela informada" . PHP_EOL;
    echo color("table_info tabela table \"tabela\" ", "white");
    echo " Mostra a info da tabela" . PHP_EOL;
    echo color("sql \"select datetime('now','localtime')\"", "white");
    echo " Executa uma consulta direta" . PHP_EOL;
    echo color("config configurar", "white");
    echo " Configura o webpkj" . PHP_EOL;

//    echo color("crud", "white");
//    echo " Cria um 'crud' basico com base na tabela informada" . PHP_EOL;
    exit();
}

function color($string, $cor = null, $fundo = null) {
    return (new Colors())->getColoredString($string, $cor, $fundo);
}

class Colors {

    public static $black = "black";
    public static $dark_gray = "dark_gray";
    public static $blue = "blue";
    public static $light_blue = "light_blue";
    public static $green = "green";
    public static $cyan = "cyan";
    public static $light_cyan = "light_cyan";
    public static $red = "red";
    public static $light_red = "light_red";
    public static $brown = "brown";
    public static $yellow = "yellow";
    public static $light_gray = "light_gray";
    public static $white = "white";
    private $foreground_colors = array();
    private $background_colors = array();

    public function __construct() {
        // Set up shell colors
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
    }

    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null) {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    // Returns all foreground color names
    public function getForegroundColors() {
        return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors() {
        return array_keys($this->background_colors);
    }

}
