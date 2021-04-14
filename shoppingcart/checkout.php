<?php
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $zodiac = $_POST['lst_zodiac'];
    $colour = $_POST['colour'];
    // Prepare the SQL statement
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if ($product && $quantity > 0) {
        // Product exists in database
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Product exists in cart so just update the quanity
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Product is not in cart so add it
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // There are no products in cart
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    $_SESSION['cart']['zodiac'] = $zodiac;
    $_SESSION['cart']['colour'] = $colour;
    // Prevent form resubmission
    header('location: index.php?page=cart');
    exit;
}
// Remove product from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Remove the product from the shopping cart
    unset($_SESSION['cart'][$_GET['remove']]);
}
// Update product quantities in cart if the user clicks the "Update" button on the shopping cart page
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Always do checks and validation
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Update new quantity
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }

    // Prevent form resubmission...
    header('location: index.php?page=cart');
    exit;
}
// Send the user to the place order page if they click the Place Order button, also the cart should not be empty
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: index.php?page=checkout');
    exit;
}

// Check the session variable for products in cart
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();

//die();
$subtotal = 0.00;
// If there are products in cart
if ($products_in_cart) {
    // There are products in the cart so we need to select those products from the database
    // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ')');
    // We only need the array keys, not the values, the keys are the id's of the products
    $stmt->execute(array_keys($products_in_cart));
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calculate the subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
    }

    //$products['zodiac'] = $_SESSION['cart']['zodiac'];
    //$products['colour'] = $_SESSION['cart']['colour'];
}


?>


<?= template_header('Cart') ?>


<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <p>Already have and account?</p>
    <input type="submit" value="Login" name="Login">

    <form action="index.php?page=placeorder" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="5" style="text-align:center;">Customer details</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="5" style="text-align:center;">First Name: <input type="text" name="firstname">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:center;">Last Name: <input type="text" name="lastname">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Your Email: <input type="email" name="email" placeholder="example@gmail.com" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:center;">street: <input type="text" name="street">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:center;">Province: <input type="text" name="province">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:center;">Phone: <input type="text" name="phone">
                    </td>
                </tr>
            </tbody>
        </table>
        </br></br></br>
        <h1>Confirm your order details and place order below</h1>
        <table>
            <thead>
                <tr>

                    <td colspan="5" style="text-align:center;">Order Details</td>
                </tr>
            </thead>
            <tbody>
                <div class="subtotal">
                <div class="buttons">
                    <input type="submit" value="Place Order" name="placeorder">
                    <p>Review your order below before you place your order</p>
                </div>
                    <span class="text">Subtotal</span>
                    <span class="price">&dollar;<?= $subtotal ?></span>
                </div>
                <?php if (empty($products)) : ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td class="img">
                                <a href="index.php?page=product&id=<?= $product['id'] ?>">
                                    <img src="/imgs/<?= $product['img'] ?>" width="50" height="50" alt="<?= $product['name'] ?>">
                                </a>
                            </td>
                            <td>
                                <a href="index.php?page=product&id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
<!-- THE TWO ECHO STATEMENTS BELOW CAUSE ME TO HAVE TO LOCATE THE PLACE ORDER BUTTON ABOVE THE FORM -->
                                <?php
                                //echo ', ' . 'Zodiac symbol: ' . $products['zodiac'];
                                echo ' , ' . 'Item colour: ' . $products['colour'];
                                ?>
                                <br>
                            </td>
                            <td class="price">&dollar;<?= $product['price'] ?></td>

                            <td class="price">&dollar;<?= $product['price'] * $products_in_cart[$product['id']] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
<?php
if (isset($_POST["placeorder"])) {
    //create database connection
    $mysqli = new mysqli("localhost", "root", "root", "shoppingcart");
    // Checking the database connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    //declaring php varibles
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    //adding data to the database; used NULL to auto-increment the customer ID in the
    //duct_details table in the shoppingcart database
    $sqli = "INSERT INTO cust_details(custID, email, firstname, lastname) 
                VALUES (NULL, '$_POST[email]', '$_POST[firstname]', '$_POST[lastname]'";
    //verifing that a record has been made to the database
    if (mysqli_query($mysqli, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sqli . "" . mysqli_error($mysqli);
    }
    //closing the database connection
    $mysqli->close();
}
?>

<?= template_footer() ?>