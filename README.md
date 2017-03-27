
*А почему сразу не написать composer.json указав все зависимости и не написать миграции для БД?*

Выполнить установку Yii2:


php composer.phar global require "fxp/composer-asset-plugin:^1.2.0"
php composer.phar create-project yiisoft/yii2-app-basic basic 2.0.11


Перейти в созданную папку basik. Добавить зависимости на библитеки в composer.json
"xj/yii2-user-agent": "1.0.0",
"yiisoft/yii2-imagine": "2.1.0"

Обновить зависмости
php composer.phar update


Необходимо создать папку для хранения загружаемых файлов. В папке web создать папку files. 
В ОС linux может потребоваться дать права доступа.
В этой же папки заменить директории css и js на текущие.

В папке basik/asset в класс AppAsset.php добавить зависимости на js и css:

public $css = [
        'css/site.css',
        'css/magnific-popup.css'
    ];
public $js = [
        'js/jquery.magnific-popup.min.js',
        'js/site.js'
    ];


В папке basik заменить директории models, views, controllers на текущие


Для связи с бд настроить конфигурационный файл basik/config/db.php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=[host];dbname=[dbname]',
    'username' => '[login]',
    'password' => '[password]',
    'charset' => 'utf8',
];

В смой базе должнв присутствовть таблица:
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `file` text,
  `user_ip` varchar(15) NOT NULL,
  `browser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


Для отправки email сообщений необходимо внести email администратора в файл basik/config/param.php
А также настроить доступ к серверу сообщений в файле basik/config/web.php:
'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'localhost',
            'username' => 'username',
            'password' => 'password',
            'port' => '587',
            'encryption' => 'tls',
        ],
]

По умолчанию сообщения будут отсылаться в папку на сервере runtime/mail/


Для начала работы перейдите по url [хост и путь к папке]/basic/web/index.php




