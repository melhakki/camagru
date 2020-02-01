<?php

    Class Pages extends Controller {

        public function __construct()
        {
            
        }
        public function error(){
            $this->view('pages/error');
        }
       

    }