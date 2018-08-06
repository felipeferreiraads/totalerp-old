<?php 
/** 

Template Name: Carrinho

**/
set_time_limit(0);
$action = get_query_var('action');
require_once('classes/Cart.php');
require_once('classes/Item.php');
require_once('classes/pagseguro-php-sdk/vendor/autoload.php');
require_once('classes/HttpRequest.php');

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

$cart 			= new Cart();
$hr 			= new HttpRequest();

$credentials 	= PagSeguro\Configuration\Configure::getAccountCredentials();

//$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions';
$url = 'https://ws.pagseguro.uol.com.br/v2/sessions';
$params = array('email' => $credentials->getEmail(), 'token' => $credentials->getToken());
$sessionID =  $hr->post($url, $params, 'array');

if($action == 'add'){
	$t = $_POST['t'];
	
	if( $t == 1 ){
		$term_id 		= $_POST['produto'];
		$term 			= get_term( $term_id, 'pacotes');
		$term_id 		= $term->term_id; 
		$produto 		= new stdClass();
		$produto->nome 	=  $term->name;
		$valor 			= str_replace(',','.', str_replace('.', '',  get_field('valor_mensal', 'pacotes_'.$term_id) ) ); 
		$valor2 		= str_replace(',','.', str_replace('.', '',   get_field('valor_anual', 'pacotes_'.$term_id)  ) );
		$qtd			= 1;
		$type 			= $_POST['radio-stacked'] != ""? $_POST['radio-stacked'] : 1;
		$code 			= get_field('codigo', 'pacotes_'.$term_id);
		$code2 			= get_field('codigo', 'pacotes_'.$term_id);
		$item 			= new Item($term_id, $produto, $valor, $valor2, $qtd, $type, $code, $code2);
		$cart->setType($type);
		$cart->add($item);
		wp_redirect('/carrinho');
		
	}else if ($t == 2 ){
		$produto_id 		= $_POST['produto'];
		$post = wp_get_single_post( $produto_id );
		$produto 		= new stdClass();
		$produto->nome 	= $post->post_title;
		$valor 			= str_replace(',','.', str_replace('.', '',  get_field('valor_mensal', $produto_id) ) );
		$valor2 		= str_replace(',','.', str_replace('.', '',   get_field('valor_anual', $produto_id)  ) );
		$qtd			= 1;
		$type 			= $_POST['radio-stacked'] != ""? $_POST['radio-stacked'] : 1;
		$code 			= get_field('codigo', $produto_id);
		$code2 			= get_field('codigo', $produto_id);
		$item 			= new Item($term_id, $produto, $valor, $valor2, $qtd, $type, $code, $code2);
		$cart->setType($type);
		$cart->add($item);
		wp_redirect('/carrinho');
	}
	exit;

}


if($action == 'delete'){
	$param = $_GET['param'];
	$cart->delete($param);
	wp_redirect('/carrinho');
}

if($action == 'clear'){
	$cart->clear();
	wp_redirect('/carrinho');
}

if($action == 'update'){
	$key = $_POST['k'];
	$type = $_POST['t'];
	$item = $cart->get($key);
	$cart->setType($type);
	$cart->update($item, $key);

	$retorno = new stdClass();
	$retorno->total = number_format( $cart->getTotal(), 2,',','.');
	echo json_encode($retorno);
	exit;
}

if($action == 'ibge'){
	$ibge= $_POST['cod'];
	$ibge= is_numeric($ibge)? $ibge: 0;
	$munQuery = $wpdb->get_row("SELECT * FROM municipio WHERE ibge = '$ibge'");
	echo json_encode($munQuery);
	exit;
}

if($action == 'cidade'){
	$uf_id = $_POST['cod'];
	$uf_id = is_numeric($uf_id)? $uf_id : 0;
	$munQuery = $wpdb->get_results("SELECT * FROM municipio WHERE uf_id = '$uf_id' ORDER BY nome");
	$rs = array();
	foreach($munQuery as $q => $v){
		$rs[$v->ibge] = $v->nome;
	}
	echo json_encode($rs);
	exit;
}

global $wpdb;
$ufQuery = $wpdb->get_results("SELECT * FROM uf ORDER BY nome");
get_header();

