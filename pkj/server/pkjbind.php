<?php
$bindDebug = false;
if ( $bindDebug ) {
    error_reporting ( -1 );
    ini_set ( "display_errors" , "On" );
}
$noUse = get_defined_functions ();
if ( isset ( $_POST["CMD"] ) ) {
    set_error_handler ( function ($severity , $message , $filename , $lineno) {
        if ( error_reporting () === 0 ) {
            return;
        }
        throw new ErrorException ( $message , 0 , $severity , $filename , $lineno );
    } );
    try {
        $cmd = $_POST["CMD"];
        header ( 'Content-Type: text/javascript; charset=UTF-8' );
        if ( in_array ( $cmd , $noUse ) ) {
            if ( $bindDebug ) {
                echo "console.log(\"função proibida\");";
            }
            exit ();
        }
        if ( isset ( $_POST["PAGE"] ) ) {
            if ( len ( $_POST["PAGE"] ) > 0 ) {
                $pagina = realpath ( getcwd () . '/public/' . $_POST['PAGE'] );
                show_errors ( true );
                ob_end_clean ();
                require_once $pagina;
                ob_end_clean ();
            }
        }
        $tmp2 = $_POST;
        addslashes_array ( $tmp2 );
        unset ( $tmp2["CMD"] );
        unset ( $tmp2["PAGE"] );
        unset ( $tmp2["HOST"] );
        if ( isset ( $tmp2['post0'] ) ) {
            if ( $tmp2["post0"] === "" ) {
                unset ( $tmp2["post0"] );
            }
        }

        if ( function_exists ( $cmd ) ) {
//            if (isset($_POST["MUSTACHE"])) {
//                call_user_func($cmd, $tmp2, $_POST["MUSTACHE"]);
//            } else {
            array_walk_recursive ( $tmp2 , function(&$v , $k) {
                $v = ($v === '') ? null : $v;
                $v = (is_numeric ( str_replace ( ['.' , ','] , '' , $v ) ) ) ? cdbl ( $v ) : $v;
            } );
            call_user_func ( $cmd , $tmp2 );
//            }
        } else {
            console ( "Função não existe" );
        }
    } catch ( Throwable $t ) {
        ?>
        console.error("<?php echo JS::addslashes ( jTraceEx ( $t ) ) ?>");
        <?php
        exit ();
    } catch ( Exception $exc ) {
        ?>
        console.error("<?php echo JS::addslashes ( jTraceEx ( $exc ) ) ?>");
        <?php
    } finally {
        
    }
    bindUpdate ();
    exit ();
}

