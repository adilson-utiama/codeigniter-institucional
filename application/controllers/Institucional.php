<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institucional extends CI_Controller {

  //Cacheando todas as funcoes que retornam uma "view"
  public function __construct() {
      parent::__construct();
      $this->output->cache(1440);
  }

  public function index(){
      $data['title'] = "LCI - HOME";
      $data['description'] = "Exercicio de exemplo do capitulo 5 do livro Codeigniter";

      $this->load->view('commons/header', $data);
      $this->load->view('home');
      $this->load->view('commons/footer');
  }

  public function Empresa() {
      $data['title'] = "LCI - HOME";
      $data['description'] = "Exercicio de exemplo do capitulo 5 do livro Codeigniter";

      $this->load->view('commons/header', $data);
      $this->load->view('empresa');
      $this->load->view('commons/footer');
  }

  public function Servicos() {
      $data['title'] = "LCI - HOME";
      $data['description'] = "Exercicio de exemplo do capitulo 5 do livro Codeigniter";

      $this->load->view('commons/header', $data);
      $this->load->view('servicos');
      $this->load->view('commons/footer');
  }


}

?>
