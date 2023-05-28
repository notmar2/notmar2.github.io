<?php
class Acceso {
    public function display_form($error){
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            
            $result = $this->validate($username, $password);
            
            if ($result) {
                header("Location: formulario.php");
                exit;
            } else {
                header("Location: index.php?error=1");
                exit;
            }
        }
        if ($error === 1) {
            echo '<p style="color: red;">Invalid username or password!</p>';
        }
        echo '
            <h2>Login Form</h2>
            <form action="' . $_SERVER['PHP_SELF'] . '" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                
                <input type="submit" value="Login">
            </form>
            <p>Don\'t have an account? <a href="register.php">Register</a></p>
        ';
    }
    
    public function validate($user, $pass) {
        $file = 'users.txt';
        $credentials = $user . ':' . $pass;
        $users = file($file, FILE_IGNORE_NEW_LINES);
        
        foreach ($users as $userData) {
            if ($userData === $credentials) {
                return true;
            }
        }
        return false;
    }
}
?> 