<?php

/**
 * 
 * @return \Resource
 */
function resource() {
    if(conf::$resource === null){
        conf::$resource = new Resource();
    }
    return conf::$resource;
}


class Resource {

    public static $list = array();
    var $folder;
    var $resources;

    function csp() {
        ?>
        <!--<meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-eval' 'nonce-<?= conf::$random ?>'; media-src *"> -->
        <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-eval' 'unsafe-inline'; media-src *">
        <script nonce="<?= conf::$random ?>">var nonce = "<?= conf::$random ?>";</script>
        <?php
    }
    function tag_view(){
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
    }
    function __construct() {
        if (conf::$random === "") {
            conf::$random = rand(1, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(0, 9) . "" . rand(1, 9) . "";
        }
        $this->folder = pathinfo((new ReflectionClass("Resource"))->getFileName())["dirname"] . "/../../pkj/client";
        $this->resources = array();
        foreach (glob($this->folder . "/*/resource.json") as $value) {
            $tmp = json_decode(file_get_contents($value));
            $this->resources[$tmp->name] = $tmp;
        }
    }

    function import($name) {
        Resource::$list[] = $name;
        if (isset($_SERVER['HTTPS']) &&
                ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        $url = "{$protocol}://{$_SERVER['HTTP_HOST']}/client/pkj/$name/";

        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $url = pkj_get_home(__DIR__ . '../../') . "client/$name/";
        } else {
            $url = pkj_get_home(__DIR__ . '/../../') . "pkj/client/$name/";
        }
        $url = "{$protocol}://{$_SERVER['HTTP_HOST']}".$url;
        foreach ($this->resources[$name]->files as $value) {
            if (endswith($value, ".js")) {
                ?>
                <script type="text/javascript" src="<?= $url . $value ?>" ></script>
                <?php
            } else {
                //padrão link
                ?>
                <link rel="stylesheet" href="<?= $url . $value ?>" >
                <?php
            }
        }
        if (isset($this->resources[$name]->onload)):
            ?>
            <script nonce="<?= conf::$random ?>">
            <?php if ($name === "jquery"): ?>
                $(function () {
                    try {
                <?= $this->resources[$name]->onload ?>;
                    } catch (e) {
                        alert(e.message);
                    }
                });
            <?php elseif ($name === "onsen"): ?>
                ons.ready(function () {
                    try {
                <?= $this->resources[$name]->onload ?>;
                    } catch (e) {
                        alert(e.message);
                    }
                });
            <?php else: ?>
                if (!!window.cordova) {
                    document.addEventListener("deviceready", function () {
                        try {
                <?= $this->resources[$name]->onload ?>;
                        } catch (e) {
                            alert(e.message);
                        }
                    }, false);
                } else {
                    document.addEventListener("DOMContentLoaded", function () {
                        try {
                <?= $this->resources[$name]->onload ?>;
                        } catch (e) {
                            alert(e.message);
                        }
                    }, false);
                }
            <?php endif; ?>
            </script>
            <?php
        endif;
    }

}
