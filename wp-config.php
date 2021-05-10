<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'pozhilrp_orhelp' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'pozhilrp_orhelp' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '78035ALROWt50' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'qd,O1ZWTHQn|oN#u89vqnQ})_B|(E.}T$O894oD1K!=_xh)2bA<(?i&y0J4VI&MW' );
define( 'SECURE_AUTH_KEY',  '1]R?v*!VO~7Hc]N(%sHY]8<xdilK)71,VYl@8r)Lgbv~pU_jn1A@v?a.-IdXDGKd' );
define( 'LOGGED_IN_KEY',    'mcj:.W_QuoW#+37F;7U$H4Su%,/c?yD)CBDB*~3^yx^$~URF6e[idb$W)Awz/p|/' );
define( 'NONCE_KEY',        'a8QT#!}pz19,:?$rD_.46&Uf29%>8-Vd$t`._ -~6Wg%FB G2vx:Vh52xQ?gfD2w' );
define( 'AUTH_SALT',        '?YPvGH?,5u9|6ITjARC=z(x#|U7Uf}bsX=8~TUn528r[+K3NnsbP/Dm(^(WKZC&O' );
define( 'SECURE_AUTH_SALT', '~Q-]W*xefh0hH}b-n6]zgi=:;3Vcq[/e(wf|)Cb4EK p~>cGXk`[O3[w% :y T}b' );
define( 'LOGGED_IN_SALT',   'VOT5QEfhnPZWmybj9QAR+-kC^`&~1sQZ_J=F*o:|CIZL3w~_a~1OA}n-[ z2,)O/' );
define( 'NONCE_SALT',       'tJK{H[t~Ci9[i5TT{/v3RC.sc|/qDB.NC`.kz{RRP9EOz}U} ^BTp(R23WhjgHa8' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp1458_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
