<?php 

/** 

Template Name: PagSeguro

**/
header('Content-Type: text/html; charset=utf-8');

require_once('classes/Cart.php');
require_once('classes/Item.php');
require_once('classes/Subscription.php');
require_once('classes/pagseguro-php-sdk/vendor/autoload.php');
require_once('classes/HttpRequest.php');
require_once('classes/errosps.php');

function input_post($param = ''){
	$return = trim(array_key_exists($param, $_POST)? $_POST[$param] : null);
	return $return;
}
function input_get($param = ''){
	$return = trim(array_key_exists($param, $_GET)? $_GET[$param] : null);
	return $return;
}
function base_url($url){
	return get_bloginfo('url').'/'.$url;
}

function loginJwt(){
	$params = new stdClass();
	$params->username = 'appmarket';
	$params->password = 'oTqhLmuN';
	$url = "http://54.232.181.173/producao/auth";
	$data = json_encode($params);
	$headers = array(
			"Content-Type: application/json",
			"Content-length: ".strlen($data)
	);
	
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $url);
	curl_setopt($tuCurl, CURLOPT_HEADER, 0);
	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	
	$tuData = curl_exec($tuCurl);
	
	if(!curl_errno($tuCurl)){
		$info = curl_getinfo($tuCurl);
		$return = json_decode($tuData, true);
		
		if( is_array($return) && array_key_exists('token', $return)){
			$_SESSION['TOKEN'] = $return['token'];
			unset($_SESSION['LOGIN_ERROR']);
		}else{
			$_SESSION['LOGIN_ERROR'] = $return;
			unset($_SESSION['TOKEN']);
		}
		
	} else {
		$return['message'] = curl_error($tuCurl);
		$_SESSION['LOGIN_ERROR'] = $return;
		unset($_SESSION['TOKEN']);
	}
	
	curl_close($tuCurl);
	return;
}

function provisionar2($params){
	$params->respCpf= preg_replace("/[^0-9]/", "", $params->respCpf);
	$params->cnpj = preg_replace("/[^0-9]/", "", $params->cnpj);
	$params->cep = preg_replace("/[^0-9]/", "", $params->cep);
	$params->tel = preg_replace("/[^0-9]/", "", $params->tel);
	
	$data = json_encode($params, JSON_PRETTY_PRINT);
	echo $data;
}

function provisionar($params){
	
	$params->respCpf= preg_replace("/[^0-9]/", "", $params->respCpf);
	$params->cnpj = preg_replace("/[^0-9]/", "", $params->cnpj);
	$params->cep = preg_replace("/[^0-9]/", "", $params->cep);
	$params->tel = preg_replace("/[^0-9]/", "", $params->tel);
	
	/*if( $params->formaPag == 'P'){
		$params->dataVencimento = 0;
	}*/
	
	$is_cliente = input_post('is_cliente');
	$endpoint = $is_cliente? 'upgrade':'provisionar';
	
	$url = 'http://54.232.181.173/producao/appMarket/'.$endpoint;
	$data = json_encode($params);
	$headers = array(
			"Content-Type: application/json",
			"Content-length: ".strlen($data),
			"X-Auth-Token: ".$_SESSION['TOKEN']
	);
	
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $url);
	curl_setopt($tuCurl, CURLOPT_HEADER, 0);
	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	
	$tuData = curl_exec($tuCurl);
	
	if(!curl_errno($tuCurl)){
		$info = curl_getinfo($tuCurl);
		$return = json_decode($tuData, true);
		
	} else {
		$return['message'] = curl_error($tuCurl);
	}
	
	file_put_contents(time()."-REAL-params.txt", $data);
	
	curl_close($tuCurl);
	return $return;
}




\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

//For example, to configure the library dynamically:
\PagSeguro\Configuration\Configure::setEnvironment('production');//production or sandbox
/*\PagSeguro\Configuration\Configure::setAccountCredentials(
		'financeiro@totalerp.com.br',
		'7FCD083DC25C4F7C9DCBF9A877E57DF1'
		);*/
