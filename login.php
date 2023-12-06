<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION["role_id"] == 1)
    {
        header("location: administrator.php");
    }else{
        header("location: visitor.php");
    }
    
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Ingrese un correo.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Ingrese una contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT u.user_id, u.email, u.password, u.name, u.lastname, u.lastname_mother, p.role_id AS 'role_id' FROM users u INNER JOIN permissions p ON u.user_id = p.user_id WHERE u.email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $user_id, $email, $_password, $name, $lastname, $lastnameM, $role_id);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $_password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["email"] = $email;                            
                            $_SESSION["name"] = $name; 
                            $_SESSION["lastname"] = $lastname;
                            $_SESSION["lastnameM"] = $lastnameM;
                            $_SESSION["role_id"] = $role_id;

                            // Redirect user to welcome page
                            if($role_id == 1)
                            {
                                header("location: administrator.php");
                            }else{
                                header("location: visitor.php");
                            }
                            
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Correo o contraseña invalido.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Correo o contraseña invalido.";
                }
            } else{
                echo "Algo salio mal. Intentelo mas tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #4e73df;">
    <div class="d-flex align-items-center justify-content-center">
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1 style="margin-top: 40%;" class="text-white text-center">VITNIX</h1>
            <h2 style="margin-top: 20%;" class="text-white text-center">Inicio de sesion</h2>
            <p class="text-white text-center">Ingrese sus credenciales para iniciar sesion.</p>
            <div class="form-group">
                <label class="text-white">Correo electronico</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label class="text-white">Contraseña</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
            ?>
            <div class="form-group">
                <input type="submit" class="btn btn-light btn-lg d-block w-100 fw-500 mb-3" value="Iniciar sesion">
            </div>
            <p class="text-white text-center">¿Eres cliente y no tienes cuenta?  <a href="register.php" class="text-dark">Registrate</a></p>
        </form>
    </div>
</body>
</html>
 