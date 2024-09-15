<?php

require_once '../inc/connection.php';

if (isset($_POST['submit']) && $_GET['id']){
    $id = (int) $_GET['id'];

    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['body']));

     //============validation============
     $errors = [];
     //title
     if(empty($title)) {
         $errors[] = "title is required";
     } elseif(is_numeric($title)){
         $errors[] = "title must be a string";
     }
     //body
     if(empty($body)) {
         $errors[] = "body is required";
     } elseif(is_numeric($title)){
         $errors[] = "body must be a string";
     }

     //check
     $query = "select id, image from posts where id=$id";
     $runQuery = mysqli_query($conn,$query);
     if(mysqli_num_rows($runQuery) == 1){
        $post = mysqli_fetch_assoc($runQuery);
        $oldImage = $post['image'];


        //check image
         if(! empty($_FILES['image']['name'])){
            //check
            $image = $_FILES['image'];
            $imageName = $image['name'];
            $imagetmpName = $image['tmp_name'];
            $size = $image['size'] / (1024*1024) ;
            $ext = strtolower(pathinfo($imageName,PATHINFO_EXTENSION));
            $error = $image['error'];
            $newName = uniqid().".$ext";
            
        //validation
        $array_ext = ['png','jpg','jpeg','gif'];
        if($error !=0) {
            $errors[] = "image is required please";
        }elseif(!in_array($ext , $array_ext)){
            $errors[] = "image is not correct";
        }elseif($size > 1){
            $errors[] = "image large size";
        }

            $newName = uniqid().".$ext";
         }else{
            $newName = $oldImage;
         }

        //update
        if(empty($errors)){
        $query = "update posts set `title`='$title' , `body`='$body' , `image`='$newName' where id = $id";
        $runQuery = mysqli_query($conn,$query);

        if($runQuery){
            if(! empty($_FILES['image']['name'])){
                unlink("../uploads/$oldImage");
                move_uploaded_file($imagetmpName,"../uploads/$newName");
            }
            $_SESSION['success'] = "post updated successfully";
            header("location:../viewPost.php?id=$id");
        }else{
            $_SESSION['errors'] = ["error while add post"];
            header("location:../editPost.php?id=$id");
        }
    
        }else{
            $_SESSION['errors'] = $errors;
            $_SESSION['title'] = $title;
            $_SESSION['body'] = $body;
            header("location:../editPost.php?id=$id");
        }



     } else{
        $_SESSION['errors'] = ["post not found"];
        header("location:../index.php");
     }


}else{
    $_SESSION['errors'] = ["please choose correct operation"];
    header("location:../index.php");
}