<?php
	if (isset($_POST['action']))
	{
		if (isset($_SESSION['id_user']))
		{
			if ($_SESSION['admin'] == 0)
			{
				$cartManager = new CartManager($link);
				$productsManager = new ProductsManager($link);
				$currentCart = $cartManager->findCurrentCart($_SESSION['user']);
				if ($_POST['action'] == 'addProduct')
				{
					if (!isset($_POST['size']))
						$error = "Enter a size";
					if (!isset($_POST['quantity']))
						$error = "Enter a quantity";
					if (empty($error))
					{
						try
						{
							$product = $productsManager->findById($_POST['id_product']);
							$quantity = intval($_POST["quantity"]);
							$currentCart->setNbProducts($quantity);
							$currentCart->addProduct($product, $quantity);
							$product->changeStock(-$quantity);
							$i=0;
							while ($i < $quantity)
							{
								$currentCart->setPrice($product->getPrice());
								$currentCart->setWeight($product->getWeight());
								$i++;
							}
							$cartManager->update($currentCart);
							$productsManager->update($product);
						}
						catch (Exception $exception)
						{
							$error = $exception->getMessage();
						}
					}
				}
				if ($_POST['action'] == 'removeProduct')
				{
					if (!isset($_POST['quantity']))
						$error = "Enter a quantity";
					if (empty($error))
					{
						try
						{
							$product = $productsManager->findById($_POST['id_product']);
							$quantity = intval($_POST["quantity"]);
							$product->changeStock($quantity);
							$currentCart->removeProduct($product);
							$currentCart->setNbProducts(-$quantity);
							$i = 0;
							while ($i< $quantity)
							{
								$currentCart->setPrice(-$product->getPrice());
								$currentCart->setWeight(-$product->getWeight());
								$i++;
							}
							$cartManager->update($currentCart);
							$productsManager->update($product);
						}
						catch (Exception $exception)
						{
							$error = $exception->getMessage();
						}
					}
				}
				if ($_POST['action'] == 'valid')
				{
					$products = $currentCart->getProducts();
					if ($products == null)
						$error = "You can't check out an empty cart";
					//Vérifier si adresse présente					
					if (empty($error))
					{
						try
						{
						$currentCart->setStatus(1);
						$cartManager->update($currentCart);
						}
						catch (Exception $exception)
						{
							$error = $exception->getMessage();
						}
					}

				}
			}
			else if ($_SESSION['admin'] == 1)
			{

			}
		}
		else 
		{
			header('Location: index.php?page=login');
			exit;
		}
	}
?>