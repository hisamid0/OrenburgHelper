<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package orenhelper
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>

<body>
<div class="body-container--register">
    <div class="container-send-form_header">
        <a href="<?= home_url(); ?>">
            <img class="text-logo--no-left-margin" src="<?= get_template_directory_uri(); ?>/assets/img/text-logo.svg">
        </a>
        <?php if (is_user_logged_in()):?>
            <div class="container-send-form_buttons">
                <a href="<?php echo wp_logout_url(home_url()); ?>">
                    <input type="button" class="button button--white" value="Выйти">
                </a>
            </div>
        <?php endif; ?>
    </div>




