<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends CI_Controller {

  public function FaleConosco() {
      $data['title'] = "LCI | Fale Conosco";
      $data['description'] = "Exercicio de exemplo do captulo 5 do Livro Codeigniter";

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
