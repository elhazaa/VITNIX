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

$_exchangeAvailable = "";

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
                    <?php 
                        require_once "config.php";
                        $city="Aasdas";
                        $param_user_id = $_SESSION["user_id"];

                        $sql = "SELECT CASE WHEN COUNT(visit_id) >= 5 THEN 1 ELSE 0 END AS isVisible FROM visits WHERE user_id = $param_user_id AND visitStatus = 0";
                        $sql1 = "SELECT uniqueCode FROM exchanges WHERE user_id = $param_user_id AND isExchanged = 0";
                        
                        $result=mysqli_query($link,$sql) or trigger_error($db->error);
                        $result1=mysqli_query($link,$sql1) or trigger_error($db->error);
                        
                        $mostrar=mysqli_fetch_array($result)

                        ?> 
                            <div style="<?php echo $mostrar["isVisible"] == 1 ? "display:block;" : "display:none;" ?>" class="row">
                                <div class="col-12 text-center mb-3">
                                    <h1 class="h3 mb-0 text-gray-800">¡Felicidades!</h1>
                                    
                                </div>
                                <div class="col-12 text-center mb-3">
                                    <h4 class="h4 mb-0 text-gray-800">Acumulaste visitas</h1>
                                    
                                </div>
                                <div class="col-12 text-center mb-3">
                                    <a href="exchangeProductVisitor.php" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                                        <i class="fas fa-birthday-cake fa-sm text-white-50"></i> Canjear producto
                                    </a>  
                                </div>
                            </div>

                            <div style="<?php echo $mostrar["isVisible"] == 0 ? "display:block;" : "display:none;" ?>" class="row">
                                <div class="offset-lg-3 col-lg-6 col-md-6 mb-4">
                                    <h5 class="h5 mb-3 text-success text-center">Codigo de producto gratis acumulado:</h5>
                                    <?php 
                                        while($mostrar1=mysqli_fetch_array($result1))
                                        {
                                            ?> 
                                                <div class="card mb-4 py-3 border-bottom-success">
                                                    <div class="card-body text-center">
                                                        <?php echo $mostrar1 ['uniqueCode'] ?>
                                                    </div>
                                                </div>
                                            <?php 
                                        }
                                    ?> 
                                 </div>   
                                
                            </div>
                        <?php 
                    ?>  
                    

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Visitas</h1>
                        
                        <a href="#" data-toggle="modal" data-target="#visitModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-ticket-alt fa-sm text-white-50"></i> Registrar visita
                        </a>       
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                         
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">

                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Visitas registradas
                                            </div>

                                            <?php 
                                                require_once "config.php";

                                                $param_user_id = $_SESSION["user_id"];

                                                $sql = "SELECT COUNT(visit_id) AS visitsAmount FROM visits WHERE user_id = $param_user_id AND visitStatus = 0";

                                                $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                                
                                                $mostrar=mysqli_fetch_array($result)

                                                ?> 
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mostrar ['visitsAmount'] ?></div>
                                                <?php 
                                            ?>  

                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-walking fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Productos canjeados</div>
                                                <?php 
                                                    require_once "config.php";

                                                    $param_user_id = $_SESSION["user_id"];

                                                    $sql = "SELECT COUNT(redeem_id) AS reedemsAmount FROM redeems WHERE user_id = $param_user_id";

                                                    $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                                    
                                                    $mostrar=mysqli_fetch_array($result)

                                                    ?> 
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mostrar ['reedemsAmount'] ?></div>
                                                    <?php 
                                                ?>  
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-wine-bottle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <!-- Collapsable Card Example -->
                            <div class="card shadow mb-4">
                                <!-- Card Header - Accordion -->
                                <a href="#collapseCardVisits" class="d-block card-header py-3" data-toggle="collapse"
                                    role="button" aria-expanded="true" aria-controls="collapseCardVisits">
                                    <h6 class="m-0 font-weight-bold text-primary">Historial de visitas</h6>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse show" id="collapseCardVisits">
                                    <div class="card-body">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Codigo de ticket</th>
                                                    <th class="text-center">Visita</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    require_once "config.php";

                                                    $param_user_id = $_SESSION["user_id"];

                                                    $sql = "SELECT * FROM visits WHERE user_id = $param_user_id AND visitStatus = 0";

                                                    $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                                    
                                                    while($mostrar=mysqli_fetch_array($result))
                                                    {
                                                        ?> 
                                                            <tr>
                                                                <td class="text-center"><?php echo $mostrar ['ticketCode'] ?></td>
                                                                <td class="text-center"><?php echo $mostrar ['visitDate'] ?></td>
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

                        <div class="col-xl-6 col-md-6 mb-4">
                            <!-- Collapsable Card Example -->
                            <div class="card shadow mb-4">
                                <!-- Card Header - Accordion -->
                                <a href="#collapseCardproducts" class="d-block card-header py-3" data-toggle="collapse"
                                    role="button" aria-expanded="true" aria-controls="collapseCardproducts">
                                    <h6 class="m-0 font-weight-bold text-warning ">Historial de productos canjeados</h6>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse show" id="collapseCardproducts">
                                    <div class="card-body">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Producto</th>
                                                    <th class="text-center">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    require_once "config.php";

                                                    $param_user_id = $_SESSION["user_id"];

                                                    $sql = "SELECT p.description, re.amount FROM redeems re INNER JOIN products p ON re.product_id = p.product_id WHERE re.user_id = $param_user_id";

                                                    $result=mysqli_query($link,$sql) or trigger_error($db->error);
                                                    
                                                    while($mostrar=mysqli_fetch_array($result))
                                                    {
                                                        ?> 
                                                            <tr>
                                                                <td class="text-center"><?php echo $mostrar ['description'] ?></td>
                                                                <td class="text-center"><?php echo $mostrar ['amount'] ?></td>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="visitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php
                    require_once "config.php";

                    $code_exist_err = "";

                    if(isset($_POST['btnRegister']))
                    {
                       // Prepare a select statement
                        $sql = "SELECT visit_id FROM visits WHERE ticketCode = ?";
                        
                        if($stmt = mysqli_prepare($link, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "s", $param_code);
                            
                            // Set parameters
                            $param_code = trim($_POST["code"]);
                            
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                /* store result */
                                mysqli_stmt_store_result($stmt);
                                
                                if(mysqli_stmt_num_rows($stmt) == 1){
                                    $code_exist_err = "El codigo ya existe en el sistema.";
                                } else{
                                    $code = trim($_POST["code"]);
                                    $code_exist_err = "";
                                }
                            } else{
                                echo "Algo salio mal. Intentelo mas tarde.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        // Check input errors before inserting in database
                        if(empty($code_exist_err))
                        {
                            // Prepare an insert statement
                            $sql = "INSERT INTO visits (user_id, ticketCode, visitDate, visitStatus) VALUES (?, ?, ?, ?)";
                            
                            if($stmt = mysqli_prepare($link, $sql)){
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "ssss", $param_user_id, $param_ticketCode, $param_visitDate, $param_visitStatus);
                                
                                // Set parameters
                                $param_user_id = $_SESSION["user_id"];
                                $param_ticketCode = $code;
                                $param_visitDate = date("Y-m-d H:i:s");
                                $param_visitStatus = false;
                                
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
                        }

                        // Close connection
                        mysqli_close($link);
                    }
                ?>
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registro de visitas</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    <div class="form-group">
                        <label>Codigo</label>
                        <input type="text" name="code" class="form-control" required>
                        <span class="invalid-feedback"></span>
                    </div>
                    <?php 
                        if(!empty($login_err)){
                            echo '<div class="alert alert-danger">' . $code_exist_err . '</div>';
                        }        
                    ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input type="submit" type="submit" name="btnRegister" class="btn btn-primary" value="Registrar">
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
    <script src="vendor/chart.js/Chart.min.js"></script>

</body>

</html>