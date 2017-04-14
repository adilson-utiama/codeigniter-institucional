<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(array('form_validation', 'session'));
    }

    public function Login() {
        $this->form_validation->set_rules('email', 'Email', 'required|min_length[1]|valid_email|trim');
        $this->form_validation->set_rules('passw', 'Senha', 'required|min_length[6]|trim');
        if($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
        } else {
            $dataLogin = $this->input->post();
            $res = $this->User_model->Login($dataLogin);
            if($res) {
              foreach($res as $result) {
                  if(password_verify($dataLogin['passw'], $result->passw)) {
                      $data['error'] = null;
                      $this->session->set_userdata('logged', true);
                      $this->session->set_userdata('email', $result->email);
                      $this->session->set_userdata('id', $result->id);
                      redirect('short-urls/home');
                  } else {
                      $data['error'] = 'Senha Incorreta';
                  }
              }
            }
        }

        $this->load->view('short-urls/login', $data);
    }

    public function Logout() {
        $this->session->unset_userdata('logged');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('id');
        redirect('short-urls/home');
    }

    public function Register() {
        $this->form_validation->set_rules('name', 'Nome', 'required|min_length[3]|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|min_length[1]|valid_email|trim');
        $this->form_validation->set_rules('passw', 'Senha', 'required|min_length[6]|trim');

        if($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
        } else {
            $dataRegister = $this->input->post();
            $res = $this->User_model->Save($dataRegister);
            if($res) {
                $data['error'] = null;
            } else {
              $data['error'] = 'Não foi possivel criar o Usuario.';
            }
        }

        if($data['error']) {
            $this->load->view('short-urls/login', $data);
        } else {
            $this->session->set_userdata('logged', true);
            $this->session->set_userdata('email', $res->email);
            $this->session->set_userdata('id', $res->id);
            redirect('short-urls/home');
        }
    }

    public function UpdatePassw() {
        $data['success'] = null;
        $data['error'] = null;
        $this->form_validation->set_rules('passw', 'Senha', 'required|min_length[6]|trim');

        if($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
        } else {
            $data = $this->input->post();
            $this->User_model->Update($data);
            $data['success'] = 'Senha alterada com Sucesso.';
            $data['error'] = null;
        }

        $data['user'] = $this->User_model->GetUser($this->session->userdata('id'));

        $this->load->view('short-urls/alterar-senha', $data);
    }

    public function Urls() {
        $this->load->model('Urls_model');
        $urls = $this->Urls_model->getAllByuser($this->session->userdata('id'));

        $data['urls'] = $urls;
        $data['error'] = null;
        $data['short_url'] = false;

        $this->load->view('short-urls/minhas-urls', $data);
    }




}