?>


	<div class="page cart">
	<script>
	$(function(){
		var overlay = $('.overlay').overlay();
		 $.validator.addMethod("valueNotEquals", function(value, element, arg){
			 return $.trim(arg) !== value;
		}, "Value must not equal arg.");

		
		
		$('#dados-contato').hide();
		$('#proximo_passo').click(function(){ 
			PagSeguroDirectPayment.setSessionId('<?php echo $sessionID['id'] ?>');
            var senderHash = PagSeguroDirectPayment.getSenderHash();
            listarFormasDePagamento();
            $('#dados-contato').show(1000);
			setTimeout(function(){
			      $('html, body').animate({
			            scrollTop: $('#dados-contato').offset().top + 'px'
			        }, 1000, 'swing');
			}, 1100);
		

		});

		$('.forma_pagamento').click(function(){
			var formaPgto = $(this).val();
			if( formaPgto == 'B' ){
				$('#div_cartao').hide();
				$('#div_boleto').show();
			}else if( formaPgto == 'P'){
				$('#div_cartao').show();
				$('#div_boleto').hide();
			}else{
				alert('Escolha uma forma de Pagamento');
			}
			
		});
		
		$('.ptype').click(function(){
			var k = $(this).data('k');
			var t = $(this).data('t');
			var params = new Object();
			params.k = k;
			params.t = t;
			
			$.post('/carrinho/update', params,  function(data){

				if( t == 2 ){
					$('.t-anual').show();
					$('.t-mensal').hide();

					$('.c-anual').show();
					$('.c-mensal').hide();
				}else{
					$('.t-anual').hide();
					$('.t-mensal').show();
					
					$('.c-anual').hide();
					$('.c-mensal').show();
				}

				var obj = jQuery.parseJSON(data);
				$('.totals > strong').html( obj.total );

			});
			
		});

		$('#finalizar_pedido').click(function(){

			$('.md p').html( '' );
			
			if ($("#form-checkout").valid()) {

				$('.md h2').html('Por favor aguarde');
				$('.md p').html( 'Aguarde enquanto estamos processando seu pedido' );
				$('.md button').hide();
				overlay.trigger('show');
				
				var params = $('#form-checkout').serialize();
				$.post( $('#form-checkout').attr('action') , params, function( data ) {

					var obj = jQuery.parseJSON(data);
					if( obj.success ){
						finaliza_boleto( obj.msg );
						$('.md p').html( obj.msg );
					}else{
						$('.md p').html( obj.errormsg );
					}
					$('.md button').show();
					
					
				});
				
			}else{
				$('.md h2').html('Atenção');
				$('.md p').html( 'Há erros no formulário. Por favor verifique os campos para prosseguir.' );
				$('.md button').show();
				overlay.trigger('show');
			}
			
		});

		
		$('#proceder_pagamento').click(function(){

			$('.md p').html( '' );
			
			if ($("#form-checkout").valid()) {
				getHashCard();
			}else{
				$('.md h2').html('Atenção');
				$('.md p').html( 'Há erros no formulário. Por favor verifique os campos para prosseguir.' );
				$('.md button').show();
				overlay.trigger('show');
			}
			
		});


		

		$('#numero_cartao').change(function(){
			var myLength = $(this).val().length;
			if( myLength > 6 ){
				$('.md p').html('Por favor aguarde enquanto verificamos os dados.');
				$('.md button').hide();
				overlay.trigger('show');
				getBrand( $(this).val() );
			} 
		});
		
		$.validator.setDefaults( {
			submitHandler: function () {
				//alert( "submitted!" );
			}
		} );



		$('#form-login').validate({
			rules: {
				cnpj: {
					required: true,
					minlength: 14,
					cnpj: true
				},
				cpf: {
					required: true,
					minlength: 11,
					cpf: true
				},
				senha: {
					required: true,
					minlength: 3
				}
			},
			messages: {
				cnpj: {
					required: "Campo obrigatório",
					minlength: "Mínimo 3 caracteres",
					cnpj: "Cnpj Inválido"
				},
				cpf: {
					required: "Campo obrigatório",
					minlength: "Mínimo 11 caracteres",
					cpf: "Cpf Inválido"
				},
				senha: {
					required: "Campo obrigatório",
					minlength: "Mínimo 3 caracteres"
				}
				
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
			},
		    unhighlight: function(element, errorClass, validClass) {
		        $(element).parents( ".form-group" ).removeClass( "has-error" ).addClass( "has-success" );
		     }
		});

		$('#form-login').submit(function(e){
			e.preventDefault();
			if ($(this).valid()) {
				$('.md h2').html( 'Por favor aguarde' );
				$('.md p').html( 'Efetuando Login' );
				overlay.trigger('show').delay( 1500 );

				$.post($(this).attr('action'), $(this).serialize() ,  function(data){
					var obj = JSON.parse(data);

					if( obj.autenticado ){
						$('#login_msgerr').hide();
						getUser($('#cnpj').val());

						var nome = obj.usuario.nome
						var arnome = nome.split(" ");
						
						$('#nome').val( arnome.shift()  ).attr('readonly', true);
						$('#sobrenome').val( arnome.join(" ") ).attr('readonly', true); 
						$('#cpf_responsavel').val( obj.usuario.cpf ).attr('readonly', true);
						$('#email_responsavel').val( obj.usuario.email ).attr('readonly', true);

						
					}else{
						$('#login_msgerr').show();
						$('#div_dados').hide();
						$('#is_cliente').val( 0 );
						overlay.trigger('hide').delay( 1500 );
					}

					

				});
				
			}else{
				$('#div_dados').hide();
				$('#login_msgerr').hide();
			}
		});


		
		$('#form-validar').validate({
			rules: {
				cnpj: {
					required: true,
					minlength: 14,
					cnpj: true
				}
			},
			messages: {
				cnpj: {
					required: "Campo obrigatório",
					minlength: "Mínimo 14 caracteres",
					cnpj: "Cnpj Inválido"
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
			},
		    unhighlight: function(element, errorClass, validClass) {
		        $(element).parents( ".form-group" ).removeClass( "has-error" ).addClass( "has-success" );
		     }
		});

		$('#form-validar').submit(function(e){
			e.preventDefault();
			if ($(this).valid()) {
				$('.md h2').html( 'Por favor aguarde' );
				$('.md p').html( 'Aguarde, estamos verificando o CNPJ' );
				$('.md button').hide();
				overlay.trigger('show');
				$.post($(this).attr('action'), $(this).serialize() ,  function(data){
					var obj = JSON.parse(data);

					
					if( !obj.possuiContrato ) {

						
							$('#fdl_msgerr').html('');
							$('#fdl_msgerr').hide();
							$('#valida_msgerr').hide();
							$('#dados_cnpj').val($('#cnpj_check').val());
							$('#forma_boleto').attr('checked',true);
							$('#div_boleto').show();
							$('#forma_cartao').attr('checked',false);
													
							$('#div_dados').show(1000);
							setTimeout(function(){
							      $('html, body').animate({
								    scrollTop: $('#div_dados').offset().top + 'px'
								}, 1000, 'swing');
							}, 1100);
						

						
					}else{
						$('#valida_msgerr').show();
						$('#div_dados').hide();
					}

					overlay.trigger('hide');
					setTimeout(function(){
						$('.md p').html('');
						$('.md h2').html( 'Total ERP' );
					},500);
					
				});
				
			}else{
				console.log( 'error ' );
			}
		});
		



		
		$("#form-checkout").validate({
			rules: {
				cnpj: {
					required: true,
					cnpj: true,
					minlength: 14
				},
				razao_social: {
					required: true,
					minlength: 3
				},
				nome: {
					required: true,
					minlength: 3
				},
				sobrenome: {
					required: true,
					minlength: 3
				},
				cpf_responsavel: {
					required: true,
					cpf: true
				},
				cep: {
					required: true
				},
				numero: {
					required: true
				},
				cidade: {
					required: true,
					valueNotEquals : '-1'
				},
				bairro: {
					required: true
				},
				endereco: {
					required: true
				},
				numero_cartao : {
					required: true
				},
				validadeMes : {
					required: true,
					minlength: 2,
					maxlength: 2,
					range: [1, 12],
					digits: true
				},
				validadeAno : {
					required: true,
					minlength: 4,
					maxlength: 4,
					digits: true
				},
				nome_cartao : {
					required: true,
				},
				cpf_cartao : {
					required: true,
					cpf: true
				},
				nascimento_cartao : {
					required: true,
					databr : true
				},
				cvv : {
					required: true
				},
				uf: {
					required: true,
					valueNotEquals : '-1'
				},
				email: {
					required: true,
					email: true
				},
				email_responsavel: {
					required: true,
					email: true
				},
				ddd: {
					required: true,
					maxlength: 2,
					minlength: 2
				},
				telefone: {
					required: true
				},
			},
			messages: {
				cnpj: {
					required: "Campo obrigatório",
					minlength: "Mínimo 14 caracteres",
					cnpj:"Cnpj inválido"
				},
				razao_social: {
					required: "Campo obrigatório",
					minlength: "Mínimo 3 caracteres"
				},
				nome: {
					required: "Campo obrigatório",
					minlength: "Mínimo 3 caracteres"
				},
				sobrenome: {
					required: "Campo obrigatório",
					minlength: "Mínimo 3 caracteres"
				},
				cpf_responsavel: { 
					required: 'Campo obrigatório',
					cpf: 'Cpf inválido'
				},
				cep: { 
					required: 'Campo obrigatório'
				},
				numero: {
					required: 'Campo obrigatório',
					minlength: "Mínimo 1 caractere"
				},
				cidade: {
					required: 'Campo obrigatório',
					valueNotEquals: 'Selecione a Cidade'
				},
				bairro: {
					required: 'Campo obrigatório',
					minlength: "Mínimo 3 caracteres"
				},
				endereco : {
					required: 'Campo obrigatório',
					minlength: "Mínimo 3 caracteres"
				},
				numero_cartao : {
					required: 'Campo obrigatório'
				},
				validadeMes : {
					required: 'Campo obrigatório',
					maxlength: "Máximo 2 caracteres",
					minlength: "Mínimo 2 caracteres",
					range: 'Informe um mês entre 01 e 12',
					digits: 'Informe apenas números'
				},
				validadeAno : {
					required: 'Campo obrigatório',
					maxlength: "Máximo 4 caracteres",
					minlength: "Mínimo 4 caracteres",
					digits: 'Informe apenas números'
				},
				nome_cartao : {
					required: 'Campo obrigatório',
					minlength: "Mínimo 3 caracteres"
				},
				cpf_cartao : {
					required: 'Campo obrigatório',
					cpf: 'Cpf Inválido'
				},
				nascimento_cartao : {
					required: 'Campo obrigatório',
					databr : "Data inválida"
				},				
				cvv : {
					required: 'Campo obrigatório'
				},
				uf: {
					required: 'Campo obrigatório',
					valueNotEquals : 'Selecione o Estado'
				},
				email: {
					email: "E-mail inválido",
					required: 'Campo obrigatório',
					minlength: "Mínimo 3 caracteres"
				},
				email_responsavel: {
					email: "E-mail inválido",
					required: 'Campo obrigatório',
					minlength: "Mínimo 3 caracteres"
				},
				ddd: {
					required: 'Campo obrigatório',
					maxlength: "Máximo 3 caracteres",
					minlength: "Mínimo 3 caracteres"
				},
				telefone: { 
					required: 'Campo obrigatório'
				},
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
			},
		    unhighlight: function(element, errorClass, validClass) {
		        $(element).parents( ".form-group" ).removeClass( "has-error" ).addClass( "has-success" );
		     }
		});
		

		$('#nome_cartao').keyup(function(){
		    this.value = this.value.toUpperCase();
		});

		$("#cnpj").mask("99.999.999/9999-99");
		$("#cnpj_check").mask("99.999.999/9999-99");
		$("#dados_cnpj").mask("99.999.999/9999-99");

		$("#nascimento_cartao").mask("99/99/9999",{placeholder:"dd/mm/aaaa"});
		$("#cpf_cartao").mask("999.999.999-99");
		$("#cpf").mask("999.999.999-99");
		$("#cpf_responsavel").mask("999.999.999-99");
		$("#cep").mask("99.999-999");
		//$("#cvv").mask("999");
		$("#ddd").mask("99");
		$("#dddc").mask("99");
		$("#telefone").mask("9999-9999?9");
		$("#celular").mask("9999-9999?9");
		//$("#validadeMes").mask("99");
		//$("#validadeAno").mask("9999");
		$("#numero_cartao").mask("9999999999999999");
		//$("#validadeAno").mask("9999");

		

		if('<?php echo $cart->getType() ?>' == 2){
			$('.t-anual').show();
			$('.t-mensal').hide();

			$('.c-anual').show();
			$('.c-mensal').hide();
		}else{
			$('.t-mensal').show();
			$('.t-anual').hide();

			$('.c-mensal').show();
			$('.c-anual').hide();
		}

		$(document).on('change','#uf',function(e){
			var cod = $(this).val();
			var params = new Object();
			params.cod = cod;
			$('.md h2').html( 'Por favor aguarde' );
			$('.md p').html('Aguarde, estamos carregando a lista de cidades.');
			$('.md button').hide();
			overlay.trigger('show');
			$.post('/carrinho/cidade', params,  function(data){
				var obj = jQuery.parseJSON(data);
				$('#cidade').empty();
				
			    $('#cidade').append($('<option/>', { 
			        value: '-1',
			        text : 'Selecione a Cidade' 
			    }));
			    
				$.each(obj, function (index, value) {
				    $('#cidade').append($('<option/>', { 
				        value: index,
				        text : value 
				    }));
				});
				overlay.trigger('hide');
				setTimeout(function(){
					$('.md p').html('');
					$('.md h2').html( 'Total ERP' );
					$('.md button').show();					
				},500);
				
			});
				
		});
		
	});

	

	function getUser(cnpj){
		var params = new Object();
		params.cnpj = cnpj;

		$('.md p').html( 'Obtendo dados da Empresa' );
		$('.md button').hide();
		
		$.post('/pagseguro/getuser', params,  function(data){
			var obj = jQuery.parseJSON(data);

			if( obj.hasOwnProperty('error') ){
				$('.md p').html( obj.message );
				$('#is_cliente').val( 0 );
			}else{
				
				$('#div_dados').show();
				$('#is_cliente').val( 1 );
				$('#dados_cnpj').val( obj.cnpj ).attr('readonly', true);;
				//$('#cpf').val( obj.cpf ).attr('readonly', true);
				$('#razao_social').val( obj.razao ).attr('readonly', true);;
				$('#email').val( obj.email ).attr('readonly', true);;
				$('#ddd').val( obj.tel.substring(0, 2) ).attr('readonly', true);;
				$('#telefone').val( obj.tel.substring(2) ).attr('readonly', true);;
	
				$('#endereco').val( obj.logradouro ).attr('readonly', true);;
				$('#numero').val( obj.numero ).attr('readonly', true);;
				$('#complemento').val( obj.complemento ).attr('readonly', true);;
				$('#bairro').val( obj.bairro ).attr('readonly', true);;

				//$('#uf').val( obj.uf );
				//$('select').val( obj.municipio );
				$('#cep').val( obj.cep ).attr('readonly', true);;

				var params = new Object();
				params.cod = obj.municipio;
				$.post('/carrinho/ibge', params,  function(data){
					var objIbge = jQuery.parseJSON(data);
					$('#uf').val( objIbge.uf_id );
					
					var params2 = new Object();
					params2.cod = objIbge.uf_id;
					$.post('/carrinho/cidade', params2,  function(data){
						var objCity = jQuery.parseJSON(data);

						$('#cidade').empty();
					    $('#cidade').append($('<option/>', { 
					        value: '-1',
					        text : 'Selecione a Cidade' 
					    }));
					    
						$.each(objCity, function (index, value) {
							option = new Object();
							option.value = index;
							option.text = value;
							if( index == obj.municipio ){
								option.selected = 'selected';
							}
						    $('#cidade').append($('<option/>', option));
						});

						
					});
					
				});
					
				//$('#nome').val( obj.respNome ).attr('readonly', true);
					//$('#cep').val( obj.cep ).attr('readonly', true); SOBRENOME
				//$('#cpf').val( obj.respCpf ).attr('readonly', true);
				//$('#email_responsavel').val( obj.respEmail ).attr('readonly', true);
	
				//$('#cod_representante').val( obj.usuarioCad ).attr('readonly', true);
	
				$('.md p').html( 'Obtendo dados de Pagamento' );
				
				$.post('/pagseguro/validar', {'cnpj':cnpj} ,  function(data){
					var obj = JSON.parse(data);
					if( obj.hasOwnProperty('error') ){
						$('.md p').html( obj.message );
					}else{				

						if( obj.possuiContrato ){
							
							if( obj.pessoaContrato.fidelidade && $("input[name=radio-stacked-1]:checked").val() == 1 ){

								$('#fdl_msgerr').html('O seu plano atual é com fidelidade, realizar a compra alterando o produto para o Plano com Fidelidade.');
								$('#fdl_msgerr').show();
								$('#div_dados').hide();
								$('#div_boleto').hide();
								
							}else if( !obj.pessoaContrato.fidelidade && $("input[name=radio-stacked-1]:checked").val() == 2 ){
								$('#fdl_msgerr').html('O seu plano atual não tem fidelidade, realizar a compra alterando o produto para o Plano Mensal ou entre em contato com o nosso setor Comercial.');
								$('#fdl_msgerr').show();
								$('#div_dados').hide();
								$('#div_boleto').hide();
								
							}else {
								
								if( obj.pessoaContrato.formaPagamento == 'BOLETO' ){
									$('#forma_boleto').attr('checked',true);
									$('#forma_cartao').parent().parent().hide();
									$('#div_cartao').hide();
									$('#div_boleto').show();
									$('.dados-resp').hide();
								}else if(obj.pessoaContrato.formaPagamento == 'PAGSEGURO' ) {
									$('#forma_cartao').attr('checked',true);
									$('#forma_boleto').parent().parent().hide();
									$('#div_cartao').show();
									$('#div_boleto').hide();
									$('.dados-resp').show();
								}else{
									$('#div_cartao').show();
									$('#div_boleto').show();
								}
							}


							
						}else{
							$('#div_cartao').show();
							$('#div_boleto').show();
						}
						
						$('.md p').html( 'Login efetuado com sucesso!' );

						setTimeout(function(){
						      $('html, body').animate({
							    scrollTop: $('#div_dados').offset().top + 'px'
							}, 1000, 'swing');
						}, 100);
						
						overlay.trigger('hide');
						setTimeout(function(){
							$('.md p').html('');
							$('.md h2').html( 'Total ERP' );
							$('.md button').show();
						},500);
					}
	
				});
			}
			
		});
	}
	
	var formasPagamento = [];
	var bandeiraCartao = '';

	function getBrand(bin){
		PagSeguroDirectPayment.getBrand({
			cardBin: bin,
			success: function(response) { 
				var ccName = response.brand.name;
				ccName = ccName.toUpperCase();
				bandeiraCartao = ccName;
				$('input#bandeira').val( response.brand.name );
				$('#cardLogo').html('<img src="https://stc.pagseguro.uol.com.br'+formasPagamento.CREDIT_CARD[ccName].images.MEDIUM.path+'" />');
			},
			error: function(response) {
				$('.md p').html('Erro no cartão').delay(5000);;
				overlay.trigger('show');
			 },
			complete: function(response) { 
				overlay.trigger('hide');
				setTimeout(function(){
					$('.md p').html('');
					$('.md button').show();
				},1000);
			}
		});		
	}

	function processaFormasDePagamento(retorno){
		$.each(retorno.paymentMethods, function( index, value ) {
			if( index == 'CREDIT_CARD' ){
				formasPagamento[index] = [];
			}
			$.each( value.options, function( k,v){
				if( index == 'CREDIT_CARD' ){
					formasPagamento[index][k]= v;
				}
			});

		});
	}

	function listarFormasDePagamento(){
		PagSeguroDirectPayment.setSessionId('<?php echo $sessionID['id'] ?>');
		PagSeguroDirectPayment.getPaymentMethods({
			amount: <?php echo number_format($cart->getTotal(), 2,'.','')?>, 
			success: function(response) {
				console.log('success');
				processaFormasDePagamento(response);
			},
			error: function(response) {
				console.log('error');
			},
			complete: function(response) {
				console.log('complete');
			}
		});
	}

	function getHashCard(){
		$('.md p').html( 'Aguarde enquanto estamos processando seu pagamento' );
		$('.md button').hide();
		overlay.trigger('show');
		
		var param = {
			cardNumber: $("input#numero_cartao").val(),
			cvv: $("input#cvv").val(),
			expirationMonth: $("input#validadeMes").val(),
			expirationYear: $("input#validadeAno").val(),
			success: function(response) {
				$('#card_token').val(response.card.token); 

					var params = $('#form-checkout').serialize();
					var cidade = $("#cidade option:selected").text();;
					var estado = $("#uf option:selected").text();;
					params = params + '&cidade_nome='+cidade+'&estado='+estado;
					
					$.post( $('#form-checkout').attr('action') , params, function( data ) {

						var obj = jQuery.parseJSON(data);
						if( obj.success ){
							finaliza_cartao( obj.msg );
							$('.md p').html( obj.msg );
						}else{
							$('.md p').html( obj.errormsg );
						}
						$('.md button').show();
						overlay.trigger('show');

						/*
						finaliza_cartao(data);
						msg = "<b>Transação realizada com sucesso</b><BR /><b>Código da transação</b><BR />"+data;
						$('.md p').html( msg );
						$('.md button').show();
						overlay.trigger('show');
						*/
					});
						
					
			},
			error: function(response) {
				var mensagem = '';
				$.each( response.errors, function (i, v){
					mensagem += v;
				});
				$('.md p').html( mensagem );
				$('.md button').show();
				overlay.trigger('show');
				$('#card_token').val(''); 
			},
			complete: function(response) { }
		};

		if($("input#bandeira").val() != '') {
			param.brand = $("input#bandeira").val();
		}
				
		PagSeguroDirectPayment.createCardToken(param);
	}

	jQuery.validator.addMethod("databr", function(value, element){
				var date=value;
				var ardt=new Array;
				var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
				ardt=date.split("/");
				erro=false;
				if ( date.search(ExpReg)==-1){
					erro = true;
					}
				else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
					erro = true;
				else if ( ardt[1]==2) {
					if ((ardt[0]>28)&&((ardt[2]%4)!=0))
						erro = true;
					if ((ardt[0]>29)&&((ardt[2]%4)==0))
						erro = true;
				}

				var retorno = true;
				
				if (erro) {
					//alert(""" + valor + "" não é uma data válida!!!");
					//campo.focus();
					//campo.value = "";
					retorno = false;
				}
				
				return this.optional(element) || retorno;
		

	},'Data inválida');

	jQuery.validator.addMethod("cpf", function(value, element) {
		   value = jQuery.trim(value);
			
			value = value.replace('.','');
			value = value.replace('.','');
			cpf = value.replace('-','');
			while(cpf.length < 11) cpf = "0"+ cpf;
			var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
			var a = [];
			var b = new Number;
			var c = 11;
			for (i=0; i<11; i++){
				a[i] = cpf.charAt(i);
				if (i < 9) b += (a[i] * --c);
			}
			if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
			b = 0;
			c = 11;
			for (y=0; y<10; y++) b += (a[y] * c--);
			if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }
			
			var retorno = true;
			if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;
			
			return this.optional(element) || retorno;

		}, "Informe um CPF válido."); // Mensagem padrão 

		 
		jQuery.validator.addMethod("cnpj", function(cnpj, element) {
		   cnpj = jQuery.trim(cnpj);
			
			// DEIXA APENAS OS NÚMEROS
		   cnpj = cnpj.replace('/','');
		   cnpj = cnpj.replace('.','');
		   cnpj = cnpj.replace('.','');
		   cnpj = cnpj.replace('-','');

		   var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
		   digitos_iguais = 1;

		   if (cnpj.length < 14 && cnpj.length < 15){
		      return this.optional(element) || false;
		   }
		   for (i = 0; i < cnpj.length - 1; i++){
		      if (cnpj.charAt(i) != cnpj.charAt(i + 1)){
		         digitos_iguais = 0;
		         break;
		      }
		   }

		   if (!digitos_iguais){
		      tamanho = cnpj.length - 2
		      numeros = cnpj.substring(0,tamanho);
		      digitos = cnpj.substring(tamanho);
		      soma = 0;
		      pos = tamanho - 7;

		      for (i = tamanho; i >= 1; i--){
		         soma += numeros.charAt(tamanho - i) * pos--;
		         if (pos < 2){
		            pos = 9;
		         }
		      }
		      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		      if (resultado != digitos.charAt(0)){
		         return this.optional(element) || false;
		      }
		      tamanho = tamanho + 1;
		      numeros = cnpj.substring(0,tamanho);
		      soma = 0;
		      pos = tamanho - 7;
		      for (i = tamanho; i >= 1; i--){
		         soma += numeros.charAt(tamanho - i) * pos--;
		         if (pos < 2){
		            pos = 9;
		         }
		      }
		      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		      if (resultado != digitos.charAt(1)){
		         return this.optional(element) || false;
		      }
		      return this.optional(element) || true;
		   }else{
		      return this.optional(element) || false;
		   }
		}, "Informe um CNPJ válido."); // Mensagem padrão


	function finaliza_boleto(data){
		$('.container > .post-title').html('Sua transação foi recebida');
		$('.container > .text p').html(data);
		$('.container > .text a').hide();
		$('.container > .list').hide();
		$('.container > #dados-contato').hide();
		$('.container > #div_dados').hide();
		$('.container > #div_cartao').hide();
		$('.container > #div_boleto').hide();

		return false;
	}
	

	function finaliza_cartao(data){
		$('.container > .post-title').html('Sua transação foi aprovada');
		$('.container > .text p').html( data );
		$('.container > .text a').hide();
		$('.container > .list').hide();
		$('.container > #dados-contato').hide();
		$('.container > #div_dados').hide();
		$('.container > #div_cartao').hide();
		$('.container > #div_boleto').hide();

		return false;
	}


	
				</script>
				<section class="banners min clouds"></section>
				<div class="container">
					<h1 class="post-title">Você está contratando</h1>
					<article class="text">             
            
			            <p>Abaixo estão listados os produtos e/ou pacotes que você deseja contratar. Clique no botão “Próximo Passo” para seguir com sua compra.</p>
                        <p> <b>Importante: Se a sua empresa já usa o Totalerp, o novo módulo será cobrado em sua próxima fatura. </b></p>
			            <a href="http://totalerp.com.br/app-market/" class="btn small">Adicionar módulos complementares</a>
