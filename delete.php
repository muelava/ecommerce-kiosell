<?php

$conn = mysqli_connect("localhost", "root", "", "login");

$id_user = $_GET["id_user"];

mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id_user'");

echo "<script>location.href='dashboard'</script>";

return mysqli_affected_rows($conn);
