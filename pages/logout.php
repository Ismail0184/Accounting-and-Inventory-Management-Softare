<?php
   session_start();
   session_destroy();
   unset($_POST);
   header('Location: ../pages/');
?>
