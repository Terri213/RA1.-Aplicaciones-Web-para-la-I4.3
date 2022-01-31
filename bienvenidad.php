<?php 

session_start();
if(!isset($_SESSION['usuario'])){
    echo '
    <script>
    alert("por favor de iniciar sesíon");
    window.location= "index.php";
    </script>
    ';
    session_destroy();
    die();
}
session_destroy();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavanderia</title>
</head>
<body>
    <h1>Productos</h1>
</body>
</html>
