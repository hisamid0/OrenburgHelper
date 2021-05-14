<?php
acf_form_head();
get_header('account');

if (!is_user_logged_in()) { ?>
    <h3>Пожалуйста войдите в <a href="/member-login/">систему</a>!</h3>
<?php } else {
    $user = wp_get_current_user();
    $splitNames = explode(" ", $user->display_name); ?>
    <div class="container-flex-dir-row">
        <p class="color-grey">Фиксируй нарушения и получай бонусные баллы за успешно принятые заявления</p>
    </div>
    <h1><?= $splitNames[0]; ?> <? the_author_meta('middle_name', $user->ID); ?> <?= $splitNames[1]; ?></h1>
    <hr class="hr--4">

    <div>
        <div class="container-send-form_stats">
            <div class="container-steps__text-row">
                <div class="container-steps__text-col-1 margin-right--18"><?= current_user_posts_count($user->ID); ?></div>
                <div class="container-steps__text-col-2">
                    <p class="p--no-line-height">Фиксаций нарушений</p>
                </div>
            </div>
            <div class="container-steps__text-row">
                <div class="container-steps__text-col-1 margin-right--18"><?php if(empty(get_user_meta($user->ID, 'bonuses', true))) echo 0; else echo get_user_meta($user->ID, 'bonuses', true);?></div>
                <div class="container-steps__text-col-2">
                    <p class="p--no-line-height">Набрано баллов</p>
                </div>
            </div>
            <div class="container-steps__text-row">
                <div class="container-steps__text-col-1 margin-right--18"><?= count_user_posts($user->ID); ?></div>
                <div class="container-steps__text-col-2">
                    <p class="p--no-line-height">Заявлений принято</p>
                </div>
            </div>
        </div>

        <hr class="hr--4">



    </div>

    <?php
    acf_form(array(
        'post_id' => 'new_post',
        'new_post' => array(
            'post_type' => 'post',
            'post_status' => 'pending',
            'post_title' => 'Нарушение'
        ),
        'label_placement' => 'left',
//        'html_before_fields' => '<div id="map"></div>',
        'updated_message' => __("Нарушение было добавлено на рассмотрение", 'acf'),
        'submit_value' => 'Зафиксировать нарушение'
    ));
    ?>
<?php } ?>

<?php
get_footer();
?>