<?php
// Category.class.php -> PascalCase
class Category
{
	// Déclaration des propriétés privées
	private $id;
	private $name;
	private $description;

	private $link;

	public function __construct($link)
	{
		$this->link = $link;
	}

	// Getter/Setter | Accesseur/Mutateur | Accessor/Mutator
	public function getId()
	{
		return $this->id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setName($name)
	{
		if (strlen($name) < 4)
			throw new Exception ("Nom trop court (< 4)");
		else if (strlen($name) > 31)
			throw new Exception ("Nom trop long (> 31)");
		$this->name = $name;
	}

	public function setDescription($description)
	{
		if (strlen($description) < 4)
			throw new Exception ("Description trop courte (< 4)");
		else if (strlen($description) > 123)
			throw new Exception ("Description trop longue (> 123)");
		$this->description = $description;
	}

	public function getSubCategory(Category $category)
	{
		$list = [];
		$id_category = $category->getId();
		if ($id_category)
		{
			$request = "SELECT * FROM sub_category WHERE id_category=".$id_category;
			$res = mysqli_query($this->link, $request);
			while ($sub_category = mysqli_fetch_object($res, "SubCategory"))
				$list[] = $sub_category;
			return $list;
		}
	}
	public function getProducts(Category $category)
	{
		$list = [];
		$id_category = $category->getId();
		$request = "SELECT products.* 
					FROM products
					INNER JOIN sub_category
					ON sub_category.id = products.id_sub_cat
					WHERE sub_category.id_category = ".$id_category;
		$res = mysqli_query($this->link, $request);
		while($product = mysqli_fetch_object($res, "Products", [$this->link]))
			$list[] = $product;
		return $list;
	}
}
?>