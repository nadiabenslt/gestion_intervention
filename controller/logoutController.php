<?php 

session_start();
session_destroy();
setcookie("role", "", time() - 1); 

header("Location: ../view/index.php");