<img src="http://totalerp.com.br/sitew2/wp-content/uploads/2018/03/pagseguro_logo.png" class="pull-right" style="margin-top:9px;">
			        </article>
			        <section class="list" >
			        			<div class="custom-control custom-radio">
                                    <label class="custom-control custom-radio">
	                                    <input id="radioStacked1" name="radio-stacked-1" value="1" data-k=1  data-t=1 type="radio" <?php echo $cart->getType() != 2? 'checked=checked' : ''; ?> class="custom-control-input ptype">
	                                    <span class="custom-control-indicator"></span>
	                                    <span class="custom-control-description"> Plano Mensal</span>
	                                </label>
                                    <label class="custom-control custom-radio">
	                                    <input id="radioStacked1" name="radio-stacked-1" value="2" data-k=1 data-t=2 type="radio" <?php echo $cart->getType() == 2? 'checked=checked' : ''; ?> class="custom-control-input ptype">
	                                    <span class="custom-control-indicator"></span>
	                                    <span class="custom-control-description"> Plano Fidelidade</span>
	                                </label>
                            	</div> 
			        	<ul>
			        		<?php if( count( $cart->listar() ) > 0 ): ?>
			        		<?php foreach ($cart->listar() as $k => $item): if($item): ?>
			        		<li class="product" data-k=<?= $k ?>>			        			
			        			<span class="name"><a href="/carrinho/delete/?param=<?= $k?>"><i class="fa fa-close"></i></a>
			        			<span class="c-mensal"><?php echo $item->getCode()?></span>
			        			<span class="c-anual"><?php echo $item->getCode2()?></span>
			        			<?php echo ' - ', $item->getProduct()->nome ?>
			        			</span>
			        			<div class="custom-control custom-radio">
                                    <label class="custom-control custom-radio t-mensal">
	                                    <!-- <input id="radioStacked1" name="radio-stacked-<?= $k?>" data-k=<?= $k ?> data-t=1 type="radio" <?php echo $item->getType() != 2? 'checked=checked' : ''; ?> class="custom-control-input ptype">  
	                                    <span class="custom-control-indicator"></span> -->
	                                    <span class="custom-control-description">R$ <strong><?php echo number_format($item->getPrice(), 2,',','.')?></strong> - Plano Mensal</span>
	                                </label>
                                    <label class="custom-control custom-radio t-anual">
	                                    <!--  <input id="radioStacked1" name="radio-stacked-<?= $k?>" data-k=<?= $k ?> data-t=2 type="radio" <?php echo $item->getType() == 2? 'checked=checked' : ''; ?> class="custom-control-input ptype">  
	                                    <span class="custom-control-indicator"></span> -->
	                                    <span class="custom-control-description">R$ <strong><?php echo number_format($item->getPrice2(), 2,',','.')?></strong> - Plano Fidelidade</span>
	                                </label>
                            	</div> 
			        		</li>
			        		<?php endif; endforeach;?>
			        	<?php else: ?>
			        		<p> Seu carrinho está vazio </p>
			        	<?php endif; ?>
			        		
			        		
			        	</ul>
			        	<section class="totals">
			        		Total: R$ <strong> <?php echo number_format($cart->getTotal(), 2,',','.')?></strong>
			        	</section>			        	
			        	<br />
			        	<?php if( count( $cart->listar() ) > 0): ?>
			        	<a href="#dados-contato" id="proximo_passo" class="btn small next-step reverse">Próximo Passo <i class="fa fa-angle-right"></i></a>
			        	<?php endif; ?>
			        	<br />
			        	<br />
			        </section>

			        <section id="dados-contato" class="form contato">
				        
					        <div class="row">

					        	<div class="col-md-6">
					        		<form action="/pagseguro/validar" method="post" id="form-validar">
						        		<h2 class="page-title">Sou um Novo Cliente</h2>
						        		<small>Informe seu CNPJ para prosseguirmos com a finalização do seu pedido</small>
						        		<div class="form-group ">
										    <input type="text" name="cnpj" id="cnpj_check" required minlength=3 class="form-control" placeholder="CNPJ">
										</div>
										<div>
											<small style="color: red; font-weight: bold; display:none" id="valida_msgerr">O CNPJ informado já encontra-se cadastrado na nossa base de dados, por favor, utilize o formulário de login ao lado.</small> 
										</div>
										<div class="form-group pull-left">
						        			<a href="javascript:void(0)" onClick='$("#form-validar").submit();' id="btn_validar" class="btn btn-info" style="width: 200px">Cadastrar <i class="fa fa-angle-right"></i></a>
						        		</div>
					        		</form>
					        	</div>
					        	
					        	<div class="col-md-6">
						        	<form action="/pagseguro/validausuario" method="post" id="form-login">
						        		<h2 class="page-title">Já Sou Cliente</h2>
						        		<small>Se você já é cliente Totalerp, informe seus dados abaixo para validarmos seu cadastro.</small>
						        		<div class="form-group ">
										    <input type="text" name="cnpj" id="cnpj" required minlength=3 class="form-control" placeholder="CNPJ">
										</div>
						        		<div class="form-group ">
										    <input type="text" name="cpf" id="cpf" required minlength=3 class="form-control" placeholder="CPF">
										</div>
						        		<div class="form-group ">
										    <input type="password" name="senha" id="senha" required minlength=3 class="form-control" placeholder="SENHA">
										</div>
										<div>
											<small style="color: red; font-weight: bold; display:none" id="login_msgerr">O CPNJ informado não existe no nosso sistema ou está inativo. Entre em contato com nosso setor comercial através do telefone (41) 5555.5555</small> 
											<small style="color: red; font-weight: bold; display:none" id="fdl_msgerr"></small>
										</div>
										<div class="form-group pull-left">
						        			<a href="javascript:void(0)"  onClick='$("#form-login").submit();' id="btn_login" class="btn btn-info" style="width: 200px">Prosseguir <i class="fa fa-angle-right"></i></a>
						        		</div>
						        	</form>
					        	</div>
					        	
					        </div>
					        	
				        
				        <br />
				        <form action="/pagseguro/checkout" method="post" id="form-checkout">
				        <input type="hidden" name="is_cliente" id="is_cliente" value="0">
				        <div class="row" id="div_dados" style="display:none" >
				        
			        		<div class="col-md-12">
				        		<h2 class="page-title">Preencha Seus Dados</h2>
				        		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quam orci, bibendum congue elementum vel, blandit id tortor. </p>
				        		<h3>Dados da Empresa</h3>
			        		</div>
			        		
			        		<div class="form-group col-md-6 col-xs-12">
							    <input type="text" name="cnpj" id="dados_cnpj" required minlength=3 class="form-control" placeholder="CNPJ">
							</div>
							
			        		<div class="form-group col-md-6 col-xs-12">
							    <input type="text" name="razao_social" id="razao_social" required minlength=3 class="form-control" placeholder="Razão Social">
							</div>
							
			        		<div class="form-group col-md-6 col-xs-12">
							    <input type="text" name="email" id="email" required minlength=3 class="form-control" placeholder="E-mail">
							</div>
							
							<div class="row col-md-6 col-xs-12">
				        		<div class="form-group col-md-4 col-xs-4">
								    <input type="text" name="ddd" id="ddd" required minlength=2 class="form-control" placeholder="DDD">
								</div>
				        		<div class="form-group col-md-8 col-xs-8">
								    <input type="text" name="telefone" id="telefone" required minlength=8 class="form-control" placeholder="Telefone">
								</div>
							</div>
									
			        		<div class="col-md-12">
				        		<h3>Endereço</h3>
			        		</div>
			        		
			        		<div class="form-group col-md-6 col-xs-12">
							    <input type="text" name="endereco" id="endereco" required minlength=3 class="form-control" placeholder="Endereço">
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
							    <input type="text" name="numero" id="numero" required minlength=1 class="form-control" placeholder="Número">
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
							    <input type="text" name="complemento" id="complemento"   class="form-control" placeholder="Complemento">
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
							    <input type="text" name="bairro" id="bairro" required minlength=3 class="form-control" placeholder="Bairro">
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
							    <select name="uf" id="uf" class="form-control" required >
							    	<option value="-1" > Selecione o Estado </option> 
							    <?php foreach($ufQuery as $uf):?>
							    	<option value="<?php echo $uf->id?>"> <?php echo $uf->sigla?></option>
							    <?php endforeach?>
							    </select>
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
								<select name="cidade" id="cidade" required class="form-control"  >
								<option value="-1" > Cidade </option> 
								</select>
							</div>
							
			        		<div class="form-group col-md-3 col-xs-6">
							    <input type="text" name="cep" id="cep" required minlength=8 class="form-control" placeholder="CEP">
							</div>
							
							
				        		<div class="col-md-12 dados-resp">
					        		<h3>Dados do Responsável</h3>
				        		</div>
								
				        		<div class="form-group col-md-6 col-xs-12 dados-resp">
								    <input type="text" name="nome" id="nome" required minlength=3 class="form-control" placeholder="Nome">
								</div>
								
				        		<div class="form-group col-md-6 col-xs-12 dados-resp">
								    <input type="text" name="sobrenome" id="sobrenome" required minlength=3 class="form-control" placeholder="Sobrenome">
								</div>
								
				        		<div class="form-group col-md-6 col-xs-12 dados-resp">
								    <input type="text" name="cpf_responsavel" id="cpf_responsavel" required minlength=11 class="form-control" placeholder="CPF">
								</div>
								
				        		<div class="form-group col-md-6 col-xs-12 dados-resp" >
								    <input type="text" name="email_responsavel" id="email_responsavel" required minlength=3 class="form-control" placeholder="E-mail do Responsável">
								</div>
							
									
			        		<div class="col-md-12">
				        		<h3>Código do Representante</h3>
			        		</div>
			        		
			        		<div class="form-group col-md-12 col-xs-12">
							    <input type="text" name="cod_representante" id="cod_representante"  minlength=11 class="form-control" placeholder="Código do Representante">
								<small>Se você não tiver um código de representate, deixe este campo em branco.</small>
							</div>
							
			        		<div class="col-md-12">
				        		<h3>Forma de Pagamento</h3>
			        		</div>
			        		
			        		<div class="checkbox col-md-12 col-xs-12">
							    <label><input type="radio" value="B" id="forma_boleto" name="forma_pagamento" class="forma_pagamento" required > Boleto </label>
							</div>
							
			        		<div class="checkbox col-md-12 col-xs-12">
							    <label><input type="radio" value="P" id="forma_cartao" checked=checked name="forma_pagamento" class="forma_pagamento"  required > Cartão de Crédito </label>
							</div>
												        		
					    </div>
					    
					    <div class="row" id="div_cartao" style="display:none">
				        		<div class="col-md-12">
					        		<h3>Dados do Cartão de Crédito</h3>
				        		</div>
				        		
				        		<div class="form-group col-md-4 col-xs-8">
								    <input type="text" name="numero_cartao" id="numero_cartao" required minlength=3 class="form-control" placeholder="Número">
									<input type="hidden" name="bandeira" id="bandeira">
								    <input type="hidden" name="card_token" id="card_token">
								</div>
								
								<div class="col-md-2 col-xs-4">
										<div id="cardLogo"></div>
								</div>
								
				        		<div class="form-group col-md-6 col-xs-12">
								    <input type="text" name="nome_cartao" id="nome_cartao" required minlength=3 class="form-control" placeholder="Nome Impresso no Cartão">
								</div>	
								
								<div class="form-group col-md-2 col-xs-6">
								    <input type="text" name="cvv" id="cvv" required  class="form-control" placeholder="cvv">
								</div>
								
								<div class="form-group col-md-2 col-xs-6">
								    <input type="text" name="validadeMes" id="validadeMes" required minlength=2  class="form-control" placeholder="Mês de Vencimento">
								</div>
								
								<div class="form-group col-md-2 col-xs-6">
								    <input type="text" name="validadeAno" id="validadeAno" required minlength=4  class="form-control" placeholder="Ano de Vencimento">
								</div>
								
								<div class="form-group col-md-6 col-xs-12">
								    <input type="text" name="cpf_cartao" id="cpf_cartao" required minlength=11 class="form-control" placeholder="CPF do Titular do Cartão">
								</div>
								
								<div class="form-group col-md-6 col-xs-12">
								    <input type="text" name="nascimento_cartao" id="nascimento_cartao" required  class="form-control" placeholder="Data de Nascimento">
								</div>
								
								<div class="form-group pull-right col-md-12 col-xs-12">
									<a href="javascript:void(0)" id="proceder_pagamento" class="btn">Finalizar o Pagamento <i class="fa fa-angle-right"></i></a>
								</div>
					        
					    </div>
					    
						<div class="row" id="div_boleto" style="display:none">
			        		<?php /*?>
			        		<div class="col-md-12">
				        		<h3>Boleto Bancário</h3>
				        		<input type="hidden" name="data_vencimento" value="1">
			        		</div>
			        		<div class="form-group col-md-12 col-xs-12">
							    <label>Data de Vencimento de Boleto</label> <br />
							    <select class="col-md-2" name="data_vencimento" id="data_vencimento" required>
							    	<option selected="selected" value="0"> Selecione </option>
							    	<option value="5"> 05 </option>
							    	<option value="15"> 15 </option>
							    	<option value="25"> 25 </option>
							    </select>
							</div>
							<? */ ?>
							
							<div class="form-group pull-right col-md-12 col-xs-12">
								<input type="hidden" name="data_vencimento" value="1">
								<a href="javascript:void(0)" id="finalizar_pedido" class="btn">Finalizar Pedido <i class="fa fa-angle-right"></i></a>
							</div>
									
						</div>
				        
				        </form>

			        </section>
				</div>
			</div>
			
			<div class="overlay">
			  <div class="md">
			    <h2>Total ERP</h2>
			    <p> </p>
			    <button style="display: none" onclick="overlay.trigger('hide');" class="btn app-button text-center"> Fechar </button>
			  </div>			
			</div>
			


<?php get_footer(); ?>