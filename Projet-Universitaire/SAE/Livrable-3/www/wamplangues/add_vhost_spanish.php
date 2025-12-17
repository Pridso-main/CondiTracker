<?php
//3.1.1 - NotwwwDir
//3.1.3 - VirtualHostPortNone
//3.1.4 - txtTLDdev
//3.1.9 - VirtualHostName modified - Accept diacritical characters (IDN)
//3.2.6 - HoweverWamp
//3.2.8 - phpNotExists - VirtualHostPhpFCGI - modifyForm - modifyVhost - modAliasForm
//      - modifyAlias - StartAlias - ModifiedAlias - NoModifyAlias - HoweverAlias
//  modified: VirtualHostPort (%s replaced by below ) - Start - VirtualCreated - However - HoweverWamp
//  array $langues_help added.
//3.3.0 - Modification of lines FcgidInitialEnv
//3.3.2 - Suppress $langues[''VirtualSubMenuOn']

$langues = array(
	'langue' => 'Español',
	'locale' => 'español',
	'addVirtual' => 'Agregar un servidor virtual',
	'backHome' => 'Volver a la página de inicio',
	'VirtualSubMenuOn' => 'El elemento del submenú <code>Servidor virtual</code> debe establecerse en (Activo) en la <code>Configuración de Wamp</code> haciendo clic derecho en el menú, luego vuelva a cargar esta página',
	'UncommentInclude' => 'Descomente <small>(Suprimir #)</small> la línea <code>#Include conf/extra/httpd-vhosts.conf</code><br>en el archivo %s',
	'FileNotExists' => 'El archivo <code>%s</code> no existe',
	'txtTLDdev' => 'El nombre del servidor %s usa el TLD %s que está monopolizado por los navegadores web. Use otro TLD (por ejemplo, podrá utilizar .test)',
	'FileNotWritable' => 'El archivo <code>%s</code> no se pudo escribir',
	'DirNotExists' => '<code>%s</code> no existe o no es un directorio',
	'NotwwwDir' => 'La carpeta <code>%s</code> está reservada para "localhost". Utilice otra carpeta',
	'NotCleaned' => 'El archivo <code>%s</code> no se ha limpiado<br>Quedan ejemplos de servidor virtual como: dummy-host.example.com',
	'NoVirtualHost' => 'No se ha definido un servidor virtual en <code>%s</code><br>Debe tener al menos el servidor virtual para localhost',
	'NoFirst' => 'El primer servidor virtual debe ser <code>localhost</code> en el archivo <code>%s</code>',
	'ServerNameInvalid' => 'El nombre del servidor <code>%s</code> no es válido',
	'LocalIpInvalid' => 'La IP local <code>%s</code> no es válida',
	'VirtualHostName' => 'Nombre del <code>hospedador virtual</code> sin espacio - y sin guión bajo(_) ',
	'VirtualHostFolder' => 'Complete la <code>ruta</code> a la <code>carpeta</code> del proyecto del servidor virtual <i>Ejemplos: C:/wamp/www/projeto/ o E:/www/sitio1/</i> ',
	'VirtualHostIP' => '<code class="option">Si</code> desea utilizar un servidor virtual por IP: <code class="option">IP local</code> 127.x.y.z ',
	'VirtualHostPort' => '<code class="option">Si</code> desea utilizar un "Puerto de atención" que no sea el predeterminado <code class="option">Puertos aceptados</code> abajo ',
	'VirtualHostPhpFCGI' => '<code class="option">Si</code> desea utilizar PHP en modo FCGI <code class="option">Versiones aceptadas</code> abajo ',
	'VirtualHostPortNone' => 'Si desea utilizar un "Puerto de atención" que no sea el predeterminado, debe agregar un puerto de atención a Apache haciendo clic derecho el el botón en Herramientas ',
	'VirtualAlreadyExist' => 'El nombre del servidor <code>%s</code> ya existe',
	'VirtualIpAlreadyUsed' => 'La IP local <code>%s</code> ya existe',
	'VirtualPortNotExist' => 'El puerto <code>%s</code> no es un "Puerto de atención" de Apache',
	'VirtualPortExist' => 'El puerto <code>%s</code> es el "Puerto de atención" predeterminado de Apache y no debe ser mencionado',
	'VirtualHostExists' => 'El servidor virtual ya ha sido definido:',
	'Start' => 'Iniciar la creación o modificación del servidor virtual (Esto puede demorar un tiempo...)',
	'StartAlias' => 'Iniciar la modificación del alias',
	'GreenErrors' => 'Los errores enmarcados en verde se pueden corregir automáticamente',
	'Correct' => 'Iniciar la corrección automática de errores sobre le margen del panel verde',
	'NoModify' => 'El archivo <code>httpd-vhosts.conf</code> o <code>hosts</code> no han podido ser modificados',
	'NoModifyAlias' => 'El alias no podo ser modificado.',
	'VirtualCreated' => 'Los archivos han sido modificados. El servidor virtual <code>%s</code> ha sido creado o modificado',
	'ModifiedAlias' => 'El alias <code>%s</code> ha sido modificado',
	'CommandMessage' => 'Mensajes de la consola para actualizar DNS:',
	'However' => 'Porás agregar o modificar otro servidor virtual validando "Agregar un servidor virtual".<br>Sin embargo, para que wampmanager (Apache) tenga en cuenta este servidor locale, debe ejecutar el elemento <br><code>Reiniciar DNS</code><br>desde el menú de herramientas, haciendo clic derecho en el icono de wampmanager</i>',
	'HoweverAlias' => 'Porás modificar otro alias validando "Agregar un servidor virtual".<br>Sin embargo, para que Wampmanager (Apache) tenga en cuenta estos alias modificados, debe ejecutar el elemento <br><code>Reiniciar DNS</code><br>desde el menú de herramientas, haciendo clic derecho en el icono de wampmanager</i>',
	'HoweverWamp' => 'Se ha tenido en cuenta el servidor virtual creado o modificado por Apache<br>Porás agregar o modificar otro servidor virtual validando "Agregar un servidor virtual"<br>Podrá comenzar a trabajar en este nuevo servidor virtual si lo desea<br>Pero para que los menús de Wampmanager tengan en cuenta estos nuevos servidores virtuales, debe iniciar el elemento <br><code>Actualizar</code><br>desde el menú contextual del icono de Wampmanager</i>',
	'suppForm' => 'Eliminar formulario del servidor virtual',
	'suppVhost' => 'Eliminar servidor virtual',
	'modifyForm' => 'Modificar formulario del servidor virtual',
	'modifyVhost' => 'Modificar servidor virtual',
	'modAliasForm' => 'Modificar formulario alias',
	'modifyAlias' => 'Modificar alias',
	'Required' => 'Requerido',
	'Optional' => 'Opcional',
	'phpNotExists' => 'La versión de PHP no existe',
	);

?>