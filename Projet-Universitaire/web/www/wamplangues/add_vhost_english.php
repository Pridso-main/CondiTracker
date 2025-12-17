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
	'langue' => 'English',
	'locale' => 'english',
	'addVirtual' => 'Add a VirtualHost',
	'backHome' => 'Back to homepage',
	'UncommentInclude' => 'Uncomment <small>(Suppress #)</small> the line <code>#Include conf/extra/httpd-vhosts.conf</code><br>in file %s',
	'FileNotExists' => 'The file <code>%s</code> does not exists',
	'txtTLDdev' => 'The ServerName %s use TLD %s which is monopolized by web browsers. Use another TLD (.test for example)',
	'FileNotWritable' => 'The file <code>%s</code> is not writable',
	'DirNotExists' => '<code>%s</code> does not exists or is not a directory',
	'NotwwwDir' => 'The <code>%s</code> folder is reserved for "localhost". Please use another folder.',
	'NotCleaned' => 'The <code>%s</code> file has not been cleaned.<br>There remain VirtualHost examples like: dummy-host.example.com',
	'NoVirtualHost' => 'There is no VirtualHost defined in <code>%s</code><br>It should at least have the VirtualHost for localhost.',
	'NoFirst' => 'The first VirtualHost must be <code>localhost</code> in <code>%s</code> file',
	'ServerNameInvalid' => 'The ServerName <code>%s</code> is invalid.',
	'LocalIpInvalid' => 'The local IP <code>%s</code> is invalid.',
	'VirtualHostName' => 'Name of the <code>Virtual Host</code> No space - No underscore(_) ',
	'VirtualHostFolder' => 'Complete absolute <code>path</code> of the VirtualHost <code>folder</code> <i>Examples: C:/wamp/www/projet/ or E:/www/site1/</i> ',
	'VirtualHostIP' => '<code class="option">If</code> you want to use VirtualHost by IP: <code class="option">local IP</code> 127.x.y.z ',
	'VirtualHostPhpFCGI' => '<code class="option">If</code> you want to use PHP in FCGI mode <code class="option">Accepted versions</code> below ',
	'VirtualHostPort' => '<code class="option">If</code> you want to use "Listen port" other than the default <code class="option">Accepted ports</code> below ',
	'VirtualHostPortNone' => 'If you want to use a "Listen port" other than the default one, you must add a Listen Port to Apache by Right-Click Tools ',
	'VirtualAlreadyExist' => 'The ServerName <code>%s</code> already exists',
	'VirtualIpAlreadyUsed' => 'The local IP <code>%s</code> already exists',
	'VirtualPortNotExist' => 'The port <code>%s</code> is not a "Listen port" Apache',
	'VirtualPortExist' => 'The port <code>%s</code> is default "Listen port" Apache and should not be mentioned',
	'VirtualHostExists' => 'VirtualHost already defined:',
	'Start' => 'Start the creation/modification of the VirtualHost (May take a while...)',
	'StartAlias' => 'Start the modification of the Alias',
	'GreenErrors' => 'The green framed errors can be corrected automatically.',
	'Correct' => 'Start the automatic correction of errors inside the green borders panel',
	'NoModify' => 'Impossible to modify <code>httpd-vhosts.conf</code> or <code>hosts</code> files',
	'NoModifyAlias' => 'Alias has not been modified',
	'VirtualCreated' => 'The files have been modified. Virtual host <code>%s</code> was created/modified',
	'ModifiedAlias' => 'The alias <code>%s</code> have been modified',
	'CommandMessage' => 'Messages from the console to update DNS:',
	'However' => 'You may add/modify another VirtualHost by validate "Add a VirtualHost".<br>However, for these new VirtualHost are taken into account by Wampmanager (Apache), you must run item<br><code>Restart DNS</code><br>from Right-Click Tools menu of Wampmanager icon.</i>',
	'HoweverAlias' => 'You may modify another Alias by validate "Add a VirtualHost".<br>However, for these modified Alias is taken into account by Wampmanager (Apache), you must run item<br><code>Restart DNS</code><br>from Right-Click Tools menu of Wampmanager icon.</i>',
	'HoweverWamp' => 'The created/modified VirtualHost has been taken into account by Apache.<br>You may add/modify another VirtualHost by validate "Add a VirtualHost".<br>You can start working on this new VirtualHost<br>But in order for these new VirtualHosts to be taken into account by the Wampmanager menus, you must launch the item<br><code>Refresh</code><br>from Right-Click menu of Wampmanager icon.</i>',
	'suppForm' => 'Delete VirtualHost form',
	'suppVhost' => 'Delete VirtualHost',
	'modifyForm' => 'Modify VirtualHost form',
	'modifyVhost' => 'Modify VirtualHost',
	'modAliasForm' => 'Modify Alias form',
	'modifyAlias' => 'Modify Alias',
	'Required' => 'Required',
	'Optional' => 'Optional',
	'phpNotExists' => 'PHP version doesn\'t exist',
	);


?>