\PagSeguro\Configuration\Configure::setAccountCredentials(
		'financeiro@totalerp.com.br',
		'DE32E7F9F94142539DCE11675B40B869'
		);
\PagSeguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
\PagSeguro\Configuration\Configure::setLog(false, '/logpath/logFilename.log');

$hr 			= new HttpRequest();
$cart 			= new Cart();
$subscription 	= new Subscription();
$action 		= get_query_var('action');

$credentials 	= PagSeguro\Configuration\Configure::getAccountCredentials();

//$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions';
$url = 'https://ws.pagseguro.uol.com.br/v2/sessions';
$params = array('email' => $credentials->getEmail(), 'token' => $credentials->getToken());
$sessionID =  $hr->post($url, $params, 'array');

//obtem o token de acesso ao ws do totalerp
loginJwt();

if($action == 'json'){
	$post_id = input_get('param');

	$userdata = get_post_meta( $post_id, 'user_data', true );
	$itens = get_post_meta($post_id, 'subscription_itens', true);
	$itens = json_decode($itens);
	
	$_POST = json_decode($userdata, true);
	$formaPag= 'p';
	$produtos = $itens;
	$params = new stdClass();
	$params->cnpj= input_post('cnpj');
	$params->razao = input_post('razao_social');
	$params->email = input_post('email');
	$params->municipio = input_post('cidade');
	$params->logradouro= input_post('endereco');
	$params->numero= input_post('numero');
	$params->complemento= input_post('complemento');
	$params->cep= input_post('cep');
	$params->bairro= input_post('bairro');
	$params->tel= input_post('ddd').input_post('telefone');
	$params->respNome= input_post('nome').' '.input_post('sobrenome');
	$params->respCpf = input_post('cpf_responsavel');
	$params->respEmail= input_post('email_responsavel');
	$params->produtos = $produtos;
	$params->usuarioCad= input_post('cod_representante');
	$params->formaPag = input_post('forma_pagamento');
	
	if( $params->formaPag == 'P'){
		$params->codPag= get_post_meta($post_id, 'subscription_code', true);
	}else{
		$params->dataVencimento= input_post('data_vencimento');
	}
	
	$is_cliente = input_post('is_cliente');
	if( $is_cliente ){
		echo 'upgrade';
	}else{
		echo 'provisiona';
	}
	echo json_encode($params);
	//provisionar($params);


	
}



if( $action == 'getuser'){
	$params = new stdClass();
	
	$cnpj= trim(input_post('cnpj'));
	$cnpj= preg_replace("/[^0-9]/", "", $cnpj);
	$params->cnpjCpf= $cnpj;
	
	$url = "http://54.232.181.173/producao/appMarket/obterCadastroEmpresa";
	$data = json_encode($params);
	$headers = array(
			"Content-Type: application/json",
			"Content-length: ".strlen($data),
			"X-Auth-Token: ".$_SESSION['TOKEN']
	);
	
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $url);
	curl_setopt($tuCurl, CURLOPT_HEADER, 0);
	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	
	$tuData = curl_exec($tuCurl);
	curl_close($tuCurl);
	$tuData = json_decode($tuData);
	
	echo json_encode($tuData);
	
}

if( $action == 'getuserk'){
	/*$formaPag= 'P';
	$produtos = array('PATCM01','PATCM02','PVARM02');
	$params = new stdClass();
	$params->cnpj= '09399490000115';
	$params->razao ='ENXOVAIS ANDITEX LTDA';
	$params->email = 'josimarster@gmail.com';
	$params->municipio = '4106902';
	$params->logradouro= 'Tiradentes';
	$params->uf ='PR';
	$params->numero= '65';
	$params->complemento= 'SN';
	$params->cep= '83706680';
	$params->bairro= 'centro';
	$params->tel= '041997289023';
	$params->respNome= 'Eugenio';
	$params->respCpf = '08163069902';
	$params->respEmail= 'josimarster@gmail.com';
	$params->produtos = $produtos;
	$params->usuarioCad= '05323395923';
	$params->formaPag = $formaPag;
	
	if($formaPag== 'P'){
		$params->codPag = md5('teste');
	}else{
		$params->dataVencimento= '05';
	}
	
	echo json_encode($params);*/
}

