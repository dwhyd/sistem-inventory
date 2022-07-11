<?php
session_start();
unset($_SESSION['nama']);
unset($_SESSION['email']);
unset($_SESSION['role']);

session_unset();
session_destroy();
header("Location: ../uas/login.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>

<body>
    <div>redirecting to index.php</div>

</body>

</html>