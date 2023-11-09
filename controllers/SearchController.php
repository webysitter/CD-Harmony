<?php
namespace controllers;

use models\SearchModel;

class SearchController 
{
    public function __construct() {
    }

    public function searchView()
    {
        try {
            
            include 'views/search_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


    public function performSearch()
    {
        try {
            $searchModel = new SearchModel(); 
            $search = $searchModel->getSearchResult(); 
    
          // var_dump($products);
        //    print_r($products);
            // Load the view to display the products
            include 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


    public function showRecentReleases()
    {
        try {
			$tag='recent';
            $productModel = new ProductModel(); // Create an instance of ProductModel
            $products = $productModel->getRecentReleases(); // Call the method on the instance

            // Load the view to display the "New Releases" section
            include'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

	public function showProductDetails($id)
    {
        try {
            $productModel = new ProductModel(); // Create an instance of ProductModel
            $product = $productModel->getProductDetails($id); // Call the method on the instance

            // Load the view to display the "New Releases" section

            include 'views/product_details.php';
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}