if( $action == 'validausuario'){
	
	$params = new stdClass();
	$senha = trim(input_post('senha'));
	$cpf= trim(input_post('cpf'));
	$cpf= preg_replace("/[^0-9]/", "", $cpf);
	$cnpj= trim(input_post('cnpj'));
	$cnpj= preg_replace("/[^0-9]/", "", $cnpj);
	
	$params->cnpj = $cnpj;
	$params->cpf= $cpf;
	$params->senha = md5($senha);
	
	$url = 'http://54.232.181.173/producao/appMarket/validarUsuarioTotalErp';
	$data = json_encode($params);
	$headers = array(
			"Content-Type: application/json",
			"Content-length: ".strlen($data),
			"X-Auth-Token: ".$_SESSION['TOKEN']
	);
	
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $url);
	curl_setopt($tuCurl, CURLOPT_HEADER, 0);
	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	
	$tuData = curl_exec($tuCurl);
	curl_close($tuCurl);
	
	echo $tuData;
}

if($action == 'validar'){
	
	$cnpj= trim(input_post('cnpj'));
	$cnpj= preg_replace("/[^0-9]/", "", $cnpj);
	
	$params = new stdClass();
	$params->cnpjCpf= $cnpj;

	$url = 'http://54.232.181.173/producao/appMarket/validarCnpjCpf';
	$data = json_encode($params);
	$headers = array(
			"Content-Type: application/json", 
			"Content-length: ".strlen($data),
			"X-Auth-Token: ".$_SESSION['TOKEN']
	);
	
	$tuCurl = curl_init();
	curl_setopt($tuCurl, CURLOPT_URL, $url);
	curl_setopt($tuCurl, CURLOPT_HEADER, 0);
	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	
	$tuData = curl_exec($tuCurl);
	
	if(!curl_errno($tuCurl)){
		$info = curl_getinfo($tuCurl);
		$return = json_decode($tuData, true);

		
	} else {
		$return['message'] = curl_error($tuCurl);
		$return['showform'] = true;
	}
	echo json_encode($return);
	
	curl_close($tuCurl);
	return;
}


