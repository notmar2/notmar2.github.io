<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>
<body>
    <form id="form" action="result.php" method="POST">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="gender">Sexo:</label>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Hombre</label>
        <input type="radio" id="female" name="gender" value="female" required>
        <label for="female">Mujer</label>
        <input type="radio" id="other" name="gender" value="other" required>
        <label for="other">Otro</label><br><br>

        <label for="age">Edad:</label>
        <input type="number" id="age" name="age" required><br><br>

        <label for="csvData">CSV:</label>
        <textarea id="csvData" name="csvData" required></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
    <script>
    document.getElementById("form").addEventListener("submit", function() {
        window.location.href = "result.php";
    });
    </script>
</body>
</html>