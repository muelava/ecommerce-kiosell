<?php

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index");
    exit;
}


$conn = mysqli_connect("localhost", "root", "", "login");
$result = mysqli_query($conn, "SELECT *FROM user");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<style>
    div {
        width: 50%;
        margin: auto;
    }

    h1 {
        display: inline;
    }


    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
        margin: auto;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>

<body>


    <div>
        <h1>Hello</h1>
        <a href="logout">Logout</a>
        <table width="100%">
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
            <?php foreach ($result as $show) : ?>
                <tr>
                    <td><?= $show["username"]; ?></td>
                    <td><?= $show["password"]; ?></td>
                    <td>
                        <a href="delete?id_user=<?= $show['id_user'] ?>">delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>