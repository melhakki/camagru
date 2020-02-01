<?php

    require_once 'config/setup.php';

    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';
    require_once 'helpers/mail_helper.php';

    spl_autoload_register(function($className){
        require_once 'libraries/' . $className . '.php';
    });

 
