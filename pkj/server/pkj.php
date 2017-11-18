<?php

function is_array_numeric($a) {
    foreach (array_keys($a) as $key) {
        if (is_int($key)) {
            return true;
        }
    }
    return false;
}

function download($arquivo, $mine = "") {
    $ext = explode(".", $arquivo);
    $ext = ((empty($ext[count($ext) - 1]) || count($ext) == 1) ? "" : $ext[count($ext) - 1]);
    switch ($ext) {
        case "pdf": $tipo = "application/pdf";
            break;
        case "exe": $tipo = "application/octet-stream";
            break;
        case "zip": $tipo = "application/zip";
            break;
        case "doc": $tipo = "application/msword";
            break;
        case "xls": $tipo = "application/vnd.ms-excel";
            break;
        case "ppt": $tipo = "application/vnd.ms-powerpoint";
            break;
        case "gif": $tipo = "image/gif";
            break;
        case "png": $tipo = "image/png";
            break;
        case "jpg": $tipo = "image/jpg";
            break;
        case "mp3": $tipo = "audio/mpeg";
            break;
    }
    if ($mine != ""):
        $tipo = $mine;
    endif;
    header("Content-Type: " . $tipo);
    header("Content-Length: " . filesize($arquivo));
    header("Content-Disposition: attachment; filename=" . basename($arquivo));
    readfile($arquivo);
    exit();
}

class Calendar {

    public static $FORMAT_BRASILEIRO_DATA = "brasileiro";
    public static $FORMAT_BRASILEIRO_DATA_HORA = "brasileiro completo";
    public static $FORMAT_AMERICANO_DATA = "americano";
    public static $FORMAT_AMERICANO_DATA_HORA = "americano completo";
    public $ano = "0000";
    public $mes = "00";
    public $dia = "00";
    public $hora = "00";
    public $minuto = "00";
    public $segundo = "00";
    public $semana = 1; //dia da semana

    function __construct($data = "") {
        if ($data == "") {
            $this->fromString(date('Y-m-d H:i:s'));
        } else {
            $this->fromString($data);
        }
    }

    function Calendar() {
        $this->fromString(date('Y-m-d H:i:s'));
    }

    public function modify($modificador) {
        $data = $this->toString("americano completo");
        $timestamp = strtotime($data . $modificador);
        $this->fromString(date('Y-m-d H:i:s', $timestamp));
    }

    public function format($format, $modify = '') {
        $data = $this->toString("americano completo");
        $timestamp = strtotime($data . $modify);
        return date($format, $timestamp);
    }

    public static function toSeconds($valor) {
        $negative = ($valor[0] === "-");
        $valor = str_replace("-", "", $valor);
        $hora = explode(":", $valor);
        if ($negative) {
            return (($hora[0] * 3600) + ($hora[1] * 60) + $hora[2]) * -1;
        } else {
            return ($hora[0] * 3600) + ($hora[1] * 60) + $hora[2];
        }
    }

    public static function fromSeconds($valor) {
        $seconds = $valor;
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return "$hours:$minutes:$seconds";
    }

    public function addCalendar($calendario) {
        $data = $this->toString("americano completo");
        $timestamp = strtotime($data . "+{$calendario->ano} years {$calendario->mes} months {$calendario->dia} days {$calendario->hora} hours {$calendario->minuto} minutes {$calendario->segundo} seconds");
        $this->fromString(date('Y-m-d H:i:s', $timestamp));
    }

    public function removeCalendar($calendario) {
        $data = $this->toString("americano completo");
        $timestamp = strtotime($data . "-{$calendario->ano} years {$calendario->mes} months {$calendario->dia} days {$calendario->hora} hours {$calendario->minuto} minutes {$calendario->segundo} seconds");
        $this->fromString(date('Y-m-d H:i:s', $timestamp));
    }

    /**
     *
     * @param type $formato americano, brasileiro, russo,americano completo, brasileiro completo, russo completo
     */
    public function toString($formato) {
        $retorno = "";
        switch ($formato) {
            case "americano":
                $retorno = "{$this->ano}-{$this->mes}-{$this->dia}";
                break;
            case "brasileiro":
                $retorno = "{$this->dia}/{$this->mes}/{$this->ano}";
                break;
            case "americano completo":
                $retorno = "{$this->ano}-{$this->mes}-{$this->dia} {$this->hora}:{$this->minuto}:{$this->segundo}";
                break;
            case "brasileiro completo":
                $retorno = "{$this->dia}/{$this->mes}/{$this->ano} {$this->hora}:{$this->minuto}:{$this->segundo}";
                break;
        }
        return $retorno;
    }

