<?php
class Enviador extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("email");
	}

	public function sendMailGmail()
	{
		//cargamos la libreria email de ci
		$this->load->library("email");

		//configuracion para gmail
		$configGmail = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'trazabilidad.rsu@gmail.com',
			'smtp_pass' => 'MAserver09042014',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);    

		//cargamos la configuración para enviar con gmail
		$this->email->initialize($configGmail);

		$this->email->from('trazabilidad.rsu@gmail.com');
		$this->email->to("diegochecarellis@gmail.com");
		$this->email->subject('Envio de Boletas mensual');
		$this->email->message('<h2>Buenos dias capo</h2>');
		$this->email->send();
		//con esto podemos ver el resultado
		var_dump($this->email->print_debugger());
	}


	public function enviar_boleta_mail()
	{
		$htmlContent = '<h1>HTML email with attachment testing by CodeIgniter Email Library</h1>';
		$htmlContent .= '<p>You can attach the files in this email.</p>';


		$configGmail = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'trazabilidad.rsu@gmail.com',
			'smtp_pass' => 'MAserver09042014',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'wordwrap' => TRUE,
			'newline' => "\r\n"
		);    

		//cargamos la configuración para enviar con gmail
		$this->email->initialize($configGmail);

		$this->email->from('trazabilidad.rsu@gmail.com');
		$this->email->to("checcarelli.s@gmail.com");
		$this->email->subject('Envio de Boletas mensual');
		$this->email->message('<h2>Buenos dias capo</h2>');
		$this->email->attach('C:\xampp\htdocs\codeigniter\boletas\boleta_septiembre.pdf');
		$this->email->send();
		//con esto podemos ver el resultado
		var_dump($this->email->print_debugger());
		

	}

	public function sendMailYahoo()
	{
		//cargamos la libreria email de ci
		$this->load->library("email");

		//configuracion para yahoo
		$configYahoo = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.mail.yahoo.com',
			'smtp_port' => 587,
			'smtp_crypto' => 'tls',
			'smtp_user' => 'emaildeyahoo',
			'smtp_pass' => 'password',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		); 

		//cargamos la configuración para enviar con yahoo
		$this->email->initialize($configYahoo);

		$this->email->from('correo que envia los emails');//correo de yahoo o no funcionará
		$this->email->to("correo que recibe el email");
		$this->email->subject('Bienvenido/a a uno-de-piera.com');
		$this->email->message('<h2>Email enviado con codeigniter haciendo uso del smtp de yahoo</h2><hr><br> Bienvenido al blog');
		$this->email->send();
		//con esto podemos ver el resultado
		var_dump($this->email->print_debugger());

	}
}