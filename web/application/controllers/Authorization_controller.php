<?php
declare(strict_types = 1);

use Model\User_model;
use Repositories\UserRepository;
use Repositories\UserRepositoryInterface;

/**
 * Class Authorization_controller
 */
class Authorization_controller extends MY_Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    
    /**
     * Login_controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        
        $this->userRepository = new UserRepository($this->db);
    }
    
    /**
     * @return object|string
     * @throws Exception
     */
    public function login()
    {
        if ($this->validate_payload()) {
            return $this->response_error(validation_errors());
        }
        
        $username = $this->input->post('login');
        $password = $this->input->post('password');
        
        $result = $this->userRepository->findByLoginAndPassword($username, $password);
        
        if (!$result->is_loaded()) {
            return $this->response_error('Invalid credentials.');
        }
        
        $this->session->set_userdata('id', $result->get_id());

        return $this->response_success();
    }
    
    /**
     * @return object|string|void
     *
     * @throws Exception
     */
    public function logout()
    {
        $user = User_model::get_user();
        if (!$user->is_loaded()) {
            return $this->response_success();
        }
        
        $this->session->flashdata($user->get_id());
    
        return $this->response_success();
    }
    
    /**
     * @return bool
     */
    private function validate_payload()
    {
        $this->form_validation->set_rules('login','Login','required');
        $this->form_validation->set_rules('password','Password','required');
        
        return !$this->form_validation->run();
    }
}
