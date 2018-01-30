<?php
  // Destroy the session.
  $_SESSION = array(); 
  
  if(isset($_COOKIE[session_name()])) { 
    setcookie(session_name(), '', time() - 42000, '/'); 
  } 
  
  if(isset($_COOKIE[session_id()])) { 
    setcookie(session_id(), '', time() - 42000, '/'); 
  } 
   
  session_destroy(); 
  
  header("Location: index.php");
?>

