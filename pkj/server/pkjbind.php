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
    if (isset($_POST["PAGE"])) {
        if (len($_POST["PAGE"]) > 0) {
            $home = replace(conf::$pkjHome, "/pkj", "");
            if (startswith($_POST["PAGE"], $home)) {
                $pagina = __DIR__ . "/../../" . replace($_POST["PAGE"], $home, "");
            } else {
                $pagina = __DIR__ . "/../../" . $_POST["PAGE"];
            }

            show_errors(true);
            ob_start();
            require_once $pagina;
            ob_end_clean();
        }
    }
    $tmp2 = $_POST;
    addslashes_array($tmp2);
    unset($tmp2["CMD"]);
    unset($tmp2["PAGE"]);
    unset($tmp2["HOST"]);
    if ($tmp2["post0"] === "") {
        unset($tmp2["post0"]);
    }
    try {
        if (function_exists($cmd)) {
//            if (isset($_POST["MUSTACHE"])) {
//                call_user_func($cmd, $tmp2, $_POST["MUSTACHE"]);
//            } else {
            call_user_func($cmd, $tmp2);
//            }
        } else {
            console("Função não existe");
        }
    } catch (Throwable $t) {
        ?>
        console.error("<?php echo JS::addslashes($t->getFile() . ":" . $t->getLine() . "\n" . $t->getMessage()) ?>");
        console.error("<?php echo JS::addslashes($t->getTraceAsString()) ?>");
        <?php
        exit();
    } catch (Exception $exc) {
        ?>
        console.log("<?php echo JS::addslashes($exc->getTraceAsString()) ?>");
        <?php
    } finally {
        
    }
    bindUpdate();
    exit();
}

