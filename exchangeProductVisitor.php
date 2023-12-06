<?php
// Initialize the session
session_start();
 
require_once "config.php";

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

$_exchangeAvailable = "";
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="visitor.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-wine-glass-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Vitnix</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="visitor.php">
                    <i class="fas fa-walking"></i>
                    <span>Visitas</span></a>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><b>VISITANTE - <?php echo htmlspecialchars($_SESSION["name"] . " " . $_SESSION["lastname"] . " " . $_SESSION["lastnameM"]); ?></b></span>
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
                            if(isset($_POST['btnExchange']))
                            {
                                // Prepare an insert statement
                                $sql = "INSERT INTO exchanges (user_id, product_id, uniqueCode, isExchanged) VALUES (?, ?, ?, ?)";
                                
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "ssss", $param_user_id, $param_product_id, $param_uniqueCode, $param_isExchanged);
                                    
                                    // Set parameters
                                    $param_user_id = $_SESSION["user_id"];
                                    $param_product_id = $_POST["productid"];
                                    $param_uniqueCode = gen_uuid();
                                    $param_isExchanged = false;
                                    
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                    } else{
                                        echo "Algo salio mal. Intentelo mas tarde.";
                                    }
    
                                    // Close statement
                                    mysqli_stmt_close($stmt);
                                }

                                // Prepare an insert statement
                                $sql = "UPDATE visits SET visitStatus = 1 WHERE visitStatus = 0 AND user_id = $param_user_id ORDER BY visit_id LIMIT 5";
                                
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // Redirect to login page
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
                                
                            }   
                        ?>
                        <form action="" method="post">

                            <div class="modal-header">
                                <h5 class="modal-title">Canjear producto</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <select class="form-control" name="productid" required>
                                        <option value="0">Seleccione un producto:</option>
                                            <?php 
                                                $param_user_id = $_SESSION["user_id"];

                                                $sql = "SELECT * FROM products WHERE amount >= 1";

                                                $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                                
                                                while($mostrar=mysqli_fetch_array($result))
                                                {
                                                    ?> 
                                                        <option value="<?php echo $mostrar ['product_id'] ?>"> <?php echo $mostrar ['description'] ?> </option>
                                                    <?php 
                                                }
                                            ?>  
                                    </select>
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <input type="submit" type="submit" name="btnExchange" class="btn btn-primary" value="Canjear">
                            </div>
                        </form>
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
                            <a href="visitor.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
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
    <script src="vendor/chart.js/Chart.min.js"></script>

</body>

</html>