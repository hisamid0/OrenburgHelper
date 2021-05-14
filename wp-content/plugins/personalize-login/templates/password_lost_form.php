<h1>Восстановление пароля</h1>

<!--  Показываем ошибки  -->
<?php if (count($attributes['errors']) > 0) : ?>
    <?php foreach ($attributes['errors'] as $error) : ?>
        <p class="p--16">
            <?php echo $error; ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>

<p class="p--16">
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
        <input id="lost-pass-submit" type="submit" name="submit" class="button button-primary"
               value="<?php _e('Сбросить пароль', 'personalize-login'); ?>"/>
    </p>
</form>
