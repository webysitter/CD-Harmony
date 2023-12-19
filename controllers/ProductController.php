<?php
namespace Controllers;

use Models\ProductModel;

use Controllers\AdminController;

use Services\ImageHandler;

use Services\SessionManager;

class ProductController 
{
    private $productModel;
    private $imageHandler;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->imageHandler = new ImageHandler();
        $session = new SessionManager();
        $session->startSession();
    }
    

    public function showProductsByTag($tag)
    {
        try {
            $products= $this->productModel->getProductsByTag($tag);
      
    
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
 
            $products=$this->productModel->getRecentReleases(); // Call the method on the instance

            // Load the view to display the "New Releases" section
            include 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //includes the produt details page for admin or customer - depending on the role
	public function showProductDetails($id, $role = 'none')
    {
        try {
            //route for admin
            if ($role == 'admin') {
                if (AdminController::authorizeAdmin()) {
                    $product = $this->productModel->getProductDetails($id);
                    // Load the view to display the product details if the user is an admin
                    include 'views/admin/product_details.php';
                } else {
                    // Redirect to the login page in case the user is not logged as admin
                    header('Location:'. BASE_URL. '/admin-login');
                    exit();
                }
            //route for customer
            } else {
                //getting an array of variants (new and old) and the product details
                $product = $this->productModel->getProductDetails($id);
                // Load the view to display the product details
                include 'views/product_details.php';
            }
          
          
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //Getting the product variantd details (in this case, the new and old variant of a cd product)
    public function getProductVariantDetails($id)
    {
        try {
            $productVariantsDetails = $this->productModel->getProductVariantsDetails($id);
            return $productVariantsDetails;
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getProductsList()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                return $this->productModel->getAllProducts(); // Call the method on the instance

           
            
                
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showProductForm()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                // Load the view to display the product form
                include 'views/admin/product_form.php';
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function addProduct()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                // Validate the CSRF token on form submission - to ensure that only by authorized admin users
                if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                    // CSRF token is valid
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $errType=[];
                        // Retrieve values from the form
                        $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                        $description = htmlspecialchars(trim($_POST['productDescription']));
                        //converting the number to decimal
                        $price = floatval(htmlspecialchars(trim($_POST['price'])));
                        $quantityInStock = htmlspecialchars(trim($_POST['quantityInStock']));
                        $creationDate = date("Y-m-d h:i:s");  

                        // Validate quantityInStock
                        if (!is_numeric($quantityInStock) || $quantityInStock < 0) {
                            $quantityInStock = 'Quantity in Stock must be a non-negative integer.';
                            $errType['quantityInStock'] = $quantityInStock;
                            
                            
                        }

                        $file = $_FILES['image'];
        
                        $tags = htmlspecialchars(trim($_POST['tags']));
                        $releaseDate = trim($_POST['releaseDate']);
                        $artistTitle = htmlspecialchars(trim($_POST['artistTitle']));



                       // print_r($file);

                        
                        SessionManager::setSessionVariable('errors_output', $errType);
                        if(count($errType)>0){
                            header('Location:'. BASE_URL. '/admin/product/add');
                            exit;
                        }





                        $productModel = new ProductModel();
                        echo $newProductId = $productModel->addProduct($productTitle, $description, $creationDate);
                        exit;
                        if ($success) {

                            //image upload code
                            $image = $this->imageHandler->handleImageUpload($file,"./src/assets/images/albums/");




                            exit;

                            //sets the success message in the session variable
                            SessionManager::setSessionVariable('success_message', 'Product added successfully');
                        }
                    }
                }
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //shows the product form with the product details for editing
    public function showEditProductForm($id){
        try {
          
            if(SessionManager::isAdmin()) {
                // Get the product details from the database
                $productDetails = $this->productModel->getProductDetails($id);
                // Load the view to display the product form
                include 'views/admin/edit-product-form.php';
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //updates the product (product variant) details
    public function updateProduct() {
        try {
            if(SessionManager::isAdmin()) {
                // Validate the CSRF token on form submission - to ensure that only by authorized admin users
                if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                    // CSRF token is valid
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Retrieve values from the form
                        $productId = htmlspecialchars(trim($_POST['product_id']));
                        $productVariantId = htmlspecialchars(trim($_POST['productVariantId']));
                        $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                        $description = htmlspecialchars(trim($_POST['productDescription']));
                        $price = htmlspecialchars(trim($_POST['price']));
                        $stock = htmlspecialchars(trim($_POST['QuantatyInStock']));
                        $image = htmlspecialchars(trim($_POST['image']));
                        $tags = htmlspecialchars(trim($_POST['tags']));
                        $releaseDate = htmlspecialchars(trim($_POST['release_date']));
                        $artistTitle = htmlspecialchars(trim($_POST['artistTitle']));
    
                        $productModel = new ProductModel();
                        $success = $productModel->updateProduct($productVariantId, $productTitle, $artistTitle, $description, $price, $stock, $image, $tags, $releaseDate);
                        if ($success) {
                            //sets the success message in the session variable
                            SessionManager::setSessionVariable('success_message', 'Product updated successfully');
                        }
                    }
                }
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //deletes the product (product variant) 
    public function deleteProduct(){
        try {
            if(SessionManager::isAdmin()) {
                // Validate the CSRF token on form submission - to ensure that only by authorized admin users
                if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                    // CSRF token is valid
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Retrieve values from the form
                        $productId = htmlspecialchars(trim($_POST['product_id']));
                        $productVariantId = htmlspecialchars(trim($_POST['productVariantId']));
    
                        $productModel = new ProductModel();
                        $success = $productModel->deleteProduct($productVariantId);
                        if ($success) {
                            //sets the success message in the session variable
                            SessionManager::setSessionVariable('success_message', 'Product deleted successfully');
                        }
                    }
                }
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getAllVariants()
    {
        try {
            if(SessionManager::isAdmin()) {
                return $this->productModel->getAllVariants(); // Call the method on the instance
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showAddProductForm()    
    {
        try {
            if(SessionManager::isAdmin()) {
                
                // Load the view to display the product form
                include 'views/admin/add-product.php';
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
