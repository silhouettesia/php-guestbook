<?php

function db_connect() {
   @ $result = new mysqli('localhost', 'gbadmin', '12345', 'guestbook');
   if (mysqli_connect_errno()) {
      return false;
   }
   $result->query("SET NAMES 'UTF8'");
   return $result;
}

?>
