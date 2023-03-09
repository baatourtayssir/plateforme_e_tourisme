<?php
$connection = mysqli_connect("localhost","root","");
$db = mysqli_select_db($connection, 'pfe');
    
    if(isset($_POST['editData'])){

        $id= $_POST['update_id'];
        
        $name= $_POST['fname'];
        $email= $_POST['email'];
        $phoneNumber= $_POST['phoneNumber'];
        $adress= $_POST['adress'];
        $description= $_POST['description'];
        $brochureFilename= $_POST['brochureFilename'];

        $query = "UPDATE agence SET  fname='$name' , email='$email' ,  phoneNumber= '$phoneNumber', adress='$adress' , description ='$description', brochureFilename='$brochureFilename' where id='$id' " ;
        $query_run = mysqli_query($connection, $query);

        if($query_run){

            echo' <script> alert ("Data updated"); </script> ';
            header("Location: index.html.twig ");

        }else{

            echo' <script> alert ("Data not updated"); </script> ';



            }

        
    }
?>