<?php

require_once '../inc/connection.php';

//catching id , query check , query delete

if(isset($_POST['submit']) && isset($_GET['id'])){

     $id =(int) $_GET['id'];
     $query = "select id , image from posts where id=$id";
     $runQuery = mysqli_query($conn , $query);
     if(mysqli_num_rows($runQuery)==1){
        //fetch
        $post = mysqli_fetch_assoc($runQuery);
        //unlink for the photo
        $image = $post['image'];
        unlink("../uploads/$image");
        

        //delete
        $query = "delete from posts where id = $id";
        $runQuery = mysqli_query($conn,$query);
        if($runQuery){
            $_SESSION['success'] = "post deleted  successfuly";
            header("location:../index.php");
        }else{
            $_SESSION['errors'] = ["error while deleting"];
            header("location:../index.php");
        }

     }else{
        $_SESSION['errors'] = ["post not found"];
        header("location:../index.php");
     }

}else{
    $_SESSION['errors'] = ["please choose correct operation"];
    header("location:../index.php");
}