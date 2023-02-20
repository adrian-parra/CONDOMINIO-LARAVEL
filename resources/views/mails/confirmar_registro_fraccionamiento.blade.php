<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $data['nombre'] }}, gracias por registrarte en LOAL</h2>
    <p>Por favor confirma tu correo electrónico para seguir con el proceso de registro.</p>
    <p>Para ello simplemente debes hacer click en el siguiente enlace:</p>

    <a href="{{$data['token']}}">
        Confirmar correo
    </a>
    <p style="color: red;">Este enlace es válido durante 30 minutos desde el momento en que hizo su registro por primera vez .</p>
<p>Si usted no se ha registrado, no necesita realizar ninguna acción.</p>
</body>
</html>