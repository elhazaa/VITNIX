<?php
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Clientes</h1>
                        
                        <a href="#" data-toggle="modal" data-target="#exchangeModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-exchange-alt fa-sm text-white-50"></i> Canjear
                        </a>       
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Correo electronico</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Rol</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody id="myTable">
                                        <?php 
                                            require_once "config.php";

                                            $sql = "SELECT u.user_id, u.email, u.password, u.name, u.lastname, u.lastname_mother, p.role_id AS 'role_id', r.description AS 'role_description' FROM users u INNER JOIN permissions p ON u.user_id = p.user_id INNER JOIN roles r ON p.role_id = r.role_id WHERE p.role_id = 2";
                                            
                                            $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                            
                                            while($mostrar=mysqli_fetch_array($result))
                                            {
                                                ?> 
                                                    <tr>
                                                        <td class="text-center"><?php echo $mostrar ['email'] ?></td>
                                                        <td class="text-center"><?php echo $mostrar ['name'] ?> <?php echo $mostrar ['lastname'] ?> <?php echo $mostrar ['lastname_mother'] ?></td>
                                                        <td class="text-center"><?php echo $mostrar ['role_description'] ?></td>
                                                        <td class="text-center">
                                                            
                                                            <a href="detailVisita.php?id=<?php echo $mostrar ['user_id'] ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                                                <i class="fas fa-walking fa-sm text-white-50"></i> Visitas
                                                            </a> 
                                                            <a href="detailProducto.php?id=<?php echo $mostrar ['user_id'] ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                                                <i class="fas fa-user-alt fa-sm text-white-50"></i> Canjeos
                                                            </a> 
                                                        </td>
                                                    </tr>
                                                <?php 
                                            } 

                                        ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

    <!-- Canjear Modal-->
    <div class="modal fade" id="exchangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php
                    if(isset($_POST['btnExchangeProduct']))
                    {
                        $param_uniqueCode=$_POST['uniqueCode'];
                        // Prepare a select statement
                        $sql = "SELECT exchange_id, user_id, product_id, uniqueCode, isExchanged FROM exchanges WHERE uniqueCode = '$param_uniqueCode'";
                                
                        if($stmt = mysqli_prepare($link, $sql)){

                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                // Store result
                                mysqli_stmt_store_result($stmt);
                                
                                // Check if username exists, if yes then verify password
                                if(mysqli_stmt_num_rows($stmt) == 1){                    
                                    // Bind result variables
                                    mysqli_stmt_bind_result($stmt, $ex_exchange_id, $ex_user_id, $ex_product_id, $ex_uniqueCode, $ex_isExchanged);
                                    if(mysqli_stmt_fetch($stmt)){
                                        if($ex_isExchanged == true){
                                            echo "<meta http-equiv='refresh' content='0'>"; 
                                        } 
                                    }
                                } 
                            } else{
                                echo "Algo salio mal. Intentelo mas tarde.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        $sql = "UPDATE exchanges SET isExchanged = 1 WHERE exchange_id = $ex_exchange_id";
                        
                        if($stmt = mysqli_prepare($link, $sql)){
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                // Redirect to login page
                                
                            } else{
                                echo "Algo salio mal. Intentelo mas tarde.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        $sql = "SELECT product_id, description, price, amount, isActive FROM products WHERE product_id =  $ex_product_id";
                                        
                        if($stmt = mysqli_prepare($link, $sql)){

                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                // Store result
                                mysqli_stmt_store_result($stmt);
                                
                                // Check if username exists, if yes then verify password
                                if(mysqli_stmt_num_rows($stmt) == 1){                    
                                    // Bind result variables
                                    mysqli_stmt_bind_result($stmt, $prod_product_id, $prod_description, $prod_price, $prod_amount, $prod_sActive);
                                    if(mysqli_stmt_fetch($stmt)){
                                    }
                                } 
                            } else{
                                echo "Algo salio mal. Intentelo mas tarde.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        $amount_ = $prod_amount - 1;
                        $sql = "UPDATE products SET amount = $amount_ WHERE product_id = $prod_product_id";
                        
                        if($stmt = mysqli_prepare($link, $sql)){
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                            } else{
                                echo "Algo salio mal. Intentelo mas tarde.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        // Prepare an insert statement
                        $sql = "INSERT INTO redeems (user_id, product_id, amount, redeemedBy) VALUES (?, ?, ?, ?)";
                        
                        if($stmt = mysqli_prepare($link, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "ssss", $red_user_id, $red_product_id, $red_amount, $red_redeemedBy);
                            
                            // Set parameters
                            $red_user_id = $ex_user_id;
                            $red_product_id = $ex_product_id;
                            $red_amount= 1;
                            $red_redeemedBy= $_SESSION["name"] . " " . $_SESSION["lastname"];

                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                // Redirect to login page
                                echo "<meta http-equiv='refresh' content='0'>"; 
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
                        <h5 class="modal-title" id="exampleModalLabel">Canjeo de producto</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">
                        <label>Codigo</label>
                        <input type="text" name="uniqueCode" class="form-control" required>
                        <span class="invalid-feedback"></span>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input type="submit" type="submit" name="btnExchangeProduct" class="btn btn-primary" value="Registrar">
                    </div>
                </form>
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