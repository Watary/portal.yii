# Перші кроки

## Створюємо новий проект
Створення нового проекта виконується за допомогою команди в консолі:

	composer create-project --prefer-dist yiisoft/yii2-app-basic basic

## Створюємо зручні посилання
Створюємо в каталозі **web** файл з назвою **.htaccess**, в ньому пропиуємо:

	RewriteEngine on
	
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	RewriteRule . index.php

Редагуємо конфігураційний файл (config\web.php), прибираємо коментар з рядків:

	'urlManager' => [
		'enablePrettyUrl' => true,
		'showScriptName' => false,
		'rules' => [
		],
	],

## Підключаємо базу даних
Створюэмо нову базу даннх через консоль, веб додаток phpMyAdmin або іншим зручним способом.
В мене база даних створена на локальному сервері, ім'я бази даних *portal.yii*, ім'я користувача *root* і немає пароля.
Редагуємо конфігураційний файл (config\db.php), змінюємо пртрібні рядки під новостворену базу даних:

	return [
		'class' => 'yii\db\Connection',
		'dsn' => 'mysql:host=localhost;dbname=portal.yii',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8',
	];

## Налаштовуємо адресацію в контроллерах і модудях

Редагуємо конфігураційний файл (config\console.php), додаємо наступні рядки для *urlManager*:

    'urlManager' => [
        'rules' => [
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ],
    ],