<?php
set_time_limit ( 0 );
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
if(true){
    $url = "https://github.com/FelipeAzambuja/webpkj/archive/master.zip";
    $data = file_get_contents($url);
    file_put_contents("master.zip",$data);
    $data = null;
}
if(true){
    $zip = new ZipArchive;
    $res = $zip->open("master.zip");
    $zip->extractTo(".");
    $zip->close();
}

	

function xcopy( $source, $target ) {
    if ( is_dir( $source ) ) {
        @mkdir( $target );
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' ) {
                continue;
            }
            $Entry = $source . '/' . $entry; 
            if ( is_dir( $Entry ) ) {
                xcopy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }

        $d->close();
    }else {
        copy( $source, $target );
    }
}
function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
} 
if(true){
    xcopy("webpkj-master/pkj","pkj");
}

unlink("master.zip");
rrmdir("webpkj-master");
echo "update done";

