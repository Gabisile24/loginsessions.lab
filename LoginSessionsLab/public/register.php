<?php 
require_once ('config.php');

// Redirect if already logged in
if(isset($_SESSION['Username'])) {
    header("location: index.php");
    exit;
}

if(isset($_POST['Register'])) {
    // Server-side validation
    $username = trim($_POST['Username']);
    $password = trim($_POST['Password']);
    $confirm_password = trim($_POST['Confirm_Password']);
    
    if(empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields";
    } elseif($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check if username exists
        try {
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if($stmt->rowCount() > 0) {
                $error = "Username already exists";
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashed_password]);
                
                $_SESSION['user_id'] = $db->lastInsertId();
                $_SESSION['Username'] = $username;
                $_SESSION['Active'] = true;
                header("location: index.php");
                exit;
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/signin.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <title>Register</title>
</head>

<body>
<div class="container">
    <form action="" method="post" name="Register_Form" class="form-signin" onsubmit="return validateRegisterForm()">
        <h2 class="form-signin-heading">Register</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <label for="inputUsername">Username</label>
        <input name="Username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
        
        <label for="inputPassword">Password</label>
        <input name="Password" type="password" id="inputPassword" class="form-control" placeholder="Password (min 6 characters)" required>
        
        <label for="inputConfirmPassword">Confirm Password</label>
        <input name="Confirm_Password" type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm Password" required>
        
        <button name="Register" value="Register" class="button" type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<script>
// Client-side validation
function validateRegisterForm() {
    var username = document.getElementById('inputUsername').value.trim();
    var password = document.getElementById('inputPassword').value.trim();
    var confirm_password = document.getElementById('inputConfirmPassword').value.trim();
    
    if(username === "" || password === "" || confirm_password === "") {
        alert("Please fill in all fields");
        return false;
    }
    
    if(password !== confirm_password) {
        alert("Passwords do not match");
        return false;
    }
    
    if(password.length < 6) {
        alert("Password must be at least 6 characters");
        return false;
    }
    
    return true;
}
</script>
</body>
</html>