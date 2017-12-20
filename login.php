<?php
session_start();

$username  = "";
$password  = "";
$mensaje   = "";
$response  = NULL;
$error     = FALSE;
$respuesta = (object)array();
$cambioPass = '
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></div>
            <input type="password" class="form-control" name="pass1" placeholder="Ingrese nueva contraseña" autofocus>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></div>
            <input type="password" class="form-control" name="pass2" placeholder="Confirme nueva Contraseña">
        </div>
    </div>';

if( !isset( $_SESSION[ 'idPerfil' ] ) AND !isset( $_SESSION[ 'username' ] )  ) {

    if( isset( $_POST[ 'username' ]) && isset( $_POST[ 'password' ] ) ) { 
        
        include 'class/conexion.class.php';
        include 'class/sesion.class.php';
        include 'class/modulo.class.php';
        include 'class/usuario.class.php';
        include 'class/validar.class.php';
        include 'class/funciones.php';

        $conexion = new Conexion();
        $usuario  = new Usuario();

        $username  = $_POST['username'];
        $password  = $_POST['password'];

        $respuesta = $usuario->login( $username, $password );

        if( $respuesta['respuesta'] == 'danger' ){           // ERROR NO EXISTE USUARIO o CONTRASEÑA
            $error    = true;
            $response = $respuesta[ 'respuesta' ];
            $mensaje  = "<div class='alert alert-danger'>{$respuesta[ 'mensaje' ]}</div>";
        }
        // AGREGAR INPUTS PARA CAMBIO DE CONTRASEÑA SI ES PRIMER INICIO
        elseif( $respuesta[ 'respuesta' ] == 'warning' || isset( $_POST[ 'pass1' ] ) ){
            // VALIDAR CONTRASEÑAS NUEVOS ESTEN DEFINIDOS
            if( isset( $_POST[ 'pass1' ] ) && isset( $_POST[ 'pass2' ] ) ){
                // OBTENER NUEVOS PASSWORDS
                $pass1 = $_POST[ 'pass1' ];
                $pass2 = $_POST[ 'pass2' ];

                $respuesta = $usuario->cambiarClaveUsuario( $username, $password, $pass1, $pass2 );
                
                if( $respuesta[ 'respuesta' ] == 'success' ): // SI CAMBIÓ LA CONTRASEÑA
                    $password = "";
                    $response = 1;
                    $error    = false;
                    $mensaje  = "<div class='alert alert-info'>{$respuesta['mensaje']}</div>";;
                    unset( $_POST );
                else:
                    $response = 2;
                    $error   = true;
                    $mensaje = "<div class='alert alert-danger'>{$respuesta['mensaje']}</div>";
                endif;                
            }
            else{
                $error    = true;
                $response = 2;
                $mensaje  = "<div class='alert alert-warning'>{$respuesta['mensaje']}</div>";
            }
        }
        $conexion->close();
    }

    $disabled = $response == 2 ? 'readonly' : '';
?>
<!DOCTYPE html>
<html>
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
            <div>
                <img class="img-rounded" src="img/logo_churchil.png">
                <h3>INGRESAR</h3>
            </div>
            <p>
                <form action="<?php echo htmlspecialchars( $_SERVER[ 'PHP_SELF' ]); ?>" method="POST" novalidate autocomplete="off">
                    <div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
                        <input class="form-control" type="text" name="username" value="<?= $username; ?>" maxlength="12" placeholder="Usuario" <?= $disabled; ?> />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                        </span>
                        <input class="form-control" type="password" maxlength="25" name="password" value="<?= $password; ?>" placeholder="Contraseña" <?= $disabled; ?> />
                    </div>
                    <?php 
                        if( $response == 2 && $error ):
                            echo $cambioPass;
                        endif;
                    ?>
                    <div class="form-group">
                        <?php
                            if( ( $response == 1 && !$error ) OR $error ):
                                echo $mensaje;
                            endif;
                        ?>
                    </div>
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
</body>
</html>
<?php
}
else
    header( 'Location: ./' );
?>