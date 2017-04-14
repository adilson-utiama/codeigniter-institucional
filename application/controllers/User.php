<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(array('form_validation'));
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
        $config = $this->PaginationConfiguration();
        $this->pagination->initialize($config);

        if($this->uri->segment(2)) {
            $data['testeValor'] = 'Teste: ' . $this->uri->segment(2);
            //$offset = ($this->uri->segment(2) - 1) * $config['per_page'];
            $offset = 1;
        } else {
            $offset = 0;
        }

        $urls = $this->Urls_model->getAllByPage($this->session->userdata('id'), $config['per_page'], $offset);

        $data['urls'] = $urls;
        $data['error'] = null;
        $data['short_url'] = false;
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('short-urls/minhas-urls', $data);
    }

    private function PaginationConfiguration() {
        $config['base_url'] = base_url('short-urls/minhas-urls');
        $config['total_rows'] = $this->db->select('*')->from('urls')->where('user_id',$this->session->userdata('id'))->count_all_results();
        $config['per_page'] = 5;
        $config['uri_segment'] = 2;
        $config['num_links'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = "<nav><ul class='pagination'>";
        $config['full_tag_close'] = "</ul></nav>";
        $config['first_link'] = "Primeira";
        $config['first_tag_open'] = "<li>";
        $config['first_tag_close'] = "</li>";
        $config['last_link'] = "Última";
        $config['last_tag_open'] = "<li>";
        $config['last_tag_close'] = "</li>";
        $config['next_link'] = "Próxima";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_link'] = "Anterior";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tag_close'] = "</li>";
        $config['cur_tag_open'] = "<li class='active'><a href='#'>";
        $config['cur_tag_close'] = "</a></li>";
        $config['num_tag_open'] = "<li>";
        $config['num_tag_close'] = "</li>";

        return $config;
    }




}
