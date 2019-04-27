<?php

/**
 * 
 * @param type $to
 * @param type $subject
 * @param type $message
 * @return type
 */
function mail2 ( $to , $subject , $message ) {
    $mail = new Mail();
    $mail->addAddress ( $to );
    $mail->Subject = $subject;
    $mail->Body = $message;
    foreach ( Mail::$mail2_files as $f ) {
        $mail->addAttachment ( $f[0] , $f[1] );
    }
    foreach ( Mail::$mail2_images as $i ) {
        $mail->addEmbeddedImage ( $i[0] , $i[1] );
    }
    return $mail->async_send ();
}

/**
 * 
 * @param type $imageCID array file,cid
 * @return type
 */
function mail2_image ( $file , $cid ) {
    Mail::$mail2_images[] = [$file , $cid];
}

/**
 * 
 * @param type $file array file,name
 * @return type
 */
function mail2_attachment ( $file , $name ) {
    Mail::$mail2_files[] = [$file , $name];
}

class Mail extends \PHPMailer\PHPMailer\PHPMailer {

    public static $mail2_files = [];
    public static $mail2_images = [];

    public static function deliveryAsync () {
        ob_start ();
        foreach ( glob ( 'pkj/storage/mail/*.json' ) as $email ) {
            $email_data = json_decode ( file_get_contents ( $email ) );
            $mail = new Mail();
            foreach ( $email_data as $key => $value ) {
                if ( ! in_array ( $key , ['address' , 'BCC' , 'CC'] ) ) {
                    $mail->{$key} = $value;
                }
            }
            foreach ( $email_data->address as $address ) {
                $mail->addAddress ( $address[0] , $address[1] );
            }
            foreach ( $email_data->BCC as $bcc ) {
                $mail->addBCC ( $bcc[0] , $bcc[1] );
            }
            foreach ( $email_data->CC as $cc ) {
                $mail->addCC ( $cc[0] , $cc[1] );
            }
            foreach ( $email_data->EmbeddedImage as $ei ) {
                $mail->addEmbeddedImage ( $ei[0] , $ei[1] , $ei[2] , $ei[3] , $ei[4] , $ei[5] );
            }
            foreach ( $email_data->Attachment as $a ) {
                $mail->addAttachment ( $a[0] , $a[1] , $a[2] , $a[3] , $a[4] );
            }
            $mail->SMTPDebug = 4;
            $mail->Debugoutput = 'html';
            if ( $mail->send () === false ) {
                echo $mail->ErrorInfo;
            }
            unlink ( $email );
        }
        debug ( ob_get_clean () );
    }

    public function __construct () {
        $this->isSMTP ( true );
        $this->Host = trim ( conf::$mail_host );
        $this->SMTPAuth = conf::$mail_auth;
        $this->Username = trim ( conf::$mail_username );
        $this->Password = conf::$mail_password;
        $this->SMTPSecure = conf::$mail_secure;
        $this->Port = conf::$mail_port;
        $this->CharSet = 'UTF-8';
        $this->setFrom ( conf::$mail_from , conf::$mail_name );
        $this->isHTML ( true );
    }

    public $address = [];
    public $BCC = [];
    public $CC = [];
    public $EmbeddedImage = [];
    public $Attachment = [];

    public function addAddress ( $address , $name = '' ) {
        $this->address[] = [$address , $name];
        parent::addAddress ( $address , $name );
    }

    public function addBCC ( $address , $name = '' ) {
        $this->BCC[] = [$address , $name];
        parent::addBCC ( $address , $name );
    }

    public function addCC ( $address , $name = '' ) {
        $this->CC[] = [$address , $name];
        parent::addCC ( $address , $name );
    }

    public function addEmbeddedImage ( $path , $cid , $name = '' , $encoding = self::ENCODING_BASE64 , $type = '' , $disposition = 'inline' ) {
        $this->EmbeddedImage[] = [$path , $cid , $name , $encoding , $type , $disposition];
        parent::addEmbeddedImage ( $path , $cid , $name , $encoding , $type , $disposition );
    }

    public function addAttachment ( $path , $name = '' , $encoding = self::ENCODING_BASE64 , $type = '' , $disposition = 'attachment' ) {
        $this->attachment[] = [$path , $name , $encoding , $type , $disposition];
        parent::addAttachment ( $path , $name , $encoding , $type , $disposition );
    }

    function async_send () {
        global $url;
        if ( ! file_exists ( 'pkj/storage' ) ) {
            mkdir ( 'pkj/storage' );
        }
        if ( ! file_exists ( 'pkj/storage/mail' ) ) {
            mkdir ( 'pkj/storage/mail' );
        }
        $this->SMTPDebug = 4;
        file_put_contents ( 'pkj/storage/mail/' . uniqueID () . '.json' , json_encode ( $this ) );
        $curl = curl_init ( $url . 'mail' );
        curl_setopt_array ( $curl , [
            CURLOPT_RETURNTRANSFER => true ,
            CURLOPT_TIMEOUT_MS => 100
        ] );
        curl_exec ( $curl ); // vai dar erro mesmo
        curl_close ( $curl );
    }

}