if($action == 'checkout'){
	
	$telefone_full = input_post('te');
	$nome = input_post('nome').' '.input_post('sobrenome');
	$ddd = input_post('ddd');
	$telefone = input_post('telefone');
	$email = input_post('email');
	$cep = input_post('cep');
	$endereco = input_post('endereco');
	$numero = input_post('numero');
	$complemento = input_post('complemento');
	$bairro = input_post('bairro');
	$cidade = input_post('cidade');
	$cidade_nome = input_post('cidade_nome');
	$UF = input_post('uf');
	$estado = input_post('estado');
	$empresa = input_post('empresa');
	$cpf = input_post('cpf_responsavel');
		
	$cpf_cartao = input_post('cpf_cartao');
	$token_cartao = input_post('card_token');
	$nome_cartao = input_post('nome_cartao');
	$aniversario_cartao = input_post('nascimento_cartao');
	
	$cpf_cartao	= preg_replace('/[^0-9]/', '', $cpf_cartao);
	$cpf		= preg_replace('/[^0-9]/', '', $cpf);
	$cep		= preg_replace('/[^0-9]/', '', $cep);
	$telefone	= preg_replace('/[^0-9]/', '', $telefone);
	
	$formaPag = input_post('forma_pagamento');
	$periodicidade = 1;
	$fidelidade = 'N';
	
	
	if( $cart->getType() == 2){
		$periodicidade = 12;
		$fidelidade = 'S';
	}
	
	
	foreach ($cart->listar() as $k => $item){
		$subscription->description[] = $item->getProduct()->nome;
		
		if( $cart->getType() == 2){
			$subscription->itens[] = $item->getCode();
			$subscription->maxAmount += ($item->getPrice2() * $periodicidade);
		}else{
			$subscription->itens[] = $item->getCode();
			$subscription->maxAmount += ($item->getPrice() * $periodicidade);
		}
	}
	
	$subscription->amount = $subscription->maxAmount / $periodicidade;
	$subscription->dateStart = date('Y-m-d\T00:00:00');
	$subscription->dateEnd = date('Y-m-d\T00:00:00', strtotime("+ $periodicidade MONTH"));
	$subscription->fidelidade = $fidelidade;
	
	$post_id = wp_insert_post(array (
			'post_type' => 'assinaturas',
			'post_title' => mb_strtoupper($empresa)." : ".mb_strtoupper(implode(', ',$subscription->description)),
			'post_content' => mb_strtoupper($empresa)." : ".mb_strtoupper(implode(', ',$subscription->description)),
			'post_status' => 'publish',
			'comment_status' => 'closed',  
			'ping_status' => 'closed', 
	));
			
	if ($post_id) {
		update_post_meta($post_id, 'subscription_itens', json_encode($subscription->itens));
		update_post_meta($post_id, 'subscription_ref', $post_id);
		update_post_meta($post_id, 'subscription_fidelidade', $subscription->fidelidade);
		update_post_meta($post_id, 'subscription_status', 'PENDING');
		update_post_meta($post_id, 'subscription_data_inicio', date('d/m/Y'));
		update_post_meta($post_id, 'subscription_data_fim', date('d/m/Y', strtotime("+ $periodicidade MONTH")));
		update_post_meta($post_id, 'user_data', json_encode($_POST));
	}
	
	
	if( $formaPag == 'P'){
	
		$plan = new \PagSeguro\Domains\Requests\DirectPreApproval\Plan();
		$plan->setRedirectURL(base_url('pagseguro/redirect'));
		$plan->setReference($post_id);
		$plan->setPreApproval()->setName(mb_strtoupper($empresa)." : ".mb_strtoupper(implode(', ',$subscription->description)));
		$plan->setPreApproval()->setCharge('AUTO');
		$plan->setPreApproval()->setPeriod('MONTHLY');
		$plan->setPreApproval()->setAmountPerPayment(number_format($subscription->amount,2,'.',''));
		//$plan->setPreApproval()->setTrialPeriodDuration(0);
		$plan->setPreApproval()->setDetails("Todo mês será cobrado o valor de R$ ".number_format($subscription->amount,2,',','.')." referente a assinatura dos serviços ".mb_strtoupper(implode(', ',$subscription->description)));
		$plan->setPreApproval()->setFinalDate($subscription->dateEnd);
		$plan->setPreApproval()->setCancelURL("http://www.totalerp.com.br");
		$plan->setReviewURL('http://totalreap.com.br/review');
		$plan->setMaxUses(100);
		$plan->setReceiver()->withParameters($credentials->getEmail());
		
		$preApproval = new \PagSeguro\Domains\Requests\DirectPreApproval\Accession();
		$preApproval->setReference($post_id);
		$preApproval->setSender()->setName($nome);
		$preApproval->setSender()->setEmail($email);
		$preApproval->setSender()->setPhone()->withParameters($ddd, $telefone);
		$preApproval->setSender()->setIp($_SERVER['REMOTE_ADDR']);
		$preApproval->setSender()->setAddress()->withParameters($endereco, $numero, $bairro, $cep, $cidade_nome, $estado, 'BRA');
			
		$document = new \PagSeguro\Domains\DirectPreApproval\Document();
		$document->withParameters('CPF', $cpf); 
		$preApproval->setSender()->setDocuments($document);
			
		$preApproval->setPaymentMethod()->setCreditCard()->setToken($token_cartao); 
		$preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setName($nome_cartao); 
		$preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setBirthDate($aniversario_cartao); 
		
		$document = new \PagSeguro\Domains\DirectPreApproval\Document();
		$document->withParameters('CPF', $cpf_cartao); //cpf do titular do cartão de crédito
		$preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setDocuments($document);
		$preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setPhone()->withParameters($ddd, $telefone);
		$preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setBillingAddress()->withParameters($endereco, $numero,
				$bairro, $cep, $cidade_nome, $estado, 'BRA'); 
		
		try {
			$response = $plan->register( $credentials);
			$preApproval->setPlan($response->code);
			$response = $preApproval->register( $credentials);
			
			$code 			= $response->code;
			
			$ret = new stdClass();
			$ret->success = true;
			$ret->codepag = $code;
			$ret->msg = "<b>Transação realizada com sucesso</b><BR /><b>Código da transação</b><BR />".$code;
			$ret->errormsg = "";
			echo json_encode($ret);
			$cart->clear();
			
			$response 		= \PagSeguro\Services\PreApproval\Search\Code::search( $credentials, $code );
			$status 		= $response->getStatus();
			$post_id		= $response->getReference();
			$tracker 		= $response->getTracker();
			$time 			= current_time('mysql');
			
			update_post_meta($post_id, 'subscription_code', $code);
			update_post_meta($post_id, 'subscription_payment_status', $status);
			update_post_meta($post_id, 'subscription_tracker', $tracker);
			
			$data = array(
					'comment_post_ID' => $post_id,
					'comment_author' => 'admin',
					'comment_author_email' => 'admin@admin.com',
					'comment_author_url' => base_url(''),
					'comment_content' => 'CONTRATAÇÃO EFETUADA COM SUCESSO!',
					'comment_type' => '',
					'comment_parent' => 0,
					'user_id' => 1,
					'comment_author_IP' => '127.0.0.1',
					'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
					'comment_date' => $time,
					'comment_approved' => 0,
			);
			wp_insert_comment($data);
	
			
		} catch (Exception $e) {

			$arr = json_decode( $e->getMessage(), true);
			if( is_array( $arr )){
				$str= "<b>Não foi possível realizar a operação</b><BR />";
				foreach($arr['errors'] as $key => $value){
					if( array_key_exists($key, $errosPs)){
						$str .= $errosPs[$key] . '<Br />';
					}else {
						$str .= $value. '<Br />';
					}
				}

				$ret = new stdClass();
				$ret->success = false;
				$ret->codepag = '';
				$ret->msg = "";
				$ret->errormsg = $str;
				echo json_encode($ret);
				
			}
		}
		
	}else{
			
		$formaPag= 'B';
		$produtos = $subscription->itens;
		$params = new stdClass();
		$params->cnpj= input_post('cnpj');
		$params->razao = input_post('razao_social');
		$params->email = input_post('email');
		$params->municipio = input_post('cidade');
		$params->logradouro= input_post('endereco');
		$params->numero= input_post('numero');
		$params->complemento= input_post('complemento');
		$params->cep= input_post('cep');
		$params->bairro= input_post('bairro');
		$params->tel= input_post('ddd').input_post('telefone');
		$params->respNome= input_post('nome').' '.input_post('sobrenome');
		$params->respCpf = input_post('cpf_responsavel');
		$params->respEmail= input_post('email_responsavel');
		$params->produtos = $produtos;
		$params->usuarioCad= input_post('cod_representante');
		$params->formaPag = $formaPag;
		$params->dataVencimento= input_post('data_vencimento');
		$params->fidelidade = $subscription->fidelidade;
		
		provisionar($params);
		
		$ret = new stdClass();
		$ret->success = true;
		$ret->codepag = '';
		$ret->msg = "Sua transação foi recebida com sucesso. Entraremos em contato em breve.";
		$ret->errormsg = $str;
		echo json_encode($ret);
		
		$cart->clear();
		exit;
		
	}
	
	return;
}

