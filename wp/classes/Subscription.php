<?php
session_start();

class Subscription{
	var $amount;
	var $maxAmount;
	var $description;
	var $dateStart;
	var $dateEnd;
	var $itens;
	var $fidelidade;
	
	function __construct(){
		$this->amount = 0;
		$this->maxAmount= 0;
		$this->description = array();
		$this->itens = array();
		
	}
	
	
	
}