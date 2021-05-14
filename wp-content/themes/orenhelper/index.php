<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package orenhelper
 */
get_header();
?>
    <hr class="hr--1">
    <div class="text-container">
        <h1>
            Повышай
            <span style="color: #327F94;">безопасность</span>
            <br> на дорогах своего города
        </h1>
        <p>
            «Помощник Оренбурга» фиксирует нарушения ПДД быстро и просто
        </p>

        <div class="divider"></div>
        <?php if (!is_user_logged_in()):?>
            <a href="/member-register/" target="_blank">
                <input type="button" class="button" value="Регистрация">
            </a>
            <div class="divider"></div>
            <a href="/member-login/" target="_blank">
                <input type="button" class="button" value="Войти">
            </a>
        <?php else: ?>
            <a href="/member-account/">
                <input type="button" class="button" value="В личный кабинет">
            </a>
        <?php endif; ?>
    </div>

    <hr class="hr--2">

    <div class="container-flex">
        <div class="text-container text-container--small">
            <h2>Зачем нужен
                <span style="color: #327F94;">«Помощник»</span>
            </h2>
            <p class="p--18">
                С помощью «Помощника Оренбурга» вы можете зафиксировать нарушения ПДД и отправить его на обработку
                сотрудникам ГИБДД прямо со своего смартфона.
            </p>
        </div>
        <div class="phone-mockup">
            <img class="phone-mockup__body" src="<?=get_template_directory_uri();?>/assets/img/phone.png">
            <img class="phone-mockup__label" src="<?=get_template_directory_uri();?>/assets/img/iPhone X Mockup label.png">
        </div>
        <hr class="hr--3">
        <div class="container-steps">
            <div class="container-steps__text">
                <div class="container-steps__text-row">
                    <div class="container-steps__text-col-1">01</div>
                    <div class="container-steps__text-col-2">
                        <p class="p--no-line-height">Пройдите Регистрацию</p>
                    </div>
                    <div class="container-steps__text-col-3">
                        <p class="p--18 p--no-line-height">
                            Зарегистрируйтесь в сервисе, заполните анкетные данные</div>
                    </p>
                </div>
            </div>
            <hr class="hr--4">
            <div class="container-steps__text">
                <div class="container-steps__text-row">
                    <div class="container-steps__text-col-1">02</div>
                    <div class="container-steps__text-col-2">
                        <p class="p--no-line-height">Подтвердите аккаунт</p>
                    </div>
                    <div class="container-steps__text-col-3">
                        <p class="p--18 p--no-line-height">
                            После регистрации на ваш номер придет смс с кодом подтверждения</div>
                    </p>
                </div>
            </div>
            <hr class="hr--4">
            <div class="container-steps__text">
                <div class="container-steps__text-row">
                    <div class="container-steps__text-col-1">03</div>
                    <div class="container-steps__text-col-2">
                        <p class="p--no-line-height">Фиксируйте нарушения</p>
                    </div>
                    <div class="container-steps__text-col-3">
                        <p class="p--18 p--no-line-height">
                            После подтверждения введенных данных вы можете пользоваться сервисом
                        </p>
                    </div>
                </div>
            </div>
            <?php if (!is_user_logged_in()):?>
                <div class="container-steps__right-panel">
                    <hr class="hr--4">
                    <p class="p--18">Регистрация займёт не более 5 минут</p>
                    <a href="/member-register/">
                        <input type="button" class="button button--white" value="Создать аккаунт" href="registration.html">
                    </a>
                </div>
            <?php endif; ?>
        </div>
<?php
get_footer();
