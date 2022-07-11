<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="info.css"> -->
    <title>Info</title>
</head>

<body id="container">
    <?php
    echo "<p> $info </p>";

    if (!$info === '') {
        echo '';
    }
    ?>
    <button id="close">x</button>
    <script>
        let container = document.getElementById('container')
        let message = document.getElementById('message')
        let close = document.getElementById("close")
        close.addEventListener("click", function() {
            message.style.display = 'none'

        })
    </script>
</body>

</html>