if( $action == 'abc'){
	$json = '{"error":true,"errors":{"61009":"document value is required."}}';
	$arr = json_decode( $json , true);
	foreach($arr['errors'] as $key => $value){
		echo $value, '<Br />';
	}
	
}

if($action == 'notificacao'){
	file_put_contents("a.txt", print_r($_REQUEST, 1), FILE_APPEND);
	$code			= input_post('notificationCode');
	$type 			= input_post('notificationType');
	$credentials 	= PagSeguro\Configuration\Configure::getAccountCredentials();
	$time 			= current_time('mysql');
	
	if( $type == 'transaction'){
		try {
			$response 	=  PagSeguro\Services\Transactions\Search\Notification::search( $credentials, $code );
			$status	 	= $response->getStatus();
			$post_id	= $response->getReference();
			$date 		= $response->getDate();	
			update_post_meta($post_id, 'subscription_payment_status', $status);

			$arrStatus[1] = 'AGUARDANDO PAGAMENTO';
			$arrStatus[2] = 'EM ANÁLISE';
			$arrStatus[3] = 'PAGA';
			$arrStatus[4] = 'DISPONÍVEL';
			$arrStatus[5] = 'EM DISPUTA';
			$arrStatus[6] = 'DEVOLVIDA';
			$arrStatus[7] = 'CANCELADA';
			$arrStatus[8] = 'DEBITADO (VALOR DEVOLVIDO AO COMPRADOR)';
			$arrStatus[9] = 'RETENÇÃO TEMPORÁRIA';
			
			
			$data = array(
					'comment_post_ID' => $post_id,
					'comment_author' => 'admin',
					'comment_author_email' => 'admin@admin.com',
					'comment_author_url' => base_url(''),
					'comment_content' => 'HISTÓRICO DA TRANSAÇÃO.<br/><b>DATA:</b>'.date('d/m/Y H:i:s', strtotime($date)).'<br/><b>STATUS DO PAGAMENTO: </b>'.$arrStatus[$status],
					'comment_type' => '',
					'comment_parent' => 0,
					'user_id' => 1,
					'comment_author_IP' => '127.0.0.1',
					'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
					'comment_date' => $time,
					'comment_approved' => 0,
			);
			wp_insert_comment($data);
			
			if( $status == 3 ){
				
				$userdata = get_post_meta( $post_id, 'user_data', true );
				$itens = get_post_meta($post_id, 'subscription_itens', true);
				$fidelidade = get_post_meta($post_id, 'subscription_fidelidade', true);
				$itens = json_decode($itens);
				
				$_POST = json_decode($userdata, true);
				$formaPag= 'P';
				$produtos = $itens;
				$params = new stdClass();
				$params->cnpj= input_post('cnpj');
				$params->razao = input_post('razao_social');
				$params->email = input_post('email');
				$params->municipio = input_post('cidade');
				$params->logradouro= input_post('endereco');
				$params->numero= input_post('numero');
				$params->complemento= input_post('complemento');
				$params->cep= input_post('cep');
				$params->bairro= input_post('bairro');
				$params->tel= input_post('ddd').input_post('telefone');
				$params->respNome= input_post('nome').' '.input_post('sobrenome');
				$params->respCpf = input_post('cpf_responsavel');
				$params->respEmail= input_post('email_responsavel');
				$params->produtos = $produtos;
				$params->usuarioCad= input_post('cod_representante');
				$params->formaPag = $formaPag;
				//$params->dataVencimento= input_post('data_vencimento');
				$params->codPag= get_post_meta($post_id, 'subscription_code', true);
				$params->fidelidade = $fidelidade;
				
				provisionar($params);
				
				echo 'Operação realizada com sucesso';
				exit;
				
			}
			

		} catch (Exception $e) {
			die($e->getMessage());
		}
		
	}else if($type == 'preApproval'){
		try {
			
			$response 	=  PagSeguro\Services\PreApproval\Search\Notification::search($credentials, $code);
			$status	 	= $response->getStatus();
			$post_id	= $response->getReference();
			$date 		= $response->getDate();		
			
			$data = array(
					'comment_post_ID' => $post_id,
					'comment_author' => 'admin',
					'comment_author_email' => 'admin@admin.com',
					'comment_author_url' => base_url(''),
					'comment_content' => 'HISTÓRICO DA ASSINATURA. <br /> <b>DATA: </b>'.date('d/m/Y H:i:s', strtotime($date)).' <br/><b>STATUS DO ASSINATURA: </b>'.$status,
					'comment_type' => '',
					'comment_parent' => 0,
					'user_id' => 1,
					'comment_author_IP' => '127.0.0.1',
					'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
					'comment_date' => $time,
					'comment_approved' => 0,
			);
			wp_insert_comment($data);
			
			update_post_meta($post_id, 'subscription_status', $status);
			
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}

if($action == 'cancel'){
	$code 			= input_get('code');
	$credentials 	= PagSeguro\Configuration\Configure::getAccountCredentials();
	
	try {
		$response 		= \PagSeguro\Services\PreApproval\Cancel::create( $credentials, $code );
		
		if( $response->getResult() == 'ok'){
			$response 		= \PagSeguro\Services\PreApproval\Search\Code::search( $credentials, $code );
			
			$status 		= $response->getStatus();
			$post_id		= $response->getReference();
			$tracker 		= $response->getTracker();
			$time 			= current_time('mysql');
			
			update_post_meta($post_id, 'subscription_code', $code);
			update_post_meta($post_id, 'subscription_payment_status', $status);
			update_post_meta($post_id, 'subscription_tracker', $tracker);
			
			$data = array(
					'comment_post_ID' => $post_id,
					'comment_author' => 'admin',
					'comment_author_email' => 'admin@admin.com',
					'comment_author_url' => base_url(''),
					'comment_content' => 'HISTÓRICO DA ASSINATURA. <br /> <b>DATA: </b>'.date('d/m/Y H:i:s', strtotime($date)).' <br/><b>STATUS DO ASSINATURA: </b>'.$status,
					'comment_type' => '',
					'comment_parent' => 0,
					'user_id' => 1,
					'comment_author_IP' => '127.0.0.1',
					'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
					'comment_date' => $time,
					'comment_approved' => 0,
			);
			wp_insert_comment($data);
			wp_redirect( get_admin_url( null, 'edit.php?post_type=assinaturas', null ) );
		}
		
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

if($action == 'redirect'){
	$code 			= input_get('code');
	$credentials 	= PagSeguro\Configuration\Configure::getAccountCredentials();
	
	try {
		$response 		= \PagSeguro\Services\PreApproval\Search\Code::search( $credentials, $code );
		$status 		= $response->getStatus();
		$post_id		= $response->getReference();
		$tracker 		= $response->getTracker();
		$time 			= current_time('mysql');
		
		update_post_meta($post_id, 'subscription_code', $code);
		update_post_meta($post_id, 'subscription_payment_status', $status);
		update_post_meta($post_id, 'subscription_tracker', $tracker);

		$data = array(
				'comment_post_ID' => $post_id,
				'comment_author' => 'admin',
				'comment_author_email' => 'admin@admin.com',
				'comment_author_url' => base_url(''),
				'comment_content' => 'CONTRATAÇÃO EFETUADA COM SUCESSO!',
				'comment_type' => '',
				'comment_parent' => 0,
				'user_id' => 1,
				'comment_author_IP' => '127.0.0.1',
				'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
				'comment_date' => $time,
				'comment_approved' => 0,
		);
		wp_insert_comment($data);
		
		echo 'Obrigado por contratar nossos serviços!';
		
	} catch (Exception $e) {
		die($e->getMessage());
	}
		
}

return;