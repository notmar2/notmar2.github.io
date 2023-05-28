<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcial2</title>
</head>
<body>

    <?php
    require 'acceso.php';
    $acceso = new Acceso();
    if(isset($_GET["error"]) && $_GET["error"] === "1"){
        $acceso->display_form(1);
    }else{
        $acceso->display_form(0);
    }
    ?>
    
</body>
</html>