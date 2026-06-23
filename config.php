<?php
session_start();
$conn = new mysqli("localhost","root","","sp_lele");
if ($conn->connect_error) { die("DB Error"); }
?>