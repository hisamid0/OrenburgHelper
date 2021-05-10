<?php
acf_form_head();
get_header();

if (!is_user_logged_in()) { ?>
    <h3>Пожалуйста войдите в <a href="/member-login/">систему</a>!</h3>
<?php } else {
    $user = wp_get_current_user();
    $splitNames = explode(" ", $user->display_name); ?>
    <h3><?= $splitNames[0]; ?><? the_author_meta('middle_name', $user->ID); ?> <?= $splitNames[1]; ?></h3>
    <?php $violations = wp_count_posts(); ?>
    <?= current_user_posts_count($user->ID); ?>
    <span> Фиксаций нарушений</span>
    <?php if(empty(get_user_meta($user->ID, 'bonuses', true))) echo 0; else echo get_user_meta($user->ID, 'bonuses', true);?>
    <span> Набрано баллов</span> <?= count_user_posts($user->ID); ?>
    <span> Заявлений принято</span>

    <?php
    acf_form(array(
        'post_id' => 'new_post',
        'new_post' => array(
            'post_type' => 'post',
            'post_status' => 'pending',
            'post_title' => 'Нарушение'
        ),
//        'html_before_fields' => '<div id="map"></div>',
        'updated_message' => __("Нарушение было добавлено на рассмотрение", 'acf'),
        'submit_value' => 'Зафиксировать нарушение'
    ));
    ?>
    <?php the_content(); ?>
<?php } ?>

<?php
get_footer();
?>