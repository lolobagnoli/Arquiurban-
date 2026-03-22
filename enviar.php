<?php
// =======================================================
// CONFIGURACIÓN: Reemplaza estos valores con tus datos
// =======================================================

// 1. Correo de destino: A dónde quieres que lleguen las consultas
$to_email = "tu_correo@arquiu.com"; // <-- REEMPLAZA ESTA LÍNEA

// 2. Asunto del correo que recibirás
$subject = "Nueva Consulta Web - ARQUIurban Landing Page";

// 3. Dirección de correo remitente (REQUERIDO POR HOSTINGS)
// Usa una cuenta de correo real creada en tu hosting (ej. info@tu-dominio.com)
$from_email = "info@tu-dominio.com"; // <-- REEMPLAZA ESTA LÍNEA

// URL de la Landing Page (necesario para la redirección)
$landing_page = "index.html"; 

// =======================================================
// PROCESAMIENTO DEL FORMULARIO
// =======================================================

// 1. Verificar que la solicitud sea POST y que los campos no estén vacíos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Función para limpiar y validar los datos
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Recoger y limpiar los datos del formulario
    $nombre = test_input($_POST['nombre']);
    $email = test_input($_POST['email']);
    $telefono = test_input($_POST['telefono']);
    $mensaje = test_input($_POST['mensaje']);

    // Comprobación de campos esenciales (anti-spam básico)
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        // Redirigir si faltan campos esenciales
        header("Location: $landing_page?error=missing_fields");
        exit;
    }

    // 2. Construir el cuerpo del mensaje que recibirás
    $email_body = "Se ha recibido una nueva consulta de ARQUIurban:\n\n";
    $email_body .= "Nombre: " . $nombre . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Teléfono: " . (empty($telefono) ? "No proporcionado" : $telefono) . "\n\n";
    $email_body .= "Mensaje:\n" . $mensaje;

    // 3. Establecer las cabeceras del correo (IMPORTANTE para que no sea spam)
    $headers = "From: " . $from_email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // 4. Intentar enviar el correo
    if (mail($to_email, $subject, $email_body, $headers)) {
        // ÉXITO: Redirigir a la página principal con el parámetro 'success=true'
        header("Location: $landing_page?success=true");
        exit;
    } else {
        // FALLO DEL SERVIDOR: Si el mail() falla, redirigir con error
        header("Location: $landing_page?error=mail_failed");
        exit;
    }
} else {
    // Si alguien intenta acceder a enviar.php directamente sin formulario
    header("Location: $landing_page");
    exit;
}
?>