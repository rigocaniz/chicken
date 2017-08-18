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
        $data = $usuario->login( $username, $clave );
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

    <style type="text/css" media="screen">

        body{
            background: url('img/ejemplo.jpg') no-repeat fixed center center;
            background-size: cover;
        }

    

    .login-block {
        width: 350px;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        border-top: 6px solid #ff5722;
        border-bottom: 3px solid #ff5722;
        margin: 35px auto;
    }

    .login-block h3 {
        text-align: center;
        font-size: 20px;
        text-transform: uppercase;
        margin-top: 0;
        margin-bottom: 15px;
        color: #ff3d00;
        text-shadow: 2px 2px 2px #cccccc;
        font-weight: bold;
    }

    .container{
        background-color: rgba(255, 152, 0, 0.29);
        width: 100%;
        height: 100% !important;
        position: fixed;
    }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="login-block">
            <img class="text-center" src="img/Logo_Churchil.png">
            <div class="text-center">
                <h3>INGRESAR</h3>
            </div>
            <p>
                <form action="login.php" method="POST" novalidate autocomplete="off">
                    <div class="form-group input-group" ng-class="{'has-success has-feedback': user && user.length >=8}">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
                        <input class="form-control" type="text" name="usuario" ng-model="user" maxlength="12" placeholder="Usuario" required />
                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" ng-show="user && user.length >=8"></span>
                    </div>
                    <div class="form-group input-group" ng-class="{'has-success has-feedback': pass && pass.length >=6}" ">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                        </span>
                        <input class="form-control" type="password" maxlength="30" name="clave" ng-model="pass" ng-disabled="user.length < 5" placeholder="Contraseña" required />     
                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true" ng-show="pass && pass.length >=6"></span>
                    </div>
                    <?php
                        if( isset( $data[ 'respuesta' ] ) AND $data[ 'respuesta' ] == 'danger'  ):
                    ?>
                    <div class="alert alert-danger" role="alert"><?= $data[ 'mensaje' ]; ?></div>
                    <?php
                        endif;
                    ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block" ng-disabled="user.length < 6">
                            <b>ACCEDER</b>
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