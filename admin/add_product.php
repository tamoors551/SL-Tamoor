<?php
    include '../components/connect.php';
    // include '\components\connect.php

    if  (isset($_POST['add_product'])){
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_image = $_FILES['product_image']['name'];
        $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
        $product_image_folder = '../uploaded_img/'.$product_image;

        if(empty($product_name) || empty($product_price) || empty($product_description) || empty($product_image)){
            $message[] = 'please fill out all';
        }else{
            $insert_product = $conn->prepare("INSERT INTO `products`(`name`, `details`, `price`, `image_01`, `image_02`, `image_03`) 
            VALUES('$product_name', '$product_price', '$product_description', '$product_image');");
            // $insert_product = $conn->prepare("INSERT INTO `products`(name, price, description, image) VALUES(?,?,?,?)");
            $insert_product->execute([$product_name, $product_price, $product_description, $product_image]);
            
            if($insert_product){
                if($product_image_size > 2000000){
                        $message[] = "image size is too large";
                }else{  
                    move_uploaded_file($product_image_tmp_name, $product_image_folder);
                    $message[] = 'new product added successfully';
                }
            }

        }

    }
    if (isset($_POST['update_product'])){
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = '../uploaded_img/'.$update_image;

        $update_id = $_POST['update_id'];

        $update_query = $conn->prepare("UPDATE `products` SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $update_query->execute([$product_name, $product_price, $product_description, $update_image, $update_id]);
        if($update_query){
            if($update_image_size > 2000000){
            $message[] = " image size is too large";
        }else{
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            $message[] = 'product updated successfully';
        }
        }
    } 
    if (isset($_POST['delete_product'])){
        $delete_id = $_POST['delete_id'];
        $delete_image_query = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
        $delete_image_query->execute([$delete_id]);
        $fetch_delete_image = $delete_image_query->fetch(PDO::FETCH_ASSOC);
        unlink('../uploaded_img/'.$fetch_delete_image['image']);
        $delete_query = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_query->execute([$delete_id]);
        if($delete_query){
            $message[] = 'product deleted successfully';
        }
    }
?>








        