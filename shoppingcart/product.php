<?php
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        exit('Product does not exist!');
    }
} else {
    // Simple error to display if the id wasn't specified
    exit('Product does not exist!');
}
?>
<?php
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt2 = $pdo->prepare('SELECT title FROM zodiac ');
    $stmt3 = $pdo->prepare('SELECT link FROM zodiac ');
    $stmt2->execute([$_GET['id']]);
    $stmt3->execute([$_GET['id']]);
    // Fetch the zodiac from the database and return all results
    $zodiac = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $image = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    // Check if the zodiac exists
    if (!$zodiac) {
        // Simple error to display if the id for the zodiac
        exit('Product does not exist!');
    }
} else {
    // Simple error to display if the id wasn't specified
    exit('Product does not exist!');
}
?>

<?=template_header('Product')?>

<div class="product content-wrapper">
    <img src="/imgs/<?=$product['img']?>" width="500" height="500" alt="<?=$product['name']?>">
    <div>
        <h1 class="name"><?=$product['name']?></h1>
        <span class="price">
            &dollar;<?=$product['price']?>
            <?php if ($product['rrp'] > 0): ?>
            <span class="rrp">&dollar;<?=$product['rrp']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=cart" method="POST">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Quantity">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <select name="lst_zodiac" id="lst_zodiac" onchange="Swap(this,'MyImg');">  
            <option value="Select">Choose a zodiac</option> 
                <?php foreach ($zodiac as $row): ?>    
                    <option value="<?=$row["title"]?>"><?=$row["title"]?></option>
                <?php endforeach ?>
                
            </select></br>
            <script language="JavaScript" type="text/javascript">
            var Path='/imgs/';
            var ImgAry=new Array('','Dog.png', 'Dragon.png', 'Horse.png', 'Monkey.png', 'Ox.png', 'Pig.png', 'Rabbit.png', 'Rat.png', 'Rooster.png', 'Sheep.png', 'Snake.png', 'Tiger.png');

            function Swap(obj,id){
            var i=obj.selectedIndex;
            if (i<1){ return; }
            document.getElementById(id).src=Path+ImgAry[i];
            }
            
            </script>
            <img id="MyImg" src="https://previews.123rf.com/images/enterline/enterline1801/enterline180100601/93790145-the-word-zodiac-concept-and-theme-painted-in-black-ink-on-a-watercolor-wash-background-.jpg" width=100 height=100 >
            </br>
            <select name="colour" id="colour">  
                <option value="">Select a product colour</option> 
                <option value="White">White</option>  
                <option value="Black">Black</option>  
                <option value="Red">Red</option>  
                <option value="Blue">Blue</option> 
            </select></br>
            <input type="submit" value="Add To Cart">
            
        </form>
        <div class="description">
            <?=$product['desc']?>
        </div>
    </div>
</div>

<?=template_footer()?>

