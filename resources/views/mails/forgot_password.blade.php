<p>Hola {{$data['nombre']}}</p>
<p>Usted solicitó un restablecimiento de contraseña para su cuenta en LOAL.</p>
<p>Para confirmar esta petición, y establecer una nueva contraseña para su cuenta, por favor vaya a la siguiente dirección de Internet:</p>
<a href="{{$data['token']}}">{{$data['token']}}</a>
<p style="color: red;">Este enlace es válido durante 30 minutos desde el momento en que hizo la solicitud por primera vez .</p>
<p>Si usted no ha solicitado este restablecimiento de contraseña, no necesita realizar ninguna acción.</p>
