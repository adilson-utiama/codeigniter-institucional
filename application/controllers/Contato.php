<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends CI_Controller {

  public function __construct() {
      parent::__construct();
      $this->load->library(array('form_validation', 'session'));
      $this->load->helper('form');
      $this->load->helper('captcha');
  }


  public function FaleConosco() {
      $data['title'] = "LCI | Fale Conosco";
      $data['description'] = "Exercicio de exemplo do captulo 5 do Livro Codeigniter";

      //Regra de validação
      $this->form_validation->set_rules($this->RegrasValidacao());
      // $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[3]');
      // $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
      // $this->form_validation->set_rules('assunto', 'Assunto', 'trim|required|min_length[5]');
      // $this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required|min_length[30]');

      if($this->form_validation->run() == FALSE) {
          $data['formErrors'] = validation_errors();
      } else {
          $formData = $this->input->post();  //recupera os dados enviados pelo formulario
          $emailStatus = $this->SendEmailToAdmin(
              $formData['email'],
              $formData['nome'],
              "to@domain.com",
              "To name",
              $formData['assunto'],
              $formData['mensagem'],
              $formData['email'],
              $formData['nome']
          );

          if($emailStatus) {
              $this->session->set_flashdata('success_msg', 'Contato recebido com sucesso!');
              $data['formErrors'] = null;
          } else {
              $data['formErrors'] = "Desculpe! Não foi possivel enviar o seu contato. Tente novamente mais tarde";
          }




      }
      $data['captcha_image'] = $this->GenerateCaptcha();

      $this->load->view('commons/header', $data);
      $this->load->view('fale-conosco');
      $this->load->view('commons/footer');
  }

  public function TrabalheConosco() {
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
          $uploadCurriculo = $this->UploadFile('curriculo');

          if($uploadCurriculo['error']) {
              $data['formErrors'] = $uploadCurriculo['message'];
          } else {
              $formaData = $this->input->post();
              $emailStatus = $this->SendEmailToAdmin(
                  $formData['email'],
                  $formData['nome'],
                  "to@domain.com",
                  "To name",
                  $formData['assunto'],
                  $formData['mensagem'],
                  $formData['email'],
                  $formData['nome'],
                  $uploadCurriculo['filedata']['full_path']
              );

              if($emailStatus) {
                  $this->session->set_flashdata('success_msg', 'Contato recebido com sucesso!');
                  $data['formErrors'] = null;
              } else {
                  $data['formErrors'] = "Desculpe! Não foi possivel enviar o seu contato. Tente novamente mais tarde";
              }
          }
      }

      $this->load->view('commons/header', $data);
      $this->load->view('trabalhe-conosco');
      $this->load->view('commons/footer');
  }

  //Validando o captcha
  public function captcha_check($string) {
      //Comprara a String informada no campo com a variavel de sessao previamente armazenada
      if($string === $this->session->userdata('user_captcha_value')) {
          return TRUE;
      } else {
        $this->form_validation->set_message('captcha_check', 'O captcha informado está incorreto.');
          return FALSE;
      }
  }

  private function UploadFile($inputFileName) {
      $this->load->library('uplload'); //carregando a biblioteca 'upload' do CodeIgniter

      $path = "../curriculos";

      //Configuracoes do upload
      $config['upload_path'] = $path;
      $config['allowed_types'] = 'doc|docx|pdf|zip|rar';
      $config['max_size'] = '5120';
      $config['encrypt_name'] = TRUE;

      //Verifica se o diretorio existe, caso nao exista sera criado.
      if(!is_dir($path)) {
          mkdir($path, 0777, $recursive = true);
      }

      $this->upload->initialize($config);

      if(!$this->upload->do_upload($inputFileName)) {
          $data['error'] = true;
          $data['message'] = $this->upload->display_errors();
      } else {
          $data['error'] = false;
          $data['filedata'] = $this->upload->data();
      }

      return $data;
  }

  private function SendEmailToAdmin($from, $fromName, $to, $toName, $subject, $message, $reply = null, $replyName = null, $attach = null) {
      $this->load->library('email');  //carregando a biblioteca 'email' do CodeIgniter

      //Configuraçoes de envio do email
      $config['charset'] = 'utf-8';
      $config['wordwrap'] =  TRUE;
      $config['mailtype'] = 'html';
      $config['protocol'] = 'smtp';
      $config['smtp_host'] = 'smtp.seudominio.com.br';
      $config['smtp_user'] = 'user@seudominio.com.br';
      $config['smtp_pass'] = 'senha';
      $config['newline'] = '\r\n';

      $this->email->initialize($config); //Inicializa a biblioteca

      $this->email->from($from, $fromName);
      $this->email->to($to, $toName);

      if(reply) {
        $this->email->reply_to($reply, $replyName);
      }

      if($attach) {
          $this->email->attach($attach);
      }

      $this->email->subject($subject);
      $this->email->message($message);

      if($this->email->send()) {
          return true;
      } else {
          return false;
      }
  }

  private function RegrasValidacao() {
      return $rules = array(
          array('field' => 'nome', 'label' => 'Nome', 'rules' => 'trim|required|min_length[4]',
              array(
                'required' => 'O campo {field} é Obrigatório.',
                'min_length' => 'O campo {field} não pode ter menos de {param} caracteres'
              )),
          array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
          array('field' => 'assunto', 'label' => 'Assunto', 'rules' => 'trim|required|min_length[5]'),
          array('field' => 'mensagem', 'label' => 'Mensagem', 'rules' => 'trim|required|min_length[5]'),
          array('field' => 'captcha', 'label' => 'Captcha', 'rules' => 'trim|required|callback_captcha_check') //Chama o metodo captcha_check
      );
  }

  private function GenerateCaptcha() {
    $vals = array(
              'img_path' => './captcha/',
              'img_url' => base_url('captcha')
            );
    //Gera o captcha
    $captcha = create_captcha($vals);
    //Armazena valor do captcha na sessao
    $this->session->set_userdata('user_captcha_value', $captcha['word']);
    //Retorna a imagem do captcha
    return $captcha['image'];
  }

}

 ?>
