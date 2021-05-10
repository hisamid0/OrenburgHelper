<div id="password-lost-form" class="widecolumn">
    <!--  Показываем ошибки  -->
    <?php if (count($attributes['errors']) > 0) : ?>
        <?php foreach ($attributes['errors'] as $error) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        <?php
        _e(
            "Введите Email и мы отправим ссылку на сброс пароля.",
            'personalize_login'
        );
        ?>
    </p>

    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e('Email', 'personalize-login'); ?>
                <input type="text" name="user_login" id="user_login">
        </p>

        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button"
                   value="<?php _e('Сбросить пароль', 'personalize-login'); ?>"/>
        </p>
    </form>
</div>