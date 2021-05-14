<?php
/**
 * orenhelper functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package orenhelper
 */

// Подключение стилей и шрифтов
function orenhelper_styles_and_fonts()
{
    // СТИЛИ
//    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_style('style', get_stylesheet_uri());
//    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/plugins/slick/slick.css');

    // ШРИФТЫ
//    wp_enqueue_style('orenhelper-google-fonts-roboto', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap');
//    wp_enqueue_style('orenhelper-google-fonts-bebas', 'https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

//    wp_enqueue_style('google-preconnect', 'https://fonts.gstatic.com');
//    wp_enqueue_style('orenhelper-google-fonts', 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto&display=swap');
}

// Подключение скриптов
function orenhelper_scripts()
{
    wp_enqueue_script('main', get_template_directory_uri() . "/assets/js/main.js", array('jquery'), false, true);
//    wp_enqueue_script('gmaps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyDzS_zfRqauGZGeUGo2L-UZvte0u3rPHuk", array('jquery'), false, true);
}

add_action('wp_enqueue_scripts', 'orenhelper_styles_and_fonts');
add_action('wp_footer', 'orenhelper_scripts');

add_theme_support('title-tag');

// Регистрация Google Maps API
function my_acf_google_map_api($api)
{
    $api['key'] = 'AIzaSyDzS_zfRqauGZGeUGo2L-UZvte0u3rPHuk';
    return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

/**
 * Все посты текущего пользователя
 */
function current_user_posts_count($userID)
{
    global $wpdb;
    $query = "SELECT COUNT(*) FROM wp1458_posts WHERE post_author = {$userID} AND post_type = 'post'";
    $post_count = $wpdb->get_var($query);
    return $post_count;
}

/**
 * Добавление бонусных баллов автору поста после перевода статуса
 */
function to_publish($post)
{
    $bonuses = get_user_meta($post->post_author, 'bonuses', true);
    if(!empty($bonuses)) {
        update_user_meta($post->post_author, 'bonuses', $bonuses+2);
    } else {
        update_user_meta($post->post_author, 'bonuses', 2);
    }


}

add_action('pending_to_publish', 'to_publish', 10, 1);

// Удаляем ненужные пункты меню
add_action('admin_menu', 'remove_menus');
function remove_menus()
{
    global $menu;
    $restricted = array(
        __('Links'),
        __('Tools'),
        __('Comments'),
    );
    end($menu);
    while (prev($menu)) {
        $value = explode(' ', $menu[key($menu)][0]);
        if (in_array(($value[0] != NULL ? $value[0] : ""), $restricted)) {
            unset($menu[key($menu)]);
        }
    }
}