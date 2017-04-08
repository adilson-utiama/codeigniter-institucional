<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institucional extends CI_Controller {

    public function index(){
        $data['title'] = "LCI - HOME";
        $data['description'] = "Exercicio de exemplo do capitulo 5 do livro Codeigniter";

        $this->load->view('home', $data);
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
