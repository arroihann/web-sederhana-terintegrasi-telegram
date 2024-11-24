<?php
$conn = new mysqli("localhost", "root", "", "library");
$id = $_GET['id'];
$conn->query("DELETE FROM peminjaman WHERE id=$id");
header("Location: dashboard.php");
?>