function __normalizePath ( $path ) {
    $parts = array (); // Array to build a new path from the good parts
    $path = str_replace ( '\\' , '/' , $path ); // Replace backslashes with forwardslashes
    $path = preg_replace ( '/\/+/' , '/' , $path ); // Combine multiple slashes into a single slash
    $segments = explode ( '/' , $path ); // Collect path segments
    $test = ''; // Initialize testing variable
    foreach ( $segments as $segment ) {
        if ( $segment != '.' ) {
            $test = array_pop ( $parts );
            if ( is_null ( $test ) )
                $parts[] = $segment;
            else if ( $segment == '..' ) {
                if ( $test == '..' )
                    $parts[] = $test;

                if ( $test == '..' || $test == '' )
                    $parts[] = $segment;
            } else {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode ( '/' , $parts );
}

class JS {

//TODO implementar javascript
    public static function alert ( $mensagem ) {
        ?>alert("<?php echo JS::addslashes ( $mensagem ) ?>");<?php
    }

    public static function console ( $mensagem ) {
        if ( is_array ( $mensagem ) ) {
            ?>console.log(<?php echo json_encode ( $mensagem ) ?>);<?php
            if ( json_last_error () !== JSON_ERROR_NONE ) {
                console ( json_last_error_msg () );
                die;
            }
        } else {
            ?>console.log("<?= JS::addslashes ( $mensagem ) ?>");<?php
        }
    }

    //trocar 
    public static function popup ( $mensagem , $id = "" ) {
        ?>popup("<?php echo JS::addslashes ( $mensagem ) ?>","<?php echo JS::addslashes ( $id ) ?>");<?php
    }

    public static function popup_close ( $id = "" ) {
        ?>popup_close("<?= $id ?>");<?php
    }

    public static function redirect ( $pagina , $data = "" ) {
        if ( $data !== "" ) {
            $data = "?" . http_build_query ( $data );
        }
        ?>window.location.href="<?= $pagina . $data ?>";<?php
    }

    public static function _addslashes ( $s ) {

        $l = strlen ( $s );
        for ( $i = 0; $i < $l; $i ++ ) {
            switch ( $s[$i] ) {
                case '\\': // \
                    $s = substring ( $s , 0 , $i ) . '\\\\' . substring ( $s , $i + 1 );
                    $i = $i + 1;
                    break;
                case '"': // "
                    $s = substring ( $s , 0 , $i ) . '\\"' . substring ( $s , $i + 1 );
                    $i = $i + 1;
                    break;
            }
        }
        $s = str_replace ( PHP_EOL , '\n' , $s );
        return $s;
    }

    public static function addslashes ( $s ) {
        if ( is_array ( $s ) ) {
            $s = json_encode ( $s , JSON_UNESCAPED_UNICODE );
            if ( json_last_error () !== JSON_ERROR_NONE ) {
                console ( json_last_error_msg () );
                die;
            }
        }
        $s = strval ( $s );
        $s = str_replace ( PHP_EOL , '\n' , json_encode ( $s , JSON_UNESCAPED_UNICODE ) );
        if ( json_last_error () !== JSON_ERROR_NONE ) {
            console ( json_last_error_msg () );
            die;
        }
        $s = (strlen ( $s ) > 0) ? $s : '""';
        return '"+' . $s . '+"';
//        $s = str_replace('*/', '* /', $s);//buaaa
        return '"+_heredoc(function(){/* ' . $s . ' */})+"';
//        $a = array('<','>','\'','\\','"','\n','\r',PHP_EOL);
//        $b = array('\\x3C','\\x3E','\\\'','\\\\','\\"','\\n','\\r','');
//        return str_replace($a, $b, $s);
        $s = str_replace ( PHP_EOL , '' , $s );
        $l = strlen ( $s );
        for ( $i = 0; $i < $l; $i ++ ) {
            switch ( $s[$i] ) {
                case '\\': // \
                    $s = substring ( $s , 0 , $i ) . '\\\\' . substring ( $s , $i + 1 );
                    $i = $i + 1;
                    break;
                case '"': // "
                    $s = substring ( $s , 0 , $i ) . '\\"' . substring ( $s , $i + 1 );
                    $i = $i + 1;
                    break;
            }
        }
        return $s;
    }

}

/**
 * New instance of Bind
 * @return \Bind
 */
function bind ( $form = array () ) {
    return new Bind ( $form );
}

function bindUpdate () {
    bind ( array () )->update ();
}

function mustache ( $id , $html , $data = array () ) {
    $html = JS::addslashes ( $html );
    ?>
    var bind_tmp1 = Mustache.render("<?php echo $html ?>", <?php echo json_encode ( $data ) ?>);
    Mustache.parse(bind_tmp1);
    $("*[id='<?php echo $id ?>']").html(bind_tmp1);
    <?php
    bindUpdate ();
}

class Bind {

    var $ids;
    var $form;

    function __construct ( $form ) {
        $this->form = $form;
    }

    function update () {
        ?>tagUpdate();<?php
    }

    /**
     * Set value to html input
     * @param type $id
     * @param type $value
     * @return \Bind
     */
    function setValue ( $id , $value ) {
//        $value = JS::addslashes($value);
        $value = str_replace ( '*/' , '* /' , $value ); //buaaa
        $this->jquery ( $id , "val( _heredoc(function(){/* {$value} */}) )" );
        $this->jquery ( $id , "attr('value', _heredoc(function(){/* {$value} */}) )" );
        return $this;
    }

    /**
     * Get a input html value
     * @param type $id
     * @return type
     */
    function getValue ( $id ) {
        return $this->form[$id];
    }

    function value ( $id , $value = null ) {
        if ( $value == null ) {
            return $this->getValue ( $id );
        } else {
            $this->setValue ( $id , $value );
            return $this;
        }
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function setHtml ( $id , $html ) {

        $html = JS::addslashes ( $html );
        $this->jquery ( $id , "html(\"$html\")" );
        return $this;
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function html ( $id , $html ) {
        $this->setHtml ( $id , $html );
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function setText ( $id , $text ) {
        $text = JS::addslashes ( $text );
        $this->jquery ( $id , "text(\"$text\")" );
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function append ( $id , $text ) {
        $text = JS::addslashes ( $text );
        $this->jquery ( $id , "append(\"$text\")" );
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function text ( $id , $text ) {
        $this->setText ( $id , $text );
        return $this;
    }

    /**
     * Enable a html element
     * @param type $id
     */
    function setEnable ( $id ) {
        $this->jquery ( $id , "removeAttr(\"disabled\")" );
        return $this;
    }

    function enable ( $id ) {
        $this->setEnable ( $id );
        return $this;
    }

    function focus ( $id ) {
        $this->jquery ( $id , "focus()" );
        return $this;
    }

    /**
     * Disable a html element
     * @param type $id
     */
    function setDisable ( $id ) {
        $this->jquery ( $id , "attr(\"disabled\",\"true\")" );
        return $this;
    }

    function disable ( $id ) {
        $this->setDisable ( $id );
        return $this;
    }

    function show ( $id ) {
        $this->jquery ( $id , "show()" );
        return $this;
    }

    function hide ( $id ) {
        $this->jquery ( $id , "hide()" );
        return $this;
    }

    function attr ( $id , $name , $value ) {
        $value = JS::addslashes ( $value );
        $this->jquery ( $id , "attr(\"$name\",\"$value\")" );
        return $this;
    }

    /**
     * Get a Instance of UploadParser
     * @param type $id
     * @return \UploadParser
     */
    function upload ( $id ) {
        return new UploadParser ( $this->form[$id] );
    }

    function setInterval ( $function , $time , $parameters = array () , $page = "" ) {
        if ( isset ( $_POST["PAGE"] ) && $page === "" ) {
            $page = $_POST["PAGE"];
        }
        ?>
        eventos["<?= $function ?>"] = setInterval(function () {
        bindCall("<?= $page ?>","<?= $function ?>",<?= json_encode ( $parameters , JSON_FORCE_OBJECT ) ?>)
        },<?= $time ?>);
        <?php
    }

    function setTimeout ( $function , $time , $parameters = array () , $page = "" ) {
        if ( isset ( $_POST["PAGE"] ) && $page === "" ) {
            $page = $_POST["PAGE"];
        }
        ?>
        eventos["<?= $function ?>"] = setTimeout(function () {
        bindCall("<?= $page ?>","<?= $function ?>",<?= json_encode ( $parameters , JSON_FORCE_OBJECT ) ?>)
        },<?= $time ?>);
        <?php
    }

    function stopInterval ( $function ) {
        ?>clearInterval(eventos["<?= $function ?>"])<?php
    }

    function stopTimeout ( $function ) {
        ?>clearTimeout(eventos["<?= $function ?>"])<?php
    }

    /**
     * Send focus
     * @param type $id id 
     * @return type this
     */
    function setFocus ( $id ) {
        return $this->jquery ( $id , "focus()" );
    }

    function autocomplete ( $id , $values ) {
        $values = json_encode ( $values );
        jquery ( $id , "attr('data-autocomplete','$values')" );
    }

    function combo ( $id , $values , $names = [] ) {
        $html = "";
        if ( $names === [] ) {
            $names = $values;
        }
        for ( $index = 0; $index < count ( $values ); $index ++ ) {
            $v = $values[$index];
            $n = $names[$index];
            $html .= "<option value='$v'>$n</option>";
        }
        html ( $id , $html );
    }

    /**
     * Force a jquery code
     * @param type $id
     * @param type $code
     */
    function jquery ( $id , $code ) {
        if ( startswith ( $id , "#" ) ) {
            //$id = replace($id, "#", "");
            ?>$("<?php echo $id ?>").<?php echo $code ?>;<?php
            ?>_pkj_postfix($("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']"));<?php
        } else {
            ?>$("<?php echo $id ?>").<?php echo $code ?>;<?php
            ?>_pkj_postfix($("<?php echo $id ?>"));<?php
        }

        return $this;
    }

    /**
     * 
     * @param string $message
     * @param string $type error,success,info,warn
     */
    function notify ( $message , $type = 'success' ) {
        ?>$.notify("<?= JS::addslashes ( $message ) ?>",'<?= $type ?>');<?php
    }

}

/**
 * 
 * @param type $value
 * @return \Base64Parser
 */
function base64_parse ( $value ) {
    return new Base64Parser ( $value );
}

class Base64Parser {

    private $raw = "";
    private $parse = [null , null , null , null];

    function __construct ( $value ) {
        $this->raw = $value;
        $this->parse = preg_split ( '/(,|;|:)/' , $value );
    }

    function is_ok () {
        return count ( array_filter ( $this->parse , function($value) {
                            return $value !== null;
                        } ) ) > 0;
    }

    function error () {
        if ( $this->parse[1] === null ) {
            return 'Não foi possivel encontrar o mime';
        }
        if ( $this->parse[3] === null ) {
            return 'Não foi possivel encontrar o data';
        }
        return 'Sem erro ?';
    }

    function base64 () {
        return $this->parse[3];
    }

    function data () {
        return base64_decode ( $this->base64 () );
    }

    function mime () {
        return $this->parse[1];
    }

    function raw () {
        return $this->raw;
    }

}

/**
 * Return a instance of upload parser
 * @param type $value
 * @return \UploadParser
 */
function upload_parser ( $value ) {
    return new UploadParser ( $value );
}

class UploadParser {

    private $raw = "";

    function __construct ( $name , $array = null ) {
        if ( $array === null ) {
            $array = $_FILES;
        }
        $this->raw = isset ( $array[$name] ) ? $array[$name] : null;
    }

    /**
     * Get a extension of file
     * @return string
     */
    function ext () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        $ext = explode ( "/" , $this->mime () );
        $ext = $ext[1];
        if ( $ext === "vnd.oasis.opendocument.spreadsheet" ) {
            $ext = "ods";
        } else if ( $ext === "vnd.oasis.opendocument.text" ) {
            $ext = "odt";
        } else if ( $ext === "plain" ) {
            $ext = "txt";
        } else if ( $ext === "x-7z-compressed" ) {
            $ext = "7z";
        } else if ( $ext === "x-rar" ) {
            $ext = "rar";
        }
        return $ext;
    }

    function mime () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        return $this->raw['type'];
    }

    /**
     * Return base64 format of file
     * @return type
     */
    function base64 () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        return base64_encode ( $this->data () );
    }

    /**
     * Get binary of file
     * @return type
     */
    function data () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        return file_get_contents ( $this->raw['tmp_name'] );
    }

    /**
     * Try a get name of file
     * @return type
     */
    function name () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        return $this->raw['name'];
    }

    /**
     * Get my raw format dont use please
     * @return type
     */
    function raw () {
        return $this->raw;
    }

    /**
     * 
     * @return int size in bytes
     */
    function size () {
        if ( ! $this->is_ok () ) {
            return false;
        }
        return $this->raw['size'];
    }

    function error_code () {
        if ( $this->raw === null ) {
            return 99;
        }
        return $this->raw['error'];
    }

    function error () {
        return $this->codeToMessage ( $this->error_code () );
    }

    function is_ok () {
        return $this->error_code () === 0;
    }

    private function codeToMessage ( $code ) {
        switch ( $code ) {
            case UPLOAD_ERR_OK:
                $message = 'No error';
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    /**
     * Save data in a file
     * @param type $fileName
     * @return boolean
     */
    function save ( $fileName = '' ) {
        if ( $fileName === '' ) {
            $fileName = $this->name ();
        }
        if ( strpos ( $fileName , '.' ) === false ) {
            $fileName = $fileName . '.' . $this->ext ();
        }
        file_put_contents ( $fileName , $this->data () );
    }

    /**
     * 
     * @return \Intervention\Image\Image
     */
    function image () {
        return image ( $this->data () );
    }

}

function c ( ...$vars ) {
    Kint::$mode_default = Kint::MODE_TEXT;
    Kint::$return = true;
    console ( Kint::dump ( ...$vars ) );
}

function cd ( ...$vars ) {
    Kint::$mode_default = Kint::MODE_TEXT;
    Kint::$return = true;
    console ( Kint::dump ( ...$vars ) );
    exit ();
}

/**
 * 
 * @param type $varname
 * @return \Vue
 */
function vue ( $varname ) {
    return new Vue ( $varname );
}

class Vue {

    var $varname = '';
    var $data = [];

    function __construct ( $varname ) {
        $this->varname = $varname;
        $this->data = @$_POST['vue'][$varname];
    }

    function data ( $data = null ) {
        if ( $data === null ) {
            return $this->data;
        } else {
            foreach ( $data as $key => $value ) {
                $value = json_encode ( $value );
                echo "{$this->varname}.{$key} = {$value};";
            }
        }
    }

}

function js_set ( $name , $value ) {
    $value = json_encode ( $value , JSON_NUMERIC_CHECK );
    echo "{$name} = {$value};";
}

function js_call ( $function , $params = [] ) {
    $params = implode ( ',' , array_map ( function($value) {
                return json_encode ( $value );
            } , $params ) );
    echo "{$function}({$params});";
}
