<?php 
//var_dump( $_POST );
session_start();

if( !isset( $_SESSION['idPerfil'] ) AND !isset( $_SESSION['idNivel'] ) ) {

    if ( isset( $_POST['usuario'] ) AND isset( $_POST[ 'clave' ] ) )
    {
        
        include 'class/conexion.class.php';
        include 'class/sesion.class.php';
        include 'class/usuario.class.php';
        include 'class/validar.class.php';

        $username = $conexion->real_escape_string( $_POST[ 'usuario' ] );
        $clave    = $conexion->real_escape_string( $_POST[ 'clave' ] );

        $usuario = new Usuario();
        
        var_dump( $usuario->login( $username, $clave ) );

        //header("location: ./");
    }

?>
<!DOCTYPE html>
<html lang="es-GT" ng-app="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurante Churchill</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-md-offset-4">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="text-center">
                            <img src="img/Logo_Churchil.png">
                        </div>
                    </div>
                    <div class="panel-body">
                        <h3 class="text-center">ACCESO</h3>
                        <form autocomplete="off" action="login.php" method="POST">
                            <div class="form-group input-group has-success has-feedback">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 

                                <input class="form-control" type="text" name="usuario" ng-model="user" maxlength="12" placeholder="usuario" id="inputSuccess3" aria-describedby="inputSuccess3Status"/> 

                                <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" ></span>
                            </div>

                            <div class="form-group input-group has-success has-feedback">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input class="form-control" type="password" maxlength="30" name="clave" ng-model="pass" ng-disabled="user.length < 5"  placeholder="contraseña"/>     
                                <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-warning btn-block" ng-disabled="user.length < 6">
                                    <b>iniciar sesion</b>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    <script src="js/libs/jquery-3.2.1.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/angular.min.js"></script>

</body>
</html>

<?php
}else{
    header('Location: ./');
}
?>