<?php

class Cart
{
	private $id;
	private $id_user;
	private $date;
	private $status;
	private $price;
	private $nb_products;
	private $weight;
	private $products;
	private $user;

	private $link;

	public function __construct($link)
	{
		$this->link = $link;
	}

	public function getId()
	{
		return $this->id;
	}
	public function getIdUser()
	{
		return $this->id_user;
	}
	public function getDate()
	{
		return $this->date;
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function getPrice()
	{
		return $this->price;
	}
	public function getNbProducts()
	{
		return $this->nb_products;
	}
	public function getWeight()
	{
		return $this->weight;
	}
	public function getProducts()
	{
		if ($this->products == null)
		{
			$ProductManager = new ProductsManager($this->link);
			$this->products = $ProductManager->findByCart($this);
		}
		return $this->products;
	}
	public function getUser()
	{
		if ($this->user)
		{
			$usersManager = new UsersManager($this->link);
			$this->user = $usersManager->findById($this->id_user);
		}
		return $this->user;
	}


	public function setStatus($status)
	{
		if ($status != 0 && $status != 1)
			throw new Exception("Status invalide");
		$this->status = $status;
	}
	public function setPrice($price)
	{
		$price = str_replace(",",".",$price);
		$price = floatval($price);
		if ($price <=0)
			throw new Exception("Prix invalide");
		$this->price = $price;
	}
	public function setNbProducts($nb_products)
	{
		$nb_products = intval($nb_products);
		if ($nb_products<=0)
			throw new Exception("Quantité invalide");
		$this->nb_products = $nb_products;
	}
	public function setWeight($weight)
	{
		$weight = str_replace(",",".",$weight);
		$weight = floatval($weight);
		if ($weight<=0)
			throw new Exception("Poids invalide");
		$this->weight = $weight;
	}
	public function addProduct(Products $product, $nb)
	{
		$i = 0;
		if ($this->products === null)
			$this->getProducts();
		while ($i < $nb)
		{
			$this->products[] = $product;
			$i++;
		}
	}
	public function removeProduct(Products $product)
	{
		$id_product = $product->getId();
		if ($this->products === null)
			$this->getProducts();
		$saveTab = [];
		$i=0;
		while ($i < count($this->products))
		{
			if ($id_product != $this->products['id'])
				$saveTab = $this->products[$i];
			$i++;
		}
		$this->products = $saveTab;
	}	
}
?>