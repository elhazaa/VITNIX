<?php

require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    if($_SESSION["role_id"] == 1)
    {
        header("location: administrator.php");
    }else{
        header("location: visitor.php");
    }
    
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="administrator.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-wine-glass-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Vitnix</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <!-- Heading -->
                <div class="sidebar-heading" style="margin-top: 10px;">
                    Catalogos
                </div>

                <a class="nav-link" href="administrator.php">
                    <i class="fas fa-user-alt"></i>
                    <span>Clientes</span>
                </a>
                
                <a class="nav-link"  style="margin-top: -20px;" href="products.php">
                    <i class="fas fa-wine-bottle"></i>
                    <span>Productos</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><b>ADMINISTRADOR - <?php echo htmlspecialchars($_SESSION["name"] . " " . $_SESSION["lastname"] . " " . $_SESSION["lastnameM"]); ?></b></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="modal-content">
                        <?php
                            $oldAmount =0;
                            $newAmount = 0;
                            if(isset($_POST['btnEntrance']))
                            {
                                $currentAmount=$_POST['amount'];
                                $param_product_id = $_GET["id"];
                                // Prepare a select statement
                                $sql = "SELECT product_id, description, price, amount, isActive FROM products WHERE product_id =  $param_product_id";
                                        
                                if($stmt = mysqli_prepare($link, $sql)){

                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // Store result
                                        mysqli_stmt_store_result($stmt);
                                        
                                        // Check if username exists, if yes then verify password
                                        if(mysqli_stmt_num_rows($stmt) == 1){                    
                                            // Bind result variables
                                            mysqli_stmt_bind_result($stmt, $product_id, $description, $price, $amount, $isActive);
                                            if(mysqli_stmt_fetch($stmt)){
                                                if($param_product_id == $product_id){
                                                    // Store data in session variables
                                                    $oldAmount= $amount;

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
                                $newAmount = $oldAmount + $currentAmount;
                                $param_product_id = $_GET["id"];
                                $sql = "UPDATE products SET amount = $newAmount WHERE product_id = $param_product_id";
                                
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // Redirect to login page
                                        //header("location: products.php");

                                        $previous = "javascript:history.go(-1)";
                                        if(isset($_SERVER['HTTP_REFERER'])) {
                                            $previous = $_SERVER['HTTP_REFERER'];
                                        }
                                    } else{
                                        echo "Algo salio mal. Intentelo mas tarde.";
                                    }

                                    // Close statement
                                    mysqli_stmt_close($stmt);
                                }
                                // Close connection
                                mysqli_close($link);
                            }
                        ?>
                        <form action="" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar entrada de producto</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" name="amount" class="form-control" required>
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <input type="submit" type="submit" name="btnEntrance" class="btn btn-primary" value="Actualizar">
                            </div>
                        </form>
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
                        <a href="products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-step-backward fa-sm text-white-50"></i> Regresar
                        </a>       
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Estas seguro?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "aceptar" para cerrar sesion.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="logout.php">Aceptar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>