<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
};

if(isset($_POST['add_category'])){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $insert_category = $conn->prepare("INSERT INTO `categories`(name) VALUES(?)");
    $insert_category->execute([$name]);
    $message[] = 'New category added!';
}

if(isset($_POST['update_category'])){
    $update_id = $_POST['update_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $update_category = $conn->prepare("UPDATE `categories` SET name = ? WHERE id = ?");
    $update_category->execute([$name, $update_id]);
    $message[] = 'category updated successfully!';
}
if(isset($_POST['delete_category'])){
    $delete_id = $_POST['delete_id'];
    $delete_category = $conn->prepare("DELETE FROM `categories` WHERE id = ?");
    $delete_category->execute([$delete_id]);
    $message[] = 'category deleted successfully!';
}   
if(isset($_POST['delete_all'])){    
    $delete_all = $conn->prepare("DELETE FROM `categories`");
    $delete_all->execute();
    $message[] = 'All category deleted successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="categories">

    <h1 class="heading">categories</h1>

    <div class="box-container">

        <div class="box add">
            <h3>add new category</h3>
            <form action="" method="post">
                <input type="text" name="name" class="box" placeholder="enter category name" maxlength="20" required>
                <input type="submit" value="add category" name="add_category" class="btn">
            </form>
        </div>

        <div class="box update"> 
            <h3>update category</h3>
            <form action="" method="post">
                <select name="update_id" class="box" required>
                    <option value="" selected disabled>select category to update</option>
                    <?php
                        $select_category = $conn->prepare("SELECT * FROM `categories`");
                        $select_category->execute();
                        if($select_category->rowCount() > 0){
                            while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <option value="<?= $fetch_category['id']; ?>"><?= $fetch_category['name']; ?></option>
                    <?php
                            }
                        }else{
                            echo '<option value="" selected disabled>no category added yet!</option>';
                        }
                    ?>
                </select>
                <input type="text" name="name" class="box" placeholder="enter category name" maxlength="20" required>
                <input type="submit" value="update category" name="update_category" class="btn">
            </form>
        </div>
    
    </div>

        <div class="box delete"> 
        
        <h3>delete category</h3>
        <form action="" method="post">
            <select name="delete_id" class="box" required>
                <option value="" selected disabled>select category to delete</option>
                <?php
                    $select_category = $conn->prepare("SELECT * FROM `categories`");
                    $select_category->execute();
                    if($select_category->rowCount() > 0){
                        while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
                ?>
                <option value="<?= $fetch_category['id']; ?>"><?= $fetch_category['name']; ?></option>
                <?php
                        }
                    }else{
                        echo '<option value="" selected disabled>no category added yet!</option>';
                    }
                ?>
            </select>
            <input type="submit" value="delete category" name="delete_category" class="btn">
        </form>
        </div>

    </div>

    </div>  

</section>


</body>
</html>
