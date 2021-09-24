<?php
declare(strict_types = 1);

use Model\User_model;

/**
 * Class Money_controller
 */
class Money_controller extends MY_Controller
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
    public function add_money()
    {
        $user = User_model::get_user();
        if (!$user->is_loaded()) {
            return $this->response_error('Unathorized');
        }
    
        if (!$this->is_valid_payload()) {
            return $this->response_error(validation_errors());
        }
        
        $sum = (float)$this->input->post('sum');
    
        App::get_s()->start_trans()->execute();
    
        try {
            $user->set_wallet_balance($user->get_wallet_balance() + $sum);
            $user->set_wallet_total_refilled($user->get_wallet_total_refilled() + $sum);
            
            App::get_s()->commit()->execute();
        } catch (Throwable $exception) {
            App::get_s()->rollback()->execute();
    
            return $this->response_error('Error exchanging likes');
        }
        
        return $this->response_success();
    }
    
    /**
     * @return object|string|void
     *
     * @throws Exception
     */
    public function exchange_money()
    {
        $user = User_model::get_user();
        if (!$user->is_loaded()) {
            return $this->response_error('Unathorized');
        }
    
        if (!$this->is_valid_payload()) {
            return $this->response_error(validation_errors());
        }
        
        $sumToConvert = (float)$this->input->post('sum');
        if ($sumToConvert > $user->get_wallet_balance()) {
            return $this->response_error('Not enough money to complete this operation');
        }
        
        App::get_s()->start_trans()->execute();
        try {
            $user->set_wallet_balance($user->get_wallet_balance() - $sumToConvert);
            $user->set_likes_balance($user->get_likes_balance() + $sumToConvert);
            $user->set_wallet_total_withdrawn($user->get_wallet_total_withdrawn() + $sumToConvert);
    
            App::get_s()->commit()->execute();
        } catch (Throwable $exception) {
            App::get_s()->rollback()->execute();
            
            return $this->response_error('Error exchanging likes');
        }
        
        return $this->response_success();
    }
    
    
    /**
     * @return bool
     */
    private function is_valid_payload(): bool
    {
        $this->form_validation->set_rules('sum','sum','required|numeric');
        
        return $this->form_validation->run();
    }
}
