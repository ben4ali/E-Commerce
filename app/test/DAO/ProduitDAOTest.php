<?php

namespace DAO;

//require_once DIR . "/../../../app/model/Item.php";
//require_once DIR . "/../../../app/model/DAO/ProduitDAO.php";
//require_once DIR . "/../../../app/database/DBController.php";

use model\DAO\ItemDAO;
use model\Item;
use PHPUnit\Framework\TestCase;

class ProduitDAOTest extends TestCase
{
    protected $produitDAO;

    public function testCreate()
    {
        $produitDAO = ItemDAO::getInstance();

        $testProduct = new Item(
            1,
            1,
            'Pomme',
            'Rouge',
            1.99,
            10,
            '0123456780',
            'Fruit',
            'images/test-produit.jpg',
            'Nourriture'
        );
        $produitDAO->create($testProduct);

        $testProduct->setId('id');
        $testProduct->setCompanyId('company_id');
        $testProduct->setName('name');
        $testProduct->setDescription('description');
        $testProduct->setPrice('price');
        $testProduct->setQuantity('quantity');
        $testProduct->setUpc('upc');
        $testProduct->setCategory('category');

        $testProduct->setId($produitDAO->getById("1")->getId());

        $this->assertTrue($produitDAO->update($testProduct));

        $retrievedProduit = $produitDAO->getById('1');
        $this->assertInstanceOf(Item::class, $retrievedProduit);

        $this->assertTrue($produitDAO->delete($retrievedProduit));
    }

    public function testDelete()
    {


    }

    public function testUpdateQuantity()
    {

    }

    public function testUpdate()
    {

    }
}
