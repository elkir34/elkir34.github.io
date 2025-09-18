<?php 
	session_start();
   
	if (isset($_POST['noCuenta']) and isset($_POST['Password'])) {
		$bd_host = "localhost";
		$bd_user = "buzosbyp_erp";
		$bd_pass = "Mientras12$";
		$bd_data = "buzosbyp_erp";
		$link    = mysqli_connect( $bd_host, $bd_user,  $bd_pass, $bd_data );

		if( !$link ){
			echo "Fallo en la conexion: " . mysqli_connect_error();
			exit();
		}
		
		mysqli_set_charset( $link, "utf8mb4");
		date_default_timezone_set('America/Los_Angeles');
		
        $noCuenta = mysqli_real_escape_string($link,$_POST['noCuenta']);
        $noCuenta = (int)$noCuenta;
        $Password = mysqli_real_escape_string($link,$_POST['Password']);
        $result   = mysqli_query($link, "SELECT * FROM empleados WHERE noCuenta=$noCuenta");

        if (!ctype_digit($noCuenta)) {    
            $showErrorModal = true; 
        }		
		
		if ($row = mysqli_fetch_array($result)) {
			if ($row["Password"] == md5($Password) ) {
				$idEmpleado             = $row['idEmpleado'];
				$Fecha                  = date("Y-m-d");
				$Hora                   = date("H:i:s");
				$_SESSION["idEmpleado"] = $idEmpleado;
				$_SESSION["noCuenta"]   = $row['noCuenta'];
				$_SESSION["Password"]   = $row['Password'];
				$_SESSION["Nombre"]     = $row['Nombre'];
				$_SESSION["Empleado"]   = $row['ApPaterno'] . " " . $row['ApMaterno'] . " " . $row['Nombre'];
				$_SESSION["Empleado1"]  = ucwords(mb_strtolower($row['Nombre'] . " " . $row['ApPaterno'] . " " . $row['ApMaterno'], 'UTF-8'));
				$_SESSION["EnLinea"]    = 1;
				$_SESSION["bd_host"]    = $bd_host;
				$_SESSION["bd_user"]    = $bd_user;
				$_SESSION["bd_pass"]    = $bd_pass;
				$_SESSION["bd_data"]    = $bd_data;
				$_SESSION["Fecha1"]     = date("d-m-Y");
				$_SESSION["Fecha2"]     = date("d-m-Y");

				header('Location: reportes/index_menu.php');
			}else{
               $showErrorModal = true; // Variable para mostrar el modal
            }
		}
		mysqli_free_result($result);
		mysqli_close($link);
	}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenido | ByP</title>
        <link rel="stylesheet" href="lib/css/bootstrap.min.css" rel="stylesheet" />    
        <style>
            body {min-height: 100vh;background: #f8f9fa;display: flex;align-items: center;justify-content: center;}
            .welcome-card {max-width: 460px;}
            .col-form-label {font-size: 0.95rem;}
        </style>
    </head>
    <body>
        <div class="card shadow rounded welcome-card text-center">
            <div class="card-header bg-primary text-white">
                Bienvenidos al Portal
            </div>
            <div class="card-body">
                <h4 class="mb-4">Buzos y Pescadores de la Baja California</h4>
                <p class="card-text">Accede a los reportes, consulta información y gestiona tus operaciones desde este portal.</p>
                <div class="container-fluid d-flex justify-content-center">
                    <div class="card shadow rounded">
                        <h5 class="card-header text-center">Inicio de sesión</h5>
                        <div class="card-body">
                            <form class="form-horizontal" action="index.php" method="post" id="loginForm" autocomplete="off">
                                <div class="row align-items-center mb-3">
                                    <label for="username" class="col-4 col-form-label mb-0 text-end">Cuenta:</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" id="noCuenta" name="noCuenta" placeholder="Numero de cuenta" autocomplete="off" required autofocus/>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <label for="password" class="col-4 col-form-label mb-0 text-end">Contraseña:</label>
                                    <div class="col-8">
                                        <input type="password" class="form-control" id="password" name="Password" placeholder="Clave de acceso" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <button type="submit" class="btn btn-success w-100">Ingresar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <a href="#" class="legal-link" data-title="Aviso de Privacidad" data-url="aviso_de_privacidad.php">Aviso de privacidad</a> |
                <a href="#" class="legal-link" data-title="Términos y Condiciones" data-url="terminos_y_condiciones.php">Términos y condiciones</a> |
                <a href="mailto:administracion@buzosypescadores.com.mx">Contacto</a> | 2025&copy; ByP
            </div>
        </div>
        <div class="text-center mt-3" id="siteseal">
            <script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=lvkgPkqbURdoTmg5in5BS9cL6NAJj5lnrOYygyUWaCWp1bBfJJJGsT773pkR"></script>
        </div>
        <!-- Modal genérico para mostrar contenido externo -->
        <div class="modal fade" id="modalLegal" tabindex="-1" aria-labelledby="modalLegalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLegalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="modalLegalBody">
                        <div class="text-center text-muted">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>        
        <script src="lib/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.legal-link').forEach(function(link) {
                link.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('data-url');
                var title = this.getAttribute('data-title');
                var modal = new bootstrap.Modal(document.getElementById('modalLegal'));
                document.getElementById('modalLegalLabel').textContent = title;
                document.getElementById('modalLegalBody').innerHTML = '<div class="text-center text-muted">Cargando...</div>';
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        // Extrae solo el contenido relevante del archivo externo
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        // Busca el contenedor principal (ajusta si cambias la clase)
                        var content = tempDiv.querySelector('.aviso-container, .terminos-container');
                        document.getElementById('modalLegalBody').innerHTML = content ? content.innerHTML : html;
                    })
                    .catch(() => {
                        document.getElementById('modalLegalBody').innerHTML = '<div class="text-danger">No se pudo cargar el contenido.</div>';
                    });
                modal.show();
                });
            });
            });
        </script>
        <?php if (isset($showErrorModal) && $showErrorModal): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modalHtml = `
                        <div class='modal fade' id='errorModal' tabindex='-1' aria-labelledby='errorModalLabel' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='errorModalLabel'>Error</h5>
                                    </div>
                                    <div class='modal-body'>
                                        Error el número de cuenta y/o la contraseña son incorrectas
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-primary' id='modalAcceptBtn'>Aceptar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                    document.getElementById('modalAcceptBtn').addEventListener('click', function() {
                        window.location.href = 'index.php';
                    });
                });
            </script>
        <?php endif; ?>        
    </body>
</html>
