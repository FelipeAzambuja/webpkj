<?php
if ($_SERVER['SERVER_NAME'] === 'newbgp.com.br') {
    conf::$dateFormat = 'd/m/Y';
    conf::$local = 'pt_BR';
    conf::$servidor = 'mysql';
    conf::$endereco = 'localhost';
    conf::$usuario = 'newbgp_webpkj';
    conf::$senha = '123';
    conf::$base = 'newbgp_webpkj';
    conf::$session = 'database';
} else {
    conf::$local = 'pt_BR';
    conf::$dateFormat = 'd/m/Y';
    conf::$servidor = 'sqlite';
    conf::$endereco = '../../banco.sqlite';
    conf::$usuario = 'root';
    conf::$senha = '';
    conf::$base = '';
    conf::$session = 'database';
}
