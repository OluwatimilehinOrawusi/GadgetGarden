<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
</head>
<body>
    <div class = "wishlistcon">
        <h1>Bookmarked Items</h1>
        <?php if ($items) : ?>
            <?php foreach ($items as $item): ?>
                <div clas = "wishlistcrd">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt = "Image">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>£<?php echo htmlspecialchars($item['price']); ?></p>
                    <a href = "productPage.php?id=<?php echo $item['product_id']; ?>"><button class = "green-button">View</button></a>
                     <!-- add remove wishlist link/page  -->
            </div>
            <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <h5>You have no bookmarked items</h5>
                    <?php endif; ?>
    </div>
    
    <?php require_once "../partials/footer.php"
</body>
</html>