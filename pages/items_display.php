<?php
    session_start();
    require_once('../database/connect.php');
    $db = getDatabaseConnection();
    $stmt = $db->prepare(
        'SELECT * FROM ITEM 
        JOIN PHOTO ON PHOTO.itemId = ITEM.id
        WHERE ITEM.sold = false');
    $stmt->execute();
    $items = $stmt->fetchAll();

    // Items with multiple photos would appear <number of photos> times
    $uniqueItems = array();
    foreach ($items as $item) {
        if (!isset($uniqueItems[$item['id']])) {
            $uniqueItems[$item['id']] = $item;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/items_display_layout.css">
    <link rel="stylesheet" href="../css/items_display_style.css">
    <link rel="stylesheet" href="../css/items_display_responsive.css">
    <link rel="stylesheet" href="../css/header.css">
    <script src="../javascript/dateformat.js"> </script>
    <script src="../javascript/togglemenu.js"> </script>
    <title>Ctrl+Sell | Products</title>

</head>

<body>
<?php include ('header.php')?>

    <main>
    <?php foreach ($uniqueItems as $item):
        $stmt = $db->prepare("SELECT * FROM PHOTO WHERE itemId = :id LIMIT 1");
        $stmt->bindParam(':id' , $item['id']);
        $stmt->execute();
        $photo = $stmt->fetch();

        ?>

        <a href="item.php?id=<?php echo $item['id']; ?>" class="card">
            <img alt='itemimg' src=<?php echo $photo['img'];?> >

            <div class="name">
                <p> <?php echo $item['name'];?> </p>
            </div>
            
            <div id="price">
                <p> <?php echo $item['price'],"€";?> </p>
            </div>

            <div class="uploaded">
                <p> Uploaded <?php echo '<script>document.write(getTimeDifference("' . $item['postTimestamp'] . '"));</script>'; ?>
            </div>

        </a>
    <?php endforeach; ?>

    </main>

</body>
</html>