<?php
  session_start(); 
 
  //var_dump($_SESSION);
  
  $favcity = trim($_POST["favcity"]);

  if(!empty($favcity)){

      // Sanitize
      $favcity_s = strip_tags($favcity);

      //Escape
      $favcity_c = htmlspecialchars($favcity_s);
      
      $_SESSION['favs'][] = $favcity_c;

  }else{
      die();
  }
  
  header("Location: index.php");

?>

