<?php
declare(strict_types = 1);

use Model\User_model;
use Service\LikeModelRetriever;

/**
 * Class Like_controller
 */
class Like_controller extends MY_Controller
{
    /**
     * Like_controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    
    /**
     * @return object|string|void
     * @throws Exception
     */
    public function create()
    {
        $user = User_model::get_user();
        if (!$user->is_loaded()) {
            return $this->response_error('Unathorized');
        }
    
        if (!$this->is_valid_payload()) {
            return $this->response_error(validation_errors());
        }
        
        $likeModelFactory = new LikeModelRetriever();
        $entityModel = $likeModelFactory->make(
            $this->input->post('entity'),
            (int)$this->input->post('entity_id')
        );
        
        if (null === $entityModel) {
            return $this->response_error('entity id not found');
        }
    
        App::get_s()->start_trans()->execute();
        try {
            $user->set_likes_balance($user->get_likes_balance() - 1);
            $entityModel->like();
    
            App::get_s()->commit()->execute();
        } catch (Throwable $exception) {
            App::get_s()->rollback()->execute();
    
            return $this->response_error($exception->getMessage());
        }
        
        return $this->response_success();
    }
    
    /**
     * @return bool
     */
    private function is_valid_payload(): bool
    {
        $this->form_validation->set_rules('entity','entity','required');
        $this->form_validation->set_rules('entity_id','entity_id','integer');
        
        return $this->form_validation->run();
    }
}