function __normalizePath($path) {
    $parts = array(); // Array to build a new path from the good parts
    $path = str_replace('\\', '/', $path); // Replace backslashes with forwardslashes
    $path = preg_replace('/\/+/', '/', $path); // Combine multiple slashes into a single slash
    $segments = explode('/', $path); // Collect path segments
    $test = ''; // Initialize testing variable
    foreach ($segments as $segment) {
        if ($segment != '.') {
            $test = array_pop($parts);
            if (is_null($test))
                $parts[] = $segment;
            else if ($segment == '..') {
                if ($test == '..')
                    $parts[] = $test;

                if ($test == '..' || $test == '')
                    $parts[] = $segment;
            }
            else {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode('/', $parts);
}

/**
 * New instance of OnsSlitter
 * @param type $id
 * @return \OnsSlitter
 */
function ons_splitter($id) {
    return new OnsSplitter($id);
}

class OnsSplitter {

    var $id;

    function __construct($id) {
        $this->id = $id;
    }

    function load($page, $done = "") {
        ?>document.getElementById("<?php echo $this->id ?>").load("<?php echo $page ?>").then(function () {bindRefresh(); <?php if ($done != ""): ?>bindCall("<?php echo $_POST["PAGE"] ?>", "<?php echo $done ?>", {});<?php endif; ?>});<?php
        bindUpdate();
    }

}

/**
 * New instance of OnsNavigator
 * @param type $id
 * @return \OnsNavigator
 */
function ons_navigator($id) {
    return new OnsNavigator($id);
}

class OnsNavigator {

    var $id;

    //TODO Implementar metodos do onsen
    function __construct($id) {
        $this->id = $id;
    }

    function pushPage($page, $done = "") {
        ?>document.getElementById("<?php echo $this->id ?>").pushPage("<?php echo $page ?>").then(function () {  bindRefresh();  <?php if ($done != ""): ?>bindCall("<?php echo $_POST["PAGE"] ?>", "<?php echo $done ?>", {}); <?php endif; ?>});<?php
        bindUpdate();
    }

    function resetToPage($page, $done = "") {
        ?>document.getElementById("<?php echo $this->id ?>").resetToPage("<?php echo $page ?>").then(function () {  bindRefresh();  <?php if ($done != ""): ?>bindCall("<?php echo $_POST["PAGE"] ?>", "<?php echo $done ?>", {}); <?php endif; ?>});<?php
        bindUpdate();
    }

}

/**
 * New Ons modal
 * @param type $id
 * @return \OnsModal
 */
function ons_modal($id) {
    return new OnsModal($id);
}

class OnsModal {

    var $id;

    function __construct($id) {
        $this->id = $id;
    }

    function show() {
        ?>document.getElementById('<?php echo $this->id ?>').show();<?php
    }

    function hide() {
        ?>document.getElementById('<?php echo $this->id ?>').hide();<?php
    }

    function toggle() {
        ?>document.getElementById('<?php echo $this->id ?>').toggle();<?php
    }

}

/**
 * New Ons modal
 * @param type $id
 * @return \OnsModal
 */
function ons_dialog($id) {
    return new OnsDialog($id);
}

class OnsDialog {

    var $id;

    function __construct($id) {
        $this->id = $id;
    }

    function show() {
        ?>document.getElementById('<?php echo $this->id ?>').show();<?php
    }

    function hide() {
        ?>document.getElementById('<?php echo $this->id ?>').hide();<?php
    }

    function toggle() {
        ?>document.getElementById('<?php echo $this->id ?>').toggle();<?php
    }

}

class JS {

//TODO implementar javascript
    public static function alert($mensagem) {
        ?>alert("<?php echo JS::addslashes($mensagem) ?>");<?php
    }

    public static function console($mensagem) {
        if (is_array($mensagem)) {
            ?>console.log(<?php echo json_encode($mensagem) ?>);<?php
        } else {
            ?>console.log("<?= JS::addslashes($mensagem) ?>");<?php
        }
    }

    //trocar 
    public static function popup($mensagem, $id = "") {
        ?>popup("<?php echo JS::addslashes($mensagem) ?>");<?php
    }

    public static function popup_close($id = "") {
        ?>popup_close("<?= $id ?>");<?php
    }

    public static function redirect($pagina, $data = "") {
        if ($data !== "") {
            $data = "?" . http_build_query($data);
        }
        ?>window.location.href="<?= $pagina . $data ?>";<?php
    }

    public static function _addslashes($s) {

        $l = strlen($s);
        for ($i = 0; $i < $l; $i ++) {
            switch ($s[$i]) {
                case '\\': // \
                    $s = substring($s, 0, $i) . '\\\\' . substring($s, $i + 1);
                    $i = $i + 1;
                    break;
                case '"': // "
                    $s = substring($s, 0, $i) . '\\"' . substring($s, $i + 1);
                    $i = $i + 1;
                    break;
            }
        }
        $s = str_replace(PHP_EOL, '\n', $s);
        return $s;
    }

    public static function addslashes($s) {
        if (is_array($s)) {
            $s = json_encode($s);
        }
        return '"+' . str_replace(PHP_EOL, '\n', json_encode($s)) . '+"';
//        $s = str_replace('*/', '* /', $s);//buaaa
        return '"+_heredoc(function(){/* ' . $s . ' */})+"';
//        $a = array('<','>','\'','\\','"','\n','\r',PHP_EOL);
//        $b = array('\\x3C','\\x3E','\\\'','\\\\','\\"','\\n','\\r','');
//        return str_replace($a, $b, $s);
        $s = str_replace(PHP_EOL, '', $s);
        $l = strlen($s);
        for ($i = 0; $i < $l; $i ++) {
            switch ($s[$i]) {
                case '\\': // \
                    $s = substring($s, 0, $i) . '\\\\' . substring($s, $i + 1);
                    $i = $i + 1;
                    break;
                case '"': // "
                    $s = substring($s, 0, $i) . '\\"' . substring($s, $i + 1);
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
function bind($form = array()) {
    return new Bind($form);
}

function bindUpdate() {
    bind(array())->update();
}

function mustache($id, $html, $data = array()) {
    $html = JS::addslashes($html);
    ?>
    var bind_tmp1 = Mustache.render("<?php echo $html ?>", <?php echo json_encode($data) ?>);
    Mustache.parse(bind_tmp1);
    $("*[id='<?php echo $id ?>']").html(bind_tmp1);
    <?php
    bindUpdate();
}

class Bind {

    var $ids;
    var $form;

    function __construct($form) {
        $this->form = $form;
    }

    function update() {
        ?>if (typeof (PKJ) === "undefined") {$("*").removeAttr("bind");bindRefresh();}else{PKJ.refresh();}tagUpdate();<?php
    }

    /**
     * Set value to html input
     * @param type $id
     * @param type $value
     * @return \Bind
     */
    function setValue($id, $value) {
//        $value = JS::addslashes($value);
        $value = str_replace('*/', '* /', $value); //buaaa
        $this->jquery($id, "val( _heredoc(function(){/* {$value} */}) )");
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

        $html = JS::addslashes($html);
        $this->jquery($id, "html(\"$html\")");
        return $this;
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function html($id, $html) {
        $this->setHtml($id, $html);
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function setText($id, $text) {
        $text = JS::addslashes($text);
        $this->jquery($id, "text(\"$text\")");
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function append($id, $text) {
        $text = JS::addslashes($text);
        $this->jquery($id, "append(\"$text\")");
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
        $this->jquery($id, "removeAttr(\"disabled\")");
        return $this;
    }

    function enable($id) {
        $this->setEnable($id);
        return $this;
    }

    function focus($id) {
        $this->jquery($id, "focus()");
        return $this;
    }

    /**
     * Disable a html element
     * @param type $id
     */
    function setDisable($id) {
        $this->jquery($id, "attr(\"disabled\",\"true\")");
        return $this;
    }

    function disable($id) {
        $this->setDisable($id);
        return $this;
    }

    function show($id) {
        $this->jquery($id, "show()");
        return $this;
    }

    function hide($id) {
        $this->jquery($id, "hide()");
        return $this;
    }

    function attr($id, $name, $value) {
        $value = JS::addslashes($value);
        $this->jquery($id, "attr(\"$name\",\"$value\")");
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

    function setInterval($function, $time, $parameters = array(), $page = "") {
        if (isset($_POST["PAGE"]) && $page === "") {
            $page = $_POST["PAGE"];
        }
        ?>
        eventos["<?= $function ?>"] = setInterval(function () {
        bindCall("<?= $page ?>","<?= $function ?>",<?= json_encode($parameters, JSON_FORCE_OBJECT) ?>)
        },<?= $time ?>);
        <?php
    }

    function setTimeout($function, $time, $parameters = array(), $page = "") {
        if (isset($_POST["PAGE"]) && $page === "") {
            $page = $_POST["PAGE"];
        }
        ?>
        eventos["<?= $function ?>"] = setTimeout(function () {
        bindCall("<?= $page ?>","<?= $function ?>",<?= json_encode($parameters, JSON_FORCE_OBJECT) ?>)
        },<?= $time ?>);
        <?php
    }

    function stopInterval($function) {
        ?>clearInterval(eventos["<?= $function ?>"])<?php
    }

    function stopTimeout($function) {
        ?>clearTimeout(eventos["<?= $function ?>"])<?php
    }

    /**
     * Send focus
     * @param type $id id 
     * @return type this
     */
    function setFocus($id) {
        return $this->jquery($id, "focus()");
    }

    function autocomplete($id, $values) {
        $values = json_encode($values);
        jquery($id, "attr('data-autocomplete','$values')");
    }

    function combo($id, $values, $names = []) {
        $html = "";
        if ($names === []) {
            $names = $values;
        }
        for ($index = 0; $index < count($values); $index++) {
            $v = $values[$index];
            $n = $names[$index];
            $html .= "<option value='$v'>$n</option>";
        }
        html($id, $html);
    }

    /**
     * Force a jquery code
     * @param type $id
     * @param type $code
     */
    function jquery($id, $code) {
        if (startswith($id, "#")) {
            $id = replace($id, "#", "");
            ?>$("*[id='<?php echo $id ?>'],*[input-id='<?php echo $id ?>']").<?php echo $code ?>;<?php
        } else {
            ?>$("*[<?php echo $id ?>]").<?php echo $code ?>;<?php
        }
        return $this;
    }

    /**
     * 
     * @param string $message
     * @param string $type error,success,info,warn
     */
    function notify($message, $type = 'success') {
        ?>$.notify("<?= JS::addslashes($message) ?>",'<?= $type ?>')<?php
    }

}

/**
 * Return a instance of upload parser
 * @param type $value
 * @return \UploadParser
 */
function upload_parser($value) {
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

function c($v) {
    ob_start();
    ~d($v);
    console(ob_get_clean());
}

function cd($v) {
    c($v);
    exit();
}

/**
 * 
 * @param string $name
 * @return \Page
 */
function page($name = "", $outputElement = "") {
    return new Page($name, $outputElement);
}

class Page {

    private $name;
    private $outputElement;

    public function __construct($name = "", $outputElement) {
        $this->name = $name;
        $this->outputElement = $outputElement;
    }

    function back($data = array()) {
        ?> page.back(<?= json_encode($data) ?> ); <?php
        return $this;
    }

    function go($data = array()) {
        ?> page.go('<?= $this->name ?>','<?= $this->outputElement ?>',<?= json_encode($data) ?> ); <?php
        return $this;
    }

    function update($data) {
        ?> page.update('<?= $this->name ?>','<?= $this->outputElement ?>',<?= json_encode($data) ?> ); <?php
        return $this;
    }

    //implementar todos os metodos do bind

    /**
     * Set value to html input
     * @param type $id
     * @param type $value
     * @return \Bind
     */
    function setValue($id, $value) {
//        $value = JS::addslashes($value);
        $value = str_replace('*/', '* /', $value); //buaaa
        $this->jquery($id, "val( _heredoc(function(){/*  {$value}  */}) )");
        return $this;
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function setHtml($id, $html) {
        $html = JS::addslashes($html);
        $this->jquery($id, "html(\"$html\")");
        return $this;
    }

    /**
     * Force a inner html values
     * @param type $id
     * @param type $html
     */
    function html($id, $html) {
        $this->setHtml($id, $html);
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function setText($id, $text) {
        $text = JS::addslashes($text);
        $this->jquery($id, "text(\"$text\")");
        return $this;
    }

    /**
     * Set text to html element
     * @param type $id
     * @param type $text
     */
    function append($id, $text) {
        $text = JS::addslashes($text);
        $this->jquery($id, "append(\"$text\")");
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
        $this->jquery($id, "removeAttr(\"disabled\")");
        return $this;
    }

    function enable($id) {
        $this->setEnable($id);
        return $this;
    }

    function focus($id) {
        $this->jquery($id, "focus()");
        return $this;
    }

    /**
     * Disable a html element
     * @param type $id
     */
    function setDisable($id) {
        $this->jquery($id, "attr(\"disabled\",\"true\")");
        return $this;
    }

    function disable($id) {
        $this->setDisable($id);
        return $this;
    }

    function show($id) {
        $this->jquery($id, "show()");
        return $this;
    }

    function hide($id) {
        $this->jquery($id, "hide()");
        return $this;
    }

    /**
     * Send focus
     * @param type $id id 
     * @return type this
     */
    function setFocus($id) {
        return $this->jquery($id, "focus()");
    }

    function autocomplete($id, $values) {
        $values = json_encode($values);
        jquery($id, "attr('data-autocomplete','$values')");
    }

    function combo($id, $values, $names = []) {
        $html = "";
        if ($names === []) {
            $names = $values;
        }
        for ($index = 0; $index < count($values); $index++) {
            $v = $values[$index];
            $n = $names[$index];
            $html .= "<option value='$v'>$n</option>";
        }
        html($id, $html);
    }

    /**
     * Force a jquery code
     * @param type $id
     * @param type $code
     */
    function jquery($id, $code) {
        $page = "div[load-page='{$this->name}']";
        if (startswith($id, "#")) {
            $id = replace($id, "#", "");
            ?>$("<?= $page ?>").find("*[id='<?= $id ?>'],*[input-id='<?= $id ?>']").<?= $code ?>;<?php
        } else {
            ?>$("<?= $page ?>").find("*[<?php echo $id ?>]").<?php echo $code ?>;<?php
        }
        return $this;
    }

}
