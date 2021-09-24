<?php
declare(strict_types = 1);

use Model\Comment_model;
use Model\Post_model;
use Model\User_model;
use System\Emerald\Exception\EmeraldModelNoDataException;

/**
 * Class Comment_controller
 */
class Comment_controller extends MY_Controller
{
    /**
     * Comment_controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    
    /**
     * @return object|string
     *
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
        
        try {
            $post = new Post_model($this->input->post('post_id'));
        } catch (EmeraldModelNoDataException $exception) {
            return $this->response_error('Post not found');
        }
        
        $comment_model = Comment_model::create(
            [
                'user_id' => $user->get_id(),
                'assign_id' => $this->input->post('post_id'),
                'reply_id' => $this->input->post('parent_comment_id'),
                'text' => $this->input->post('text'),
                'likes' => 0
            ]
        );
        
        return $this->response_success(['id' => $comment_model->get_id()]);
    }
    
    /**
     * @return bool
     */
    private function is_valid_payload(): bool
    {
        $this->form_validation->set_rules('post_id','post_id','required|integer');
        $this->form_validation->set_rules('text','text','required');
        $this->form_validation->set_rules('parent_comment_id','parent_comment_id','integer');
    
        return $this->form_validation->run();
    }
}
