<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
</head>

<body>
    <h2>Hola {{ $data->nombre }}</h2>
    <p style="color:red;">Su pago a sido rechazado.</p>
    <h4>Razon:</h4>
    <p>{{ $data->razonRechazo }}</p>
    <p>Para dirigirte a la pagina haz click en el siguiente enlace:</p>

    <a href="{{ $data->pagina }}">
        Ir a la pagina
    </a>
</body>

</html>