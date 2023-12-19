<?php 

require_once "bootstrap.php";

use Controllers\MainViewController;
use Controllers\ProductController;
use Controllers\ArticleController;
use Controllers\SpecialOfferController;
use Controllers\ContactController;
use Controllers\LoginController;
use Controllers\UserController;
use Controllers\AdminController;
use Controllers\CompanyController;
use Controllers\CartController;
use Controllers\TestController;


//define("BASE_URL", "http://localhost/cdharmony");
require_once "./config/constants.php";

require_once "router.php";

//Home page route
route('/cdharmony/', 'GET', function () {
    $controller = new MainViewController();
    $controller->showMainView();
});

//insert data into the database
route('/cdharmony/test', 'GET', function () {
    $controller = new TestController();
    $controller->insertData();
});

// Add item to cart
/*route('/cdharmony/cart/add', 'POST', function () {
    $controller = new CartController();
    $requestBody = file_get_contents('php://input');
    $requestData = json_decode($requestBody, true);
    $quantity = $requestData['quantity'];
    $product_variant_id = $requestData['product_variant_id'];
    $controller->addToCart($quantity, $product_variant_id);
    
});*/
route('/cdharmony/cart/add/(\d+)/id/(\d+)', 'GET', function ($qty,$product_variant_id) {
    
    $controller = new CartController();
    $controller->addToCart($qty, $product_variant_id);
});

// Remove item from cart
route('/cdharmony/cart/remove/(\d+)', 'POST', function ($id) {
    $controller = new CartController();
    $controller->removeFromCart($id);
});

// Checkout
route('/cdharmony/cart/checkout', 'POST', function () {
    $controller = new CartController();
    $controller->checkout();
});

// View cart
route('/cdharmony/cart', 'GET', function () {
    $controller = new CartController();
    $controller->viewCart();
});


route('/cdharmony/account', 'GET', function () {
    $controller = new UserController();
    $controller->accountView();
});

//Shows product details
route('/cdharmony/product/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    //when accessing the product details from the main page, the role is set to none
    $controller->showProductDetails($id, 'none');
});


route('/cdharmony/admin/product/(\d+)', 'POST', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id,'admin');
});

route('/cdharmony/admin/product/delete/(\d+)', 'DELETE', function ($id) {
    $controller = new ProductController();
    $controller->deleteProduct($id);
});

route('/cdharmony/admin/product/edit/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    $controller->showEditProductForm($id);
});

route('/cdharmony/admin/product/update/', 'PUT', function () {
    $controller = new ProductController();
    $controller->updateProduct();
});

route('/cdharmony/admin/product/add/', 'POST', function () {
    $controller = new ProductController();
    $controller->addProduct();
});
route('/cdharmony/admin/product/add', 'GET', function () {
    $controller = new ProductController();
    $controller->showAddProductForm();
});

route('/cdharmony/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/admin/article/delete/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
});

route('/cdharmony/admin/article/update/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->updateArticle($id);
});

route('/cdharmony/admin/article/create/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->createArticle($id);
});

route('/cdharmony/admin/special-offers/', 'GET', function () {
    $controller = new SpecialOfferController();
    $controller->showSpecialOffers();
});


route('/cdharmony/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/contact/', 'GET', function() {
    $controller = new ContactController();
    $controller->contactView();
});


route('/cdharmony/contact/', 'POST', function() {
    $controller = new ContactController();
    $controller->contactInput();
});

route('/cdharmony/login/', 'GET', function() {
    $controller = new LoginController();
    $controller->loginView();
});
route('/cdharmony/login/', 'POST', function() {
    $controller = new UserController();
    $controller->authenticateUser();
});

route('/cdharmony/logout/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->logout();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new UserController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new UserController();
    $controller->createAccount();
});

//not implemented yet
route('/cdharmony/search/', 'GET', function () {
    $controller = new SearchController();
    $controller->searchView();
});



route('/cdharmony/admin/', 'GET', function () {
    $controller = new AdminController();
    $controller->adminView();
});

route('/cdharmony/admin/product/', 'GET', function () {
    $controller = new ProductController();
    $controller->showProductList();
});

route('/cdharmony/admin-login', 'GET', function() {
    $controller = new AdminController();
    $controller->adminLoginView();

});

route('/cdharmony/admin-login', 'POST', function() {
  $controller = new AdminController();
  $controller->adminLogin();
 
});


route('/cdharmony/admin/company/', 'GET', function () {
    $controller = new CompanyController();  
    $controller->showCompanyDetails();
});

route('/cdharmony/admin/company/', 'POST', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/articles/', 'GET', function () {
    $controller = new ArticleController();
    $controller->getArticles();
});

route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new AdminController();
    $controller->showProducts();
});

route('/cdharmony/admin/products/', 'POST', function () {
    $controller = new AdminController();
    $controller->handleProduct();
});




// Dispatch the router
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
// If the method is POST, check for method override
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']); // Use the method override
}
dispatch($path, $method);




