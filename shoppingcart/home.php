<?php
Setcookie(session_id(), time()+3600);
?>

<?php
// Get the 4 most recently added products
$stmt = $pdo->prepare('SELECT * FROM products ORDER BY date_added DESC LIMIT 4');
$stmt->execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Home')?>

<div class="featured">
    <div class="p-8 flex flex-col rounded-xl bg-gray-800">
        <h2>Got Custom?</h2>
        <p>Welcome to my sample of a PHP store! </p>
    </div>
</div>
<div class="recentlyadded content-wrapper">
    <p class="text-center big-text">Recently Added Products</p>
    <div class="margin-left big-text test col4 products">
        <?php foreach ($recently_added_products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="big-text product">
            <img class="" src="/imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['name']?>">
            <span class="big-text name"><?=$product['name']?></span>
            <span class="big-text price">
                &dollar;<?=$product['price']?>
                <?php if ($product['rrp'] > 0): ?>
                <span class="big-text rrp">&dollar;<?=$product['rrp']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<p></p>
<?=template_footer()?>