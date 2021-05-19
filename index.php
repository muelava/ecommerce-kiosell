<?php

session_start();

if (isset($_SESSION["login"])) {
    header("Location: dashboard");
}

$conn = mysqli_connect("localhost", "root", "", "login");


if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row["password"]) {
            header("Location: dashboard");
            $_SESSION["login"] = true;
            exit;
        }
    }
    $error = true;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php if (isset($error)) : ?>
        <script>
            alert('username / password yang anda masukan salah!');
        </script>
    <?php endif; ?>


    <form action="" method="POST" style="width: 300px; margin: 15% auto">
        <ul style="list-style: none;">
            <li><input type="text" placeholder="name" name="username" autofocus required></li><br>
            <li><input type="password" placeholder="password" name="password" required></li><br>
            <button name="login" type="submit">Login</button><br> dont have account?
            <a href="signup">Sign Up</a>
        </ul>
    </form>

</body>

</html>