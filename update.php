<?php
if (!file_exists(".htaccess")) {
    ob_start();
    ?>
    php_value auto_prepend_file  "<?php echo __DIR__ ?>"
    #php_value auto_prepend_file  "/opt/lampp/htdocs/webpkj/pkj/server/pkjall.php"
    php_value output_buffering 0
    php_value date.timezone 'America/Sao_Paulo'
    setenv pkj_dateformat "d/m/Y"

    #setenv pkj_servidor "postgre"
    #setenv pkj_endereco "localhost"
    #setenv pkj_usuario  "postgres"
    #setenv pkj_senha  "123"
    #setenv pkj_base  "webpkj"

    setenv pkj_servidor "sqlite"
    setenv pkj_endereco "../../banco.db"
    setenv pkj_usuario  ""
    setenv pkj_senha  ""
    setenv pkj_base  ""

    #setenv pkj_servidor "mysql"
    #setenv pkj_endereco "localhost"
    #setenv pkj_usuario  "root"
    #setenv pkj_senha  ""
    #setenv pkj_base  "webpkj"


    setenv pkj_sessao  "database"
    <?php
    $ht = ob_get_clean();
    file_put_contents(".htaccess", $ht);
}
system("git clone https://github.com/FelipeAzambuja/webpkj.git");