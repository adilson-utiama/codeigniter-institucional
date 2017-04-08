<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends CI_Controller {

  public function __construct() {
      parent::__construct();
      $this->load->library(array('form_validation', 'session'));
      $this->load->helper('form');
  }

  public function FaleConosco() {
      $data['title'] = "LCI | Fale Conosco";
      $data['description'] = "Exercicio de exemplo do captulo 5 do Livro Codeigniter";

      //Regra de validação
      $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[3]');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
      $this->form_validation->set_rules('assunto', 'Assunto', 'trim|required|min_length[5]');
      $this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required|min_length[30]');

      if($this->form_validation->run() == FALSE) {
          $data['formErrors'] = validation_errors();
      } else {
          $this->session->set_flashdata('success_msg', 'Contato recebido com sucesso!');
          $data['formErrors'] = null;
      }

      $this->load->view('commons/header', $data);
      $this->load->view('fale-conosco');
      $this->load->view('commons/footer');
  }

  public function TrabalheConosco() {
      $data['title'] = "LCI | Fale Conosco";
      $data['description'] = "Exercicio de exemplo do captulo 5 do Livro Codeigniter";

      $this->load->view('commons/header', $data);
      $this->load->view('trabalhe-conosco');
      $this->load->view('commons/footer');
  }

}

 ?>
