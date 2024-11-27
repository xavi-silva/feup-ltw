<?php
require_once('../database/connect.php');
$db = getDatabaseConnection();
$stmt = $db->prepare("SELECT * FROM USER");
$stmt->execute();
$users=$stmt->fetchAll();
$stmt = $db->prepare("SELECT * FROM CATEGORY");
$stmt->execute();
$categories=$stmt->fetchAll();

$stmt = $db->prepare("SELECT * FROM CONDITION");
$stmt->execute();
$conditions=$stmt->fetchAll();


if (isset($_GET['option'])) {
    $option = $_GET['option'];

    if ($option === 'users') {
        echo "<h1 class='option-title'>Assign admin role </h1>";
        foreach ($users as $user): ?>
            <?php if ($user['isAdmin']) continue; ?>
            <div class="user-container">
                <p class="user-name"><?php echo $user['username']; ?></p>
                <form action="../actions/action_make_user_admin.php" method="post">
                    <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to make this user admin?')" class="make-admin-button">Make Admin</button>
                </form>
            </div>
        <?php endforeach;

        } elseif ($option === 'category') {
        echo "<h1 class='option-title'>Categories</h1>";?>
        <?php foreach ($categories as $category): ?>
    <?php if ($category['categoryId'] == 7) continue; ?>
    <div class="category-container">
        <p id="categoryName"><?php echo $category['categoryName']; ?></p>
        <form action="../actions/action_remove_category.php" method="post">
            <input type="hidden" name="categoryId" class='input-field' value="<?php echo $category['categoryId']; ?>">
            <button type="submit" onclick="return confirm('Are you sure you want to remove this category?')" class="remove-button">Remove</button>
        </form>
    </div>
<?php endforeach; ?>
        <?php echo "
              <form action='../actions/action_add_category.php' method='post'>
                  <input type='text' name='categoryName' class='input-field' placeholder='Enter category name'>
                  <button type='submit' class='remove-button' id='addCategoryButton'> Create </button>
              </form>
              <div id='error-message' style='color: red;'></div>";


    } elseif ($option === 'condition') {
        echo "<h1 class='option-title'>Conditions</h1>";?>

        <?php foreach ($conditions as $condition): ?>
            <?php if ($condition['conditionId'] == 6) continue; ?>
            <div class="condition-container">
                <p id='conditionName'><?php echo $condition['conditionName']; ?></p>
                          <form action='../actions/action_remove_condition.php' method='post'>
                              <input type='hidden' name='conditionId' value=<?php echo $condition['conditionId'] ?> >
                              <button type='submit' onclick="return confirm('Are you sure you want to remove this condition?')" class='remove-button' id='removeConditionButton'> Remove</button>
                          </form>
            </div>
        <?php endforeach; ?>

        <?php echo "
            <div class='condition-container'>
                <form action='../actions/action_add_condition.php' method='post'>
                    <input type='text' name='conditionName' class='input-field' placeholder='Enter condition name'>
                    <button type='submit' class='remove-button' id='addConditionButton'>Create</button>
                </form>
                <div id='error-message' style='color: red;'></div>
            </div>";

}
}
?>