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
    conf::$local = 'pt-BR';
    conf::$dateFormat = 'd/m/Y';
    conf::$servidor = 'sqlite';
    conf::$endereco = '../../banco.sqlite';
    conf::$usuario = 'root';
    conf::$senha = '';
    conf::$base = '';
    conf::$session = 'database';
}
//https://toolheap.com/test-mail-server-tool/users-manual.html
conf::$mail_host = 'localhost';
conf::$mail_username = 'felipe@newbgp.com.br';
conf::$mail_from = 'felipe@newbgp.com.br';
conf::$mail_name = 'Felipe';
conf::$mail_password = '';
conf::$mail_secure = '';
conf::$mail_port = 25;
conf::$mail_auth = false;
