<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$_user_id = "";
$email = $name = $lastname = $lastnameM = $password = $confirm_password = "";
$email_err = $name_err = $lastname_err = $lastnameM_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Ingrese un correo";
    } elseif(!preg_match('/^[^0-9][_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', trim($_POST["email"]))){
        $email_err = "Ingrese un correo valido.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "El correo ya existe en el sistema.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Algo salio mal. Intentelo mas tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Ingrese una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener minimo 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirme la contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }

    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Ingrese un nombre.";     
    }else{
        $name = trim($_POST["name"]);
    }

    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Ingrese un apellido.";     
    }else{
        $lastname = trim($_POST["lastname"]);
    }

    // Validate lastname mother
    if(empty(trim($_POST["lastnameM"]))){
        $lastnameM_err = "Ingrese un apellido.";     
    }else{
        $lastnameM = trim($_POST["lastnameM"]);
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($name_err) && empty($lastname_err) && empty($lastnameM_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, name, lastname, lastname_mother) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_email, $param_password, $param_name, $param_lastname, $param_lastnameM);
            
            // Set parameters
            $param_email = $email;
            $param_password = $password; // Creates a password hash
            $param_name = $name;
            $param_lastname = $lastname;
            $param_lastnameM= $lastnameM;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                //header("location: login.php");
            } else{
                echo "Algo salio mal. Intentelo mas tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Prepare a select statement
        $sql = "SELECT user_id, email, password, name, lastname FROM users WHERE email = ?";
        
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
                    mysqli_stmt_bind_result($stmt, $user_id, $email, $_password, $name, $lastname);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $_password){
                            // Store data in session variables
                            $_user_id= $user_id;
                        } 
                    }
                } 
            } else{
                echo "Algo salio mal. Intentelo mas tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Prepare an insert statement
        $sql = "INSERT INTO permissions (user_id, role_id) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_user_id, $param_role_id);
            
            // Set parameters
            $param_user_id = $_user_id;
            $param_role_id = 2;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #4e73df;">
    <div class="d-flex justify-content-center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1 style="margin-top: 40%;" class="text-white text-center">VITNIX</h1>
            <h2 style="margin-top: 20%;" class="text-white text-center">Registro</h2>
            <p class="text-white">Llene este formulario para crear una cuenta.</p>
            <div class="form-group">
                <label class="text-white">Correo electronico</label>
                <input type="email" autocomplete="off" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label class="text-white">Contraseña</label>
                <input type="password" autocomplete="off" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label class="text-white">Confirmar contraseña</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label class="text-white">Nombre</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>   
            <div class="form-group">
                <label class="text-white">Apellido paterno</label>
                <input type="text" name="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
            </div>   
            <div class="form-group">
                <label class="text-white">Apellido materno</label>
                <input type="text" name="lastnameM" class="form-control <?php echo (!empty($lastnameM_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastnameM; ?>">
                <span class="invalid-feedback"><?php echo $lastnameM_err; ?></span>
            </div>   
            <div class="form-group text-center">
                <input type="submit" class="btn btn-light w-50" value="Crear">
                <input type="reset" class="btn btn-secondary ml-2 w-48" value="Cancelar">
            </div>
            <p class="text-white">¿Ya tienes una cuenta? <a href="login.php" class="text-dark">Inicia sesion</a></p>
        </form>
    </div>    
</body>
</html>