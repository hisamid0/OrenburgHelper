<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package orenhelper
 */

?>

        <div class="footer">
            <a class="footer__line">2021@Помощник Оренбурга</a>
            <a>Разработан студентами 16КБ(С)РЗПО</a>
        </div>
    </div>

    <?php if (is_front_page()): ?>
        <?php get_template_part('template-parts/car-ellipse');?>
    <?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>