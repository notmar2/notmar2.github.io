<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <?php
        class Register{
            public function display_form(){
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    
                    $result = $this->register($username, $password);
                    
                    if ($result) {
                        header("Location: index.php");
                        exit;
                    } else {
                        echo '<p style="color: red;">Error registering the user.</p>';
                    }
                }
                echo '
                <h2>Register Form</h2>
                <form action="' . $_SERVER['PHP_SELF'] . '" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br><br>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    
                    <input type="submit" value="Register">
                </form>
            ';
            }

            public function register($username, $password) {
                $data = $username . ':' . $password . "\n";
                $file = 'users.txt';

                $result = file_put_contents($file, $data, FILE_APPEND);
                
                return $result !== false;
            }
        }
    ?>
    <?php
    $registerForm = new Register();
    $registerForm->display_form();
    ?>

</body>
</html>