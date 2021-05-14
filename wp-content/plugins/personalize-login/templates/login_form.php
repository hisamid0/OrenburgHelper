<h1>Авторизация</h1>

    <!-- Показываем ошибки -->
    <?php if (count($attributes['errors']) > 0) : ?>
        <?php foreach ($attributes['errors'] as $error) : ?>
            <p class="p--16"><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Показываем сообщение об успешном разлогине -->
    <?php if ($attributes['logged_out']) : ?>
        <p class="p--16"><?php _e('Вы вышли. Хотите зайти снова?', 'personalize-login'); ?></p>
    <?php endif; ?>

    <!-- Показываем сообщение об успешном изменении пароля -->
    <?php if ($attributes['password_updated']) : ?>
        <p class="p--16"><?php _e('Ваш пароль был изменен. Вы можете снова войти', 'personalize-login'); ?></p>
    <?php endif; ?>

    <!-- Показываем сообщение об успешной регистрации -->
    <?php if ($attributes['registered']) : ?>
        <p class="p--16">
            <?php
            printf(
                __('Вы успешно зарегистрированы на сайте <strong>%s</strong>. На Ваш Email был отправлен пароль.', 'personalize-login'),
                get_bloginfo('name')
            );
            ?>
        </p>
    <?php endif; ?>

    <!-- Показываем сообщение об отправке письма на сброс пароля -->
    <?php if ($attributes['lost_password_sent']) : ?>
        <p class="p--16">
            <?php _e('Проверьте Email и найдите письмо со ссылкой на сброс пароля.', 'personalize-login'); ?>
        </p>
    <?php endif; ?>

    <?php
    wp_login_form(
        array(
            'label_username' => __('Email', 'personalize-login'),
            'label_log_in' => __('Войти', 'personalize-login'),
            'redirect' => $attributes['redirect'],
            'remember' => false,
        )
    );
    ?>

    <p class="p--16">
        <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
            <?php _e('Забыли пароль?', 'personalize-login'); ?>
        </a>
    </p>