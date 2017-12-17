<?php 
//var_dump( $_POST );
session_start();

if( !isset( $_SESSION['usuario'] ) AND !isset( $_SESSION['idPerfil'] ) ) {

    if ( isset( $_POST['usuario'] ) AND isset( $_POST[ 'clave' ] ) )
    {
        
        include 'class/conexion.class.php';
        include 'class/sesion.class.php';
        include 'class/modulo.class.php';
        include 'class/usuario.class.php';
        include 'class/validar.class.php';
        include 'class/funciones.php';

        $username = $conexion->real_escape_string( $_POST[ 'usuario' ] );
        $clave    = $conexion->real_escape_string( $_POST[ 'clave' ] );
        $usuario = new Usuario();
        $data = $usuario->login( $username, $clave, 'NULL' );
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

    <style type="text/css">
        body{
            background: url('img/ejemplo.jpg') no-repeat fixed center center;
            background-size: cover;
        }
    </style>
</head>
<body>
    <div class="back-login">
        <div class="login-block">
            <div class="">
                <img class="img-rounded" src="img/logo_churchil.png">
                <h3>INGRESAR</h3>
            </div>
            <p>
                <form action="login.php" method="POST" novalidate autocomplete="off">
                    <div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
                        <input class="form-control" type="text" name="usuario" ng-model="user" maxlength="12" placeholder="Usuario" required autofocus />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                        </span>
                        <input class="form-control" type="password" maxlength="30" name="clave" ng-model="pass" placeholder="Contraseña" required />
                    </div>
                    <?php
                        if( isset( $data[ 'respuesta' ] ) AND $data[ 'respuesta' ] == 'danger'  ):
                    ?>
                    <div class="alert alert-danger" role="alert"><?= $data[ 'mensaje' ]; ?></div>
                    <?php
                        endif;
                    ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block">
                            <b>ACCEDER</b>
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </form>
            </p>
        </div>
    </div>

    <script src="js/libs/jquery-3.2.1.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/angular.min.js"></script>

</body>
</html>

<?php
}
else{
    header('Location: ./');
}
?>