<?php

    function send_mail($to, $subject, $message){
        try {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: Camagru@example.com' . "\r\n";
            mail($to, $subject, $message, $headers);
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }
   
?>