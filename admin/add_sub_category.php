<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
    exit;
};

if(isset($_POST['add_category'])){
    if(isset($_POST['category_id'])) { // Check if category_id is set
        $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $insert_category = $conn->prepare("INSERT INTO `sub_categories`(category_id, name) VALUES(?, ?)");
        $insert_category->execute([$category_id, $name]);
        $message[] = 'New category added!';
    } else {
        $message[] = 'Please select a category.';
    }
}

if(isset($_POST['update_category'])){
    // Update category code remains unchanged
        if(isset($_POST['category_id'])) { $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $update_category = $conn->prepare("UPDATE `sub_categories` SET name = ? WHERE id = ?");
        $update_category->execute([$category_id, $name]);
        $message[] = "Category updated successfully!";
    } else {
        $message[] = 'Please select a category.';
    }
}

if(isset($_POST['delete_category'])){
    // Delete category code remains unchanged
    if(isset($_POST['category_id'])) { $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
        $delete_category = $conn->prepare("DELETE FROM `sub_categories` WHERE id = ?");
        $delete_category->execute([$category_id]);
        $message[] = "Category deleted successfully!";

}

if(isset($_POST['delete_all'])){    
    // Delete all categories code remains unchanged
        
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="categories">

    <h1 class="heading">Categories</h1>

    <div class="box-container">

        <div class="box add">
            <h3>Add New Sub-Category</h3>
            <form action="" method="post">
                <select name="category_id" class="box" required>
                    <option value="" selected disabled>Select category</option>
                    <?php
                        $select_categories = $conn->prepare("SELECT * FROM `categories`");
                        $select_categories->execute();
                        while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){
                            echo '<option value="'.$fetch_categories['id'].'">'.$fetch_categories['name'].'</option>';
                        }
                    ?>
                </select>
                <input type="text" name="name" class="box" placeholder="Enter category name" maxlength="20" required>
                <input type="submit" value="Add Category" name="add_category" class="btn">
            </form>
        </div>

        <!-- Update and Delete Forms remain unchanged -->

    </div>

</section>

</body>
</html