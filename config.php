<?php
if ($_SERVER['SERVER_NAME'] === 'newbgp.com.br') {
    conf::$dateFormat = 'd/m/Y';
    conf::$servidor = 'mysql';
    conf::$endereco = 'localhost';
    conf::$usuario = 'newbgp_test';
    conf::$senha = 'naosouumasenha';
    conf::$base = 'newbgp_test';
    conf::$session = 'database';
} else {
    conf::$dateFormat = 'd/m/Y';
    conf::$servidor = 'sqlite';
    conf::$endereco = '../../banco';
    conf::$usuario = 'root';
    conf::$senha = '';
    conf::$base = '';
    conf::$session = 'database';
}
