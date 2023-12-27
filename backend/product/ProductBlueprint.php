<?php

namespace Product;

// abstract class for the products

abstract class ProductBlueprint {

        protected $id;
        protected $sku;
        protected $name;
        protected $price;
        protected $product_type;

        abstract public function __construct($db);

        //setters and getters
    abstract function setId($id);

    abstract function getId();

    abstract function getSku();

    abstract function setSku($sku);

    abstract function getName();

    abstract function setName($name);

    abstract function getPrice();

    abstract function setPrice($price);


    abstract function getProductType();


    abstract function setProductType($product_type);

    // funtion that deals with creating a new product and saves it to db
    abstract function create();

    //function that reads product off the database to display it's information
    abstract function read();

    //function that deals with deleting the product
    abstract function delete($conn);
}