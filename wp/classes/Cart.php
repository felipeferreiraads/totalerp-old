<?php
session_start();
class Cart{
	protected $cart;
	protected $type;
	
	function __construct(){
		$this->retrive();
	}
	
	function setType($type){
		$this->type = $type;
	}
	
	function getType(){
		return $this->type;
	}
	
	function add($item = null){
		if(!is_null($item)){
			array_push($this->cart, $item);
			$this->save();
		}
	}
	
	function get($id){
		if ( array_key_exists($id, $this->cart)){
			return $this->cart[$id];
		}
		return false;
	}
	
	function listar(){
		return $this->cart;
	}
	
	function update($item, $key){
		$this->cart[$key] = $item;
		$this->save();
	}
	
	function delete($id = -1 ){
		if( array_key_exists($id, $this->cart)){
			unset($this->cart[$id]);
			$this->cart = array_values( $this->cart );
		}
		$this->save();
	}
	
	function getTotal(){
		$total = 0.0;

		foreach($this->cart as $item){ 
			if( $item){
				$subtotal = ($this->type == 2? $item->getPrice2() : $item->getPrice()) * $item->getQtd();
				$total += $subtotal;
			}
		}
		return $total;
	}
	
	function clear(){
		$this->cart = array();
		$this->save();
	}
	
	protected function retrive(){
		if(array_key_exists('CART', $_SESSION)){
			$data = $_SESSION['CART'];
			if(is_array(unserialize($data))){
				$this->cart = unserialize($data);
			}else {
				$this->cart = array();
			}
			$this->type = $_SESSION['CART_TYPE'];
		}else{
			$this->cart = array();
			$this->type = 1;
		}
		$this->save();
	}
	
	protected function save(){
		foreach($this->cart as $k =>$v){
			if(!$v){
				unset($this->cart[$k]);
			}
		}
		$_SESSION['CART_TYPE'] = $this->type; 
		$_SESSION['CART'] = serialize($this->cart);
	}
}