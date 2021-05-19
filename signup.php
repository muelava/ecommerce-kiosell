<?php

$conn = mysqli_connect("localhost", "root", "", "login");

if (isset($_POST["signup"])) {

    $username = strtolower(stripslashes($_POST["username"]));
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $password2 = mysqli_real_escape_string($conn, $_POST["password2"]);

    $result = mysqli_query($conn, "SELECT username from user WHERE username = '$username'");



    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username telah digunakan'); location.href = 'signup'; </script>";
        return false;
    }

    if ($password != $password2) {
        echo "<script>alert('Password tidak sesuai');</script>";
        return false;
    }
    echo "<script>alert('Berhasil, silakan login!'); document.location.href='index'</script>";

    mysqli_query($conn, "INSERT INTO user VALUES('','$username','$password')");

    return mysqli_affected_rows($conn);
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>

    <form action="" method="POST" style="width: 300px; margin: 15% auto">
        <ul style="list-style: none;">
            <li><input type="text" placeholder="name" name="username" autofocus required></li><br>
            <li><input type="password" placeholder="password" name="password" required></li><br>
            <li><input type="password" placeholder="confirm password" name="password2" required></li><br>
            <button name="signup" type="submit">Sign Up</button><br> You have account?
            <a href="index">Login</a>
        </ul>
    </form>
</body>

</html>