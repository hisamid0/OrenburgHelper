<h1>Регистрация</h1>

<!--  Отображение ошибок  -->
<?php if (count($attributes['errors']) > 0) : ?>
    <?php foreach ($attributes['errors'] as $error) : ?>
        <p>
            <?php echo $error; ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>

<p class="p--16">Личные данные</p>

<form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
    <div class="container-register-data">

        <input id="last-name" class="container-register-data__item" type="text" name="last_name" required placeholder="<?php _e('Фамилия', 'personalize-login'); ?>">

        <input id="first-name" class="container-register-data__item" type="text" name="first_name" required placeholder="<?php _e('Имя', 'personalize-login'); ?>">

        <input id="middle-name" class="container-register-data__item" type="text" name="middle_name" required placeholder="<?php _e('Отчество', 'personalize-login'); ?>">

        <input id="date" class="container-register-data__item" type="date" name="birthday" required placeholder="<?php _e('Дата рождения', 'personalize-login'); ?>">

        <input id="phone-number" class="container-register-data__item" type="tel" name="phone_number" required placeholder="<?php _e('Мобильный телефон', 'personalize-login'); ?>">

        <input id="email" class="container-register-data__item" type="email" name="email" required placeholder="<?php _e('Электронная почта', 'personalize-login'); ?>">
    </div>
    <hr class="hr--4">

    <p>
        <?php _e('Пароль генерируется автоматически и отправляется на почту.', 'personalize-login'); ?>
    </p>
    <hr class="hr--4">

    <input id="checkbox-terms-of-service" type="checkbox" name="checkbox-terms-of-service" class="checkbox">
    <p class="p--16">С условиями использования ознакомлен и согласен.</p>
    </input>
    <input id="checkbox-mailing" type="checkbox" name="checkbox-mailing" class="checkbox">
    <p class="p--16">Настоящим согласен с получением Email рассылок, информации, материалов в т.ч. материалов рекламного характера.</p>
    </input>

    <input type="submit" name="submit" class="button" value="<?php _e('Зарегистрироваться', 'personalize-login'); ?>">
</form>

<hr class="hr--4">