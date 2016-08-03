<?php
$bindDebug = false;
if ($bindDebug) {
    error_reporting(-1);
    ini_set("display_errors", "On");
}
$noUse = get_defined_functions();
if (isset($_POST["CMD"])) {
    $cmd = $_POST["CMD"];
    if (in_array($cmd, $noUse)) {
        if ($bindDebug) {
            echo "console.log(\"função proibida\");";
        }
        exit();
    }
    $tmp2 = $_POST;
    addslashes_array($tmp2);
    unset($tmp2["CMD"]);
    if ($tmp2["post0"] === "") {
        unset($tmp2["post0"]);
    }
    call_user_func($cmd, $tmp2);
    exit();
}

/**
 * New instance of Bind
 * @return \Bind
 */
function bind($form) {
    return new Bind($form);
}

class Bind {

    var $ids;
    var $form;

    function __construct($form) {
        $this->form = $form;
    }

    /**
     * Set value to html input
     * @param type $id
     * @param type $value
     * @return \Bind
     */
    function setValue($id, $value) {
        $value = $this->jsaddslashes($value);
        ?>$("*[input-id='<?php echo $id ?>'],*[id='<?php echo $id ?>']").val("<?php echo $value ?>");<?php
        return $this;
    }

    /**
     * Get a input html value
     * @param type $id
     * @return type
     */
    function getValue($id) {
        return $this->form[$id];
    }

    function value($id, $value = null) {
        if ($value == null) {
            return $this->getValue($id);
        } else {
            $this->setValue($id, $value);
            return $this;
        }
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function setHtml($id, $html) {
        $html = $this->jsaddslashes($html);
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").html("<?php echo $html ?>");<?php
        return $this;
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function html($id, $html) {
        $this->html($id, $html);
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function setText($id, $text) {
        $text = $this->jsaddslashes($text);
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").html("<?php echo $text ?>");<?php
        return $this;
    }
    
    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function append($id, $text) {
        $text = $this->jsaddslashes($text);
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").append("<?php echo $text ?>");<?php
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function text($id, $text) {
        $this->setText($id, $text);
        return $this;
    }

    /**
     * Enable a html element
     * @param type $id
     */
    function setEnable($id) {
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").removeAttr("disabled");<?php
        return $this;
    }

    function enable($id) {
        $this->setEnable($id);
        return $this;
    }

    /**
     * Disable a html element
     * @param type $id
     */
    function setDisable($id) {
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").attr("disabled",true);<?php
        return $this;
    }

    function disable($id) {
        $this->setDisable($id);
        return $this;
    }

    function show($id) {
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").show();<?php
        return $this;
    }

    function hide($id) {
        ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").hide();<?php
        return $this;
    }

    /**
     * Get a Instance of UploadParser
     * @param type $id
     * @return \UploadParser
     */
    function upload($id) {
        return new UploadParser($this->form[$id]);
    }

    private function jsaddslashes($s) {
        $o = "";
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $c = $s[$i];
            switch ($c) {
                case '<': $o.='\\x3C';
                    break;
                case '>': $o.='\\x3E';
                    break;
                case '\'': $o.='\\\'';
                    break;
                case '\\': $o.='\\\\';
                    break;
                case '"': $o.='\\"';
                    break;
                case "\n": $o.='\\n';
                    break;
                case "\r": $o.='\\r';
                    break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }

}

/**
 * Return a instance of upload parser
 * @param type $value
 * @return \UploadParser
 */
function uploadparser($value) {
    return new UploadParser($value);
}

class UploadParser {

    private $raw = "";
    private $name = "";

    function __construct($value) {
        ini_set("upload_max_filesize", "2048M");
        ini_set("post_max_size", "2048M");
        $d = explode("|filepkj|", $value);
        $this->raw = $d[1];
        $this->name = replace($d[0], "C:\\fakepath\\", "");
    }

    /**
     * Get a extension of file
     * @return string
     */
    function getExt() {
        $ext = explode("/", $this->getMime());
        $ext = $ext[1];
        if ($ext === "vnd.oasis.opendocument.spreadsheet") {
            $ext = "ods";
        } else if ($ext === "vnd.oasis.opendocument.text") {
            $ext = "odt";
        } else if ($ext === "plain") {
            $ext = "txt";
        } else if ($ext === "x-7z-compressed") {
            $ext = "7z";
        } else if ($ext === "x-rar") {
            $ext = "rar";
        }
        return $ext;
    }

    function getMime() {
        $tmp = explode(";", $this->raw);
        $tmp = $tmp[0];
        $mime = explode(":", $tmp);
        $mime = $mime[1];
        return $mime;
    }

    /**
     * Return base64 format of file
     * @return type
     */
    function getBase64() {
        $tmp = explode(";", $this->raw);
        $tmp = $tmp[1];
        $base = explode(",", $tmp);
        $base = $base[1];
        return $base;
    }

    /**
     * Get binary of file
     * @return type
     */
    function getData() {
        return base64_decode($this->getBase64());
    }

    /**
     * Try a get name of file
     * @return type
     */
    function getName() {
        return $this->name;
    }

    /**
     * Get my raw format dont use please 
     * @return type
     */
    function getRaw() {
        return $this->raw;
    }

    /**
     * Save data in a file 
     * @param type $fileName
     * @return boolean
     */
    function save($fileName = "") {
        if ($fileName == "") {
            $fileName = $this->getName();
        }
        if ($this->raw == "null") {
            return false;
        }
        if (strpos($fileName, ".") === false) {
            $fileName = "{$fileName}." . $this->getExt();
        }
        file_put_contents($fileName, $this->getData());
    }

}
