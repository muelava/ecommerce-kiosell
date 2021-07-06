<?php

session_start();

if (isset($_SESSION["login"])) {
    header("Location: admin");
}

$conn = mysqli_connect("localhost", "root", "", "kiosell");


if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' or email = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row["password"]) {
            header("Location: admin");
            $_SESSION["login"] = $username;
            $_SESSION["status"] = $row["status"];
            exit;
        }
    } else {
        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' or email = '$username'");

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if ($password === $row["password"]) {
                header("Location: admin");
                $_SESSION["login"] = $username;
                $_SESSION["status"] = $row["status"];
                exit;
            }
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
    <link rel="stylesheet" href="admin/assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">


</head>

<body>
    <?php if (isset($error)) : ?>
        <script>
            alert('username / password yang anda masukan salah!');
        </script>
    <?php endif; ?>

    <h4 class="text-center mt-5">Welcome!</h4>

    <form action="" method="POST" class="form-login">
        <ul style="list-style: none;" class="p-0">
            <li><input type="text" class="form-control" placeholder="username atau email" name="username" autofocus required></li><br>
            <li><input type="password" class="form-control" placeholder="password" name="password" required></li><br>
            <button name="login" class="btn btn-primary w-100" type="submit">Login</button>
        </ul>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>