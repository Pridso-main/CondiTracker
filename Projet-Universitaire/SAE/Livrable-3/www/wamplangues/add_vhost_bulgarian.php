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

$langues = array(
	'langue' => 'Български',
	'locale' => 'български',
	'addVirtual' => 'Добавяне на виртуален хост',
	'backHome' => 'Kъм началната страница',
	'VirtualSubMenuOn' => '<code>Подменюто VirtualHost</code> в контекстното меню <code>Настройки на Wamp</code> трябва да бъде включено. След това презареди тази страница.',
	'UncommentInclude' => 'Разкоментирай <small>(изтрий знака #)</small> от следния ред: <code>#Include conf/extra/httpd-vhosts.conf</code><br>във файла %s.',
	'FileNotExists' => 'Файлът <code>%s</code> не съществува.',
	'txtTLDdev' => 'Името на сървъра %s използва TLD %s, който е монополизиран от браузърите. Използвай друг TLD (например .test).',
	'FileNotWritable' => 'Във файла <code>%s</code> не може да се пише.',
	'DirNotExists' => '<code>%s</code> не съществува или не е директория.',
	'NotwwwDir' => 'Папката <code>%s</code> е запазена за localhost. Използвай различна папка.',
	'NotCleaned' => 'Файлът <code>%s</code> не е изчистен.<br>В него са останали виртуални хостове от рода на: dummy-host.example.com.',
	'NoVirtualHost' => 'В <code>%s</code> не е дефиниран виртуален хост.<br>Необходим е поне един виртуален хост за localhost.',
	'NoFirst' => 'Първият виртуален хост във файла <code>%s</code> трябва да бъде <code>localhost</code>.',
	'ServerNameInvalid' => 'Името на сървъра <code>%s</code> е невалидно.',
	'LocalIpInvalid' => 'Локалното IP <code>%s</code> е невалидно.',
	'VirtualHostName' => 'Име на <code>виртуалния хост</code>. Не са позволени диакритични знаци (напр. ѝ), интервали и долни черти (_). ',
	'VirtualHostFolder' => 'Пълният абсолютен <code>път</code> до <code>папката</code> на виртуалния хост. <i>Примери: C:/wamp64/www/projet/ или E:/www/site1/</i> ',
	'VirtualHostIP' => '<code class="option">Ако</code> искаш да използваш вируален хост с IP: <code class="option">локално IP</code> 127.x.y.z ',
	'VirtualHostPhpFCGI' => '<code class="option">Ако</code> искаш да използваш PHP в режим FCGI <code class="option">Допустимите версии</code> са по-долу ',
	'VirtualHostPort' => '<code class="option">Ако</code> искаш да използваш различен порт за слушане от <code class="option">приеманите</code> по подразбиране портове по-долу ',
	'VirtualHostPortNone' => 'Ако искаш да използваш различен от стандартния порт за слушане, трябва да добавиш порт за слушане на Apache от "Инструменти", като кликнеш с десен бутон върху иконката. ',
	'VirtualAlreadyExist' => 'Името на сървъра <code>%s</code> вече съществува',
	'VirtualIpAlreadyUsed' => 'Локалното IP <code>%s</code> вече съществува.',
	'VirtualPortNotExist' => 'Портът <code>%s</code> не е порт за слушане на Apache.',
	'VirtualPortExist' => 'Портът <code>%s</code> е порт за слушане по подразбиране за Apache и не може да се споменава.',
	'VirtualHostExists' => 'Съществуващи виртуални хостове:',
	'Start' => 'Започни създаване/промяна на виртуалния хост (ще отнеме известно време...)',
	'StartAlias' => 'Започни промяната на псвевдонима',
	'GreenErrors' => 'Оцветените в зелено грешки могат да бъдат поправени автоматично.',
	'Correct' => 'Започни автоматичното поправяне на оцветените в зелено грешки',
	'NoModify' => 'Файлът <code>httpd-vhosts.conf</code> или <code>hosts</code> не може да бъде променен.',
	'NoModifyAlias' => 'Псевдонимът не е променен.',
	'VirtualCreated' => 'Файловете са променени. Виртуалният хост <code>%s</code> е създаден/променен.',
	'ModifiedAlias' => 'Псевдонимът <code>%s</code> е променен.',
	'CommandMessage' => 'Съобщения от конзолата за обновяване на DNS:',
	'However' => 'Можеш да добавиш/промениш друг виртуален хост, като кликнеш върху "Добавяне на виртуален хост".<br>За да бъдат запомнени тези нови виртуални хостове от WampManager (Apache), трябва да кликнеш върху<br><code>Рестартирай DNS</code><br>, след като кликнеш с десния бутон на мишката върху иконата на WampManager.</i>',
	'HoweverAlias' => 'Можеш да промениш друг псевдоним, като кликнеш върху "Добавяне на виртуален хост".<br>За да бъдат запомнени тези променени псевдоними от WampManager (Apache), трябва да кликнеш върху<br><code>Рестартирай DNS</code><br>, след като кликнеш с десния бутон на мишката върху иконата на WampManager.</i>',
	'HoweverWamp' => 'Създаденият/промененият виртуален хост е запомнен от Apache.<br>Можеш да добавиш/промениш друг виртуален хост, като кликнеш върху "Добавяне на виртуален хост".<br>Можеш да започнеш да работиш с този нов виртуален хост.<br>Но за да бъдат запомнени тези нови виртуални хостове от менютата на WampManager, трябва да кликнеш върху<br><code>Презареди</code><br>, след като кликнеш с десния бутон на мишката върху иконата на WampManager.</i>',
	'suppForm' => 'Формуляр за изтриване на виртуален хост',
	'suppVhost' => 'Изтрий виртуалния хост',
	'modifyForm' => 'Формуляр за промяна на виртуален хост',
	'modifyVhost' => 'Промени виртуалния хост',
	'modAliasForm' => 'Формуляр за промяна на псевдоним',
	'modifyAlias' => 'Промени псевдонима',
	'Required' => 'Задължително',
	'Optional' => 'По желание',
	'phpNotExists' => 'Версията на PHP не съществува',
	);

?>