<?php
function pdo_connect_mysql() {
    // MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'root';
    $DATABASE_NAME = 'shoppingcart';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}
// Template header
function template_header($title) {
if($title == "PlaceOrder"){
    $num_items_in_cart = "0";
}
else{
    // Get the amount of items in the shopping cart, this will be displayed in the header.
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

}

echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.1/tailwind.min.css">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-G4X9QB913Y"></script>
      
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-G4X9QB913Y');
        </script>
        
	</head>
	<body>
        <header>
            <div class="test col0 content-wrapper">
                <h1 class="test col1 text-center margin-left">SAMPLE PROJECT</h1>
                <nav class="test col2">
                    <a class="col1" href="index.php">Home</a>
                    <a class="col1" href="index.php?page=products">Products</a>
                </nav>
                <div class="margin-right test col3 link-icons">
                    <a class="" href="index.php?page=cart">
                        <i class=" fas fa-shopping-cart"></i>
                        <span class="">$num_items_in_cart</span>
					</a>
                </div>
            </div>
        </header>
        <main>
EOT;
}
// Template footer
function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Sample Project INC</p>
            </div>
        </footer>
        <script src="script.js"></script>
    </body>
</html>
EOT;
}
?>