<?php
declare(strict_types = 1);

class Login_controller extends MY_Controller
{
    public function login()
    {
        $this->input->post('username');
        $this->input->post('password');
        
        
    }
}