    public function fromString($data) {
        $separador = "";
        $separador = ((stripos($data, "-") > 0) ? "-" : $separador);
        $separador = ((stripos($data, "/") > 0) ? "/" : $separador);
        $separador = ((stripos($data, ".") > 0) ? "." : $separador);
        $dataOriginal = $data;
        $data = explode(" ", $data);
        $data = explode($separador, $data[0]);
        if ($separador == "/") {
            $this->dia = $this->completa(2, $data[0]);
        } else {
            @$this->dia = $this->completa(2, $data[2]);
        }
        @$this->mes = $this->completa(2, $data[1]);
        if ($separador == "/") {
            $this->ano = $data[2];
        } else {
            $this->ano = $data[0];
        }
        //$diasNoMes = cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->ano);
        @$this->semana = date("w", mktime(0, 0, 0, $this->mes, $this->dia, $this->ano));
        if (strlen(trim($dataOriginal)) > 10) {
            $this->hora = $this->completa(2, substr($dataOriginal, 11, 2));
            if (strlen(trim($dataOriginal)) > 13) {
                $this->minuto = $this->completa(2, substr($dataOriginal, 14, 2));
            }
            if (strlen(trim($dataOriginal)) > 16) {
                $this->segundo = $this->completa(2, substr($dataOriginal, 17, 2));
            }
        }
    }

    private function completa($casas, $valor) {
        $casasValor = strlen($valor);
        $zeros = "";
        for ($index = $casasValor; $index < $casas; $index++) {
            $zeros .= "0";
        }
        return ($zeros . "" . $valor);
    }

}

function get($campo) {
    $retorno = "";
    if (isset($_GET[$campo])) {
        $retorno = $_GET[$campo];
    }
    return $retorno;
}

function post($campo) {
    $retorno = "";
    if (isset($_POST[$campo])) {
        $retorno = $_POST[$campo];
    }
    return $retorno;
}

function addslashes_array(&$arr_r) {
    if (is_array($arr_r)) {
        foreach ($arr_r as &$val) {
            is_array($val) ? addslashes_array($val) : $val = str_replace("'", '', $val);
        }
        unset($val);
    } else {
        $arr_r = str_replace("'", "", $arr_r);
    }
}

function addslashes_deep($input) {
    return addslashes_array($input);
}

function cint($value) {
    $value = replace($value, ",", "");
    $value = replace($value, ".", "");
    return intval(trim($value));
}

function cdbl($value) {
    return cdouble($value);
}

function cfloat($value) {
    return round(cdouble($value), 2);
}

function cdouble($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }
    return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
    );
}

function cstring($value) {
    return strval($value);
}

function cstr($value) {
    return strval($value);
}

/**
 * Instancia um calendar
 * @param Calendar $value
 * @return \Calendar
 */
function cdate($value = "") {
    return new Calendar($value);
}

function is_date($value) {
    return preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $value);
}

function is_time($value) {
    return preg_match("/^[0-9]{2}[:][0-9]{2}[:][0-9]{2}[\.]{0,}[0-9]{0,}$/", $value);
}

function date_filter($value) {
    $split = preg_split("([ ]|[T])", $value);
    if (count($split) == 2) {
        $split[0] = trim(replace($split[0], "T", " "));
        if (is_date($split[0]) && is_time($split[1])) {
            if ($split[1] != "00:00:00") {
                return cdate($split[0] . " " . $split[1])->format(conf::$dateFormat . " H:i:s");
            } else {
                return cdate($split[0])->format(conf::$dateFormat);
            }
        } else {
            return $value;
        }
    } elseif (count($split) == 1) {
        $split[0] = trim(replace($split[0], "T", " "));
        if (is_date($split[0])) {
            return cdate($split[0])->format(conf::$dateFormat);
        } else {
            return $value;
        }
    } else {
        return $value;
    }
}

function copyr($source, $dest) {

//    $source = "dir/dir/dir";
//     = "dest/dir";

    mkdir($dest, 0755);
    foreach (
    $iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item) {
        if ($item->isDir()) {
            mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        } else {
            copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }
    }
}
