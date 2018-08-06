<?php
session_start();
class Item{
	
	protected $id;
	protected $product;
	protected $qtd;
	protected $price;
	protected $price2;
	protected $type;
	protected $code;
	protected $code2;
	
	function __construct($id, $product, $price, $price2, $qtd, $type, $code, $code2 =''){
		$this->id = $id;
		$this->product = $product;
		$this->price = $price;
		$this->price2 = $price2;
		$this->qtd = $qtd;
		$this->type = $type;
		$this->code = $code;
		$this->code2 = $code2;
	}
	
	function setID($id){
		$this->id = $id;
	}
	
	function getID(){
		return $this->id;
	}
	
	function setProduct($product){
		$this->product = $product;
	}
	
	function getProduct(){
		return $this->product;
	}
	
	function setPrice($price){
		$this->price = $price;
	}
		
	function getPrice(){
		return $this->price;
	}
	
	function setPrice2($price2){
		$this->price2 = $price2;
	}
	
	function getPrice2(){
		return $this->price2;
	}
	
	function setQtd($qtd){
		$this->qtd = $qtd;
	}
	
	function getQtd(){
		return $this->qtd;
	}
	
	function setType($type){
		$this->type = $type;
	}
	
	function getType(){
		return $this->type;
	}
	
	function setCode($code){
		$this->code = $code;
	}
	
	function getCode(){
		return $this->code;
	}
	
	function setCode2($code2){
		$this->code2 = $code2;
	}
	
	function getCode2(){
		return $this->code2;
	}
	
}