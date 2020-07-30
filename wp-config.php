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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'moldovatruck-db' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'dYK3]bq.rar&N4~8296LOwG!X6 M!&RX!Y5H]P@-G2@]Ju!7b?(GueVaT7bjtinR' );
define( 'SECURE_AUTH_KEY',  'W*;-1H,`Pw&XEk?k8A4jW#sR[.^Aip)%(=sB[n*>.KntIU#+2ls0$}DRJ|aR4bE%' );
define( 'LOGGED_IN_KEY',    '.OZI*AK(:{KbKSst_t S:QAfpN`t_Slg%h%7Kl;l:hr9C*Qb&G> v)]rkn5<72Nf' );
define( 'NONCE_KEY',        'M)mJBj| ]wIG8 -!OTNicL,i|Evw%)]+2z4J)&9T//@$|BX-^*R=7K5(tF8=Fya>' );
define( 'AUTH_SALT',        'C1T@&TSNYKRQm/4(Y*)!ZRw~^Mxa*?bz,PkJwSdN|@zv+XaJGvf[JFn?kod2w8u@' );
define( 'SECURE_AUTH_SALT', 'WpOP4MGL;[K72OlO.eXS=AAWa#NEr~!VeR@@el?@KBE`7-NM%/;EzC8Vftc#pV*n' );
define( 'LOGGED_IN_SALT',   '7dW@./+`Q!#,G;g(6Hnc|h}Bw$>Pt6<Bod5W98R`LF+jXW gC) h6WNKH~D`.5w1' );
define( 'NONCE_SALT',       '9QcL[(Q;?6,T6xZ`QEV$0yup$?7_]t.C]D,P[qd@@4AQCTn+plAmvwDkzQ>Wi-y|' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );

/** Увелеченике лимита памяти php */
define('WP_MEMORY_LIMIT', '512M');