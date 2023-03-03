<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
</head>

<body>
    <h2>Hola {{ $data->nombre }}</h2>
    <p>Existe un nuevo Egreso que necesita tu aprobación.</p>
    <p>Para dirigirte a la pagina haz click en el siguiente enlace:</p>

    <a href="{{ $data->pagina }}">
        Ir a la pagina
    </a>
</body>

</html>