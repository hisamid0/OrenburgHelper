<?php

/**
 * Plugin Name:       Авторизация / Регистрация
 * Description:       Замена стандартных страниц входа и регистрации
 * Version:           1.0.0
 * Author:            Vladislav Kondrakov
 */

class Personalize_Login_Plugin
{

    /**
     * Инициализация плагина.
     */
    public function __construct()
    {
        // Login
        add_shortcode('custom-login-form', array($this, 'render_login_form'));
        add_action('login_form_login', array($this, 'redirect_to_custom_login'));
        add_filter('authenticate', array($this, 'maybe_redirect_at_authenticate'), 101, 3);
        add_action('wp_logout', array($this, 'redirect_after_logout'));
        add_filter('login_redirect', array($this, 'redirect_after_login'), 10, 3);

        // Register
        add_shortcode('custom-register-form', array($this, 'render_register_form'));
        add_action('login_form_register', array($this, 'redirect_to_custom_register'));
        add_action('login_form_register', array($this, 'do_register_user'));
        add_filter('wp_new_user_notification_email', array($this, 'hpl_user_notification_email'), 10, 3);

        // Forgot Password
        add_action('login_form_lostpassword', array($this, 'redirect_to_custom_lostpassword'));
        add_shortcode('custom-password-lost-form', array($this, 'render_password_lost_form'));
        add_action('login_form_lostpassword', array($this, 'do_password_lost'));
        add_filter('retrieve_password_message', array($this, 'replace_retrieve_password_message'), 10, 4);
        add_action('login_form_rp', array($this, 'redirect_to_custom_password_reset'));
        add_action('login_form_resetpass', array($this, 'redirect_to_custom_password_reset'));
        add_shortcode('custom-password-reset-form', array($this, 'render_password_reset_form'));
        add_action('login_form_rp', array($this, 'do_password_reset'));
        add_action('login_form_resetpass', array($this, 'do_password_reset'));
    }

    /**
     * Хук активации.
     *
     * Создаёт все нужные страницы для входа пользователя в систему.
     */
    public static function plugin_activated()
    {
        // Создание страниц при активации плагина
        $page_definitions = array(
            'member-login' => array(
                'title' => __('Авторизация', 'personalize-login'),
                'content' => '[custom-login-form]'
            ),
            'member-account' => array(
                'title' => __('Личный кабинет', 'personalize-login'),
                'content' => ''
            ),
            'member-register' => array(
                'title' => __('Регистрация', 'personalize-login'),
                'content' => '[custom-register-form]'
            ),
            'member-password-lost' => array(
                'title' => __('Забыли пароль?', 'personalize-login'),
                'content' => '[custom-password-lost-form]'
            ),
            'member-password-reset' => array(
                'title' => __('Создать новый пароль', 'personalize-login'),
                'content' => '[custom-password-reset-form]'
            )
        );

        foreach ($page_definitions as $slug => $page) {
            // Проверка существования страницы
            $query = new WP_Query('pagename=' . $slug);
            if (!$query->have_posts()) {
                // Добавляем страницы, исходя из представленного массива данных
                wp_insert_post(
                    array(
                        'post_content' => $page['content'],
                        'post_name' => $slug,
                        'post_title' => $page['title'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'ping_status' => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }

    // LOG IN ZONE

    /**
     * Шорткод формы входа в систему.
     *
     * @param array $attributes Атрибуты шорткода.
     * @param string $content Текст шорткода.
     *
     * @return string  Вывод шорткода
     */
    public function render_login_form($attributes, $content = null)
    {
        // Разбор атрибутов шорткода
        $default_attributes = array('show_title' => false);
        $attributes = shortcode_atts($default_attributes, $attributes);
        $show_title = $attributes['show_title'];

        if (is_user_logged_in()) {
            return __('Вы уже вошли в систему.', 'personalize-login');
        }

        // Передаём параметр для перенаправления: по умолчанию,
        // Если будет передан валиндый параметр, то обрабатываем его.
        $attributes['redirect'] = '';
        if (isset($_REQUEST['redirect_to'])) {
            $attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
        }

        // Сообщения об ошибках
        $errors = array();
        if (isset($_REQUEST['login'])) {
            $error_codes = explode(',', $_REQUEST['login']);

            foreach ($error_codes as $code) {
                $errors [] = $this->get_error_message($code);
            }
        }
        $attributes['errors'] = $errors;

        // Проверяем был ли осуществлён выход
        $attributes['logged_out'] = isset($_REQUEST['logged_out']) && $_REQUEST['logged_out'] == true;

        // Проверка на факт регистрации пользователя
        $attributes['registered'] = isset($_REQUEST['registered']);

        // Проверка запроса нового пароля
        $attributes['lost_password_sent'] = isset($_REQUEST['checkemail']) && $_REQUEST['checkemail'] == 'confirm';

        // Проверка, если пользователь только что обновил пароль
        $attributes['password_updated'] = isset($_REQUEST['password']) && $_REQUEST['password'] == 'changed';

        // Отображаем форму входа
        return $this->get_template_html('login_form', $attributes);
    }

    /**
     * Рендеринг в строку
     *
     * @param string $template_name Шаблон в который рендерим
     * @param array $attributes PHP переменные для шаблона
     *
     * @return string               Контент шаблона
     */
    private function get_template_html($template_name, $attributes = null)
    {
        if (!$attributes) {
            $attributes = array();
        }

        ob_start();

        do_action('personalize_login_before_' . $template_name);

        require('templates/' . $template_name . '.php');

        do_action('personalize_login_after_' . $template_name);

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Направление пользователя на собственную страницу аутентификации вместо wp-login.php.
     */
    function redirect_to_custom_login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : null;

            if (is_user_logged_in()) {
                $this->redirect_logged_in_user($redirect_to);
                exit;
            }

            // В остальных случая направляем на страницу логина
            $login_url = home_url('member-login');
            if (!empty($redirect_to)) {
                $login_url = add_query_arg('redirect_to', $redirect_to, $login_url);
            }

            wp_redirect($login_url);
            exit;
        }
    }

    /**
     * Направление пользователя в зависимости является ли он администратором или нет
     *
     * @param string $redirect_to Опциональный redirect_to URL для админа
     */
    private function redirect_logged_in_user($redirect_to = null)
    {
        $user = wp_get_current_user();
        if (user_can($user, 'manage_options')) {
            if ($redirect_to) {
                wp_safe_redirect($redirect_to);
            } else {
                wp_redirect(admin_url());
            }
        } else {
            wp_redirect(home_url('member-account'));
        }
    }

    /**
     * Направление пользователя при вводе неверных данных аутентификации
     *
     * @param Wp_User|Wp_Error $user Аутентифицированный пользователь или ошибки, возникшие в процессе аутентификации.
     * @param string $username Имя пользователя.
     * @param string $password Пароль.
     *
     * @return Wp_User|Wp_Error Аутентифицированный пользователь или ошибки, возникшие в процессе аутентификации.
     */
    function maybe_redirect_at_authenticate($user, $username, $password)
    {
        // Проверка на наличие ошибок аутентификации
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (is_wp_error($user)) {
                $error_codes = join(',', $user->get_error_codes());

                $login_url = home_url('member-login');
                $login_url = add_query_arg('login', $error_codes, $login_url);

                wp_redirect($login_url);
                exit;
            }
        }

        return $user;
    }

    /**
     * Находит и возвращает сообщение об ошибки в зависимости от кода.
     *
     * @param string $error_code Код ошибки.
     *
     * @return string            Сообщение об ошибке.
     */
    private function get_error_message($error_code)
    {
        switch ($error_code) {
            case 'empty_username':
                return __('Логин не должен быть пустым', 'personalize-login');

            case 'empty_password':
                return __('Пароль не должен быть пустым', 'personalize-login');

            case 'invalid_username':
                return __(
                    "Пользователь с данным Email отсутсвует",
                    'personalize-login'
                );

            case 'incorrect_password':
                $err = __(
                    "Неправильный пароль. Введите верный или воспользуйтесь восстановлением",
                    'personalize-login'
                );
                return sprintf($err, wp_lostpassword_url());

            // Ошибки при регистрации
            case 'email':
                return __('Формат Email не подходит.', 'personalize-login');

            case 'email_exists':
                return __('Данный Email адрес уже существует.', 'personalize-login');

            case 'closed':
                return __('Регистрация новых пользователей приостановлена.', 'personalize-login');

            case 'checkbox_terms_of_service':
                return __('Вы должны принять условия использования.', 'personalize-login');

            // Ошибки при сбросе пароля
            case 'empty_username':
                return __('Необходимо ввести Email, чтобы сбросить пароль.', 'personalize-login');

            case 'invalid_email':
            case 'invalidcombo':
                return __('Пользователя с данным Email не существует.', 'personalize-login');

            case 'expiredkey':
            case 'invalidkey':
                return __('Ссылка для сброса больше недоступна.', 'personalize-login');

            case 'password_reset_mismatch':
                return __("Пароли, которые Вы ввели, не совпадают.", 'personalize-login');

            case 'password_reset_empty':
                return __("Пароль не должен быть пустым.", 'personalize-login');
            default:
                break;
        }

        return __('Неизвестная ошибка. Попробуйте позже.', 'personalize-login');
    }

    /**
     * Отображение собственной страницы аутентификации после выхода.
     */
    public function redirect_after_logout()
    {
        $redirect_url = home_url('member-login?logged_out=true');
        wp_safe_redirect($redirect_url);
        exit;
    }

    /**
     * Возвращает URL на который должен быть перенаправлен пользователь после успешной аутентификации.
     *
     * @param string $redirect_to URL для перенаправления.
     * @param string $requested_redirect_to Запрашиваемый URL.
     * @param WP_User|WP_Error $user Объект WP_User если аутентификация прошла успешна или объект WP_Error в обратном случае.
     *
     * @return string URL для перенаправления
     */
    public function redirect_after_login($redirect_to, $requested_redirect_to, $user)
    {
        $redirect_url = home_url();

        if (!isset($user->ID)) {
            return $redirect_url;
        }

        if (user_can($user, 'manage_options')) {
            // Используем параметр redirect_to, если в нём было передано значение.
            if ($requested_redirect_to == '') {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }
        } else {
            // Не администраторы будут направляться на страницу информаци о профиле
            $redirect_url = home_url('member-account');
        }

        return wp_validate_redirect($redirect_url, home_url());
    }

    // REGISTER ZONE

    /**
     * Шорткод для рендеринга формы регистрации нового пользователя.
     *
     * @param array $attributes Атрибуты шорткода.
     * @param string $content Контент шорткода. Не используется.
     *
     * @return string  Вывод шорткода
     */
    public function render_register_form($attributes, $content = null)
    {
        // Разбор атрибутов шорткода
        $default_attributes = array('show_title' => false);
        $attributes = shortcode_atts($default_attributes, $attributes);

        if (is_user_logged_in()) {
            return __('Вы уже вошли в систему.', 'personalize-login');
        } elseif (!get_option('users_can_register')) {
            return __('Регистрация пользователей приостановлена.', 'personalize-login');
        } else {
            // Получаем сообщения об ошибках из параметра запроса
            $attributes['errors'] = array();
            if (isset($_REQUEST['register-errors'])) {
                $error_codes = explode(',', $_REQUEST['register-errors']);

                foreach ($error_codes as $error_code) {
                    $attributes['errors'] [] = $this->get_error_message($error_code);
                }
            }

            return $this->get_template_html('register_form', $attributes);
        }
    }

    /**
     * Направление пользователя на собственную страницу регистрации
     * вместо wp-login.php?action=register.
     */
    public function redirect_to_custom_register()
    {
        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
            } else {
                wp_redirect(home_url('member-register'));
            }
            exit;
        }
    }

    /**
     * Валидация данных и запуск процесса регистрации пользователя.
     *
     * @param string $email Новый email пользователя
     * @param string $first_name Имя пользователя
     * @param string $last_name Фамилия пользователя
     * @param string $middle_name Отчество пользователя
     * @param string $phone_number Номер телефона пользователя
     * @param DateTime $birthday День рождения пользователя
     * @param boolean $checkbox_terms_of_service Принял ли пользователь условия соглашения
     * @param boolean $checkbox_mailing Принял ли пользователь согласие на рассылку
     *
     * @return int|WP_Error         Id нового пользователя или ошибка.
     */
    private function register_user($email, $first_name, $last_name, $middle_name, $phone_number, $birthday, $checkbox_terms_of_service, $checkbox_mailing)
    {
        $errors = new WP_Error();

        // Email будет использоваться в качестве адреса почты и логина
        if (!is_email($email)) {
            $errors->add('email', $this->get_error_message('email'));
            return $errors;
        }

        if (username_exists($email) || email_exists($email)) {
            $errors->add('email_exists', $this->get_error_message('email_exists'));
            return $errors;
        }

        // Проверка чекбокса на условия использования
        if (!$checkbox_terms_of_service) {
            $errors->add('checkbox_terms_of_service', $this->get_error_message('checkbox_terms_of_service'));
            return $errors;
        }

        // Формирование пароля, который будет отправлен на почту пользователя...
        $password = wp_generate_password(12, false);

        $user_data = array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'nickname' => $first_name,
            'show_admin_bar_front' => 'false',
        );

        $user_id = wp_insert_user($user_data);

        // Доп поля
        add_user_meta($user_id, 'middle_name', $middle_name);
        add_user_meta($user_id, 'phone_number', $phone_number);
        add_user_meta($user_id, 'birthday', $birthday);
        add_user_meta($user_id, 'mailing', $checkbox_mailing);

        wp_new_user_notification($user_id, null, 'both');

        return $user_id;
    }

    /**
     * Отлов регистрации нового пользователя.
     *
     * Будет задействована через хук "login_form_register" в wp-login.php
     * при регистрации.
     */
    public function do_register_user()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $redirect_url = home_url('member-register');

            if (!get_option('users_can_register')) {
                // Регистрация закрыта, отображение ошибки
                $redirect_url = add_query_arg('register-errors', 'closed', $redirect_url);
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field($_POST['first_name']);
                $last_name = sanitize_text_field($_POST['last_name']);
                $middle_name = sanitize_text_field($_POST['middle_name']);
                $phone_number = $_POST['phone_number'];
                $birthday = $_POST['birthday'];
                $checkbox_terms_of_service = (!empty($_POST['checkbox-terms-of-service'])) ? true : false;
                $checkbox_mailing = (!empty($_POST['checkbox-mailing'])) ? true : false;

                $result = $this->register_user($email, $first_name, $last_name, $middle_name, $phone_number, $birthday, $checkbox_terms_of_service, $checkbox_mailing);

                if (is_wp_error($result)) {
                    // Разбор ошибок и передача их в качестве параметров при редиректе
                    $errors = join(',', $result->get_error_codes());
                    $redirect_url = add_query_arg('register-errors', $errors, $redirect_url);
                } else {
                    // Успех, направления пользователя на страницу входа.
                    $redirect_url = home_url('member-login');
                    $redirect_url = add_query_arg('registered', $email, $redirect_url);
                }
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Изменяет содержимое письма, отправляемое при регистрации нового пользователя
     *
     * @param array $email_data
     * @param WP_User $user
     * @param string $blogname
     *
     * @return array
     */
    function hpl_user_notification_email($email_data, $user, $blogname)
    {
        $newpass = wp_generate_password(10, true, false);
        wp_set_password($newpass, $user->ID);

        $text = sprintf(__("Добро пожаловать в %s! Здесь Ваши данные для авторизации:"), $blogname) . "\r\n\r\n";
        $text .= wp_login_url() . "\r\n\r\n";
        $text .= sprintf(__('Имя пользователя: %s'), $user->user_login) . "\r\n\r\n";
        $text .= sprintf(__('Пароль: %s'), $newpass) . "\r\n\r\n";
        $text .= sprintf(__('Если у Вас возникли вопросы, свяжитесь с нами по почте: %s.'), get_option('admin_email')) . "\r\n\r\n";
        $text .= __('Приятного дня!');

        $email_data['subject'] = 'Доступ к личному кабинету сайта "' . wp_specialchars_decode($blogname) . '"';
        $email_data['message'] = $text;

        return $email_data;
    }

    // Forgot Password Zone

    /**
     * Редирект пользователя на кастомную страницу вместо wp-login.php?action=lostpassword.
     */
    public function redirect_to_custom_lostpassword()
    {
        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            if (is_user_logged_in()) {
                $this->redirect_logged_in_user();
                exit;
            }

            wp_redirect(home_url('member-password-lost'));
            exit;
        }
    }

    /**
     * Рендеринг формы для сброса пароля.
     *
     * @param array $attributes Аттрибуты шорткода.
     * @param string $content Контент.
     *
     * @return string  Вывод шорткода
     */
    public function render_password_lost_form($attributes, $content = null)
    {
        // Парсим аттрибуты шорткода
        $default_attributes = array('show_title' => false);
        $attributes = shortcode_atts($default_attributes, $attributes);

        if (is_user_logged_in()) {
            return __('Вы уже вошли.', 'personalize-login');
        } else {
            // Получаем возможные ошибки из параметров запроса
            $attributes['errors'] = array();
            if (isset($_REQUEST['errors'])) {
                $error_codes = explode(',', $_REQUEST['errors']);

                foreach ($error_codes as $error_code) {
                    $attributes['errors'] [] = $this->get_error_message($error_code);
                }
            }

            return $this->get_template_html('password_lost_form', $attributes);
        }
    }

    /**
     * Запуск процесса сброса пароля.
     */
    public function do_password_lost()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $errors = retrieve_password();
            if (is_wp_error($errors)) {
                // Найдены ошибки
                $redirect_url = home_url('member-password-lost');
                $redirect_url = add_query_arg('errors', join(',', $errors->get_error_codes()), $redirect_url);
            } else {
                // Отправка Email
                $redirect_url = home_url('member-login');
                $redirect_url = add_query_arg('checkemail', 'confirm', $redirect_url);
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Возвращает тело сообщения для письма сброса пароля.
     *
     * @param string $message Сообщение по-умолчанию.
     * @param string $key Ключ активации.
     * @param string $user_login Имя пользователя.
     * @param WP_User $user_data WP_User объект.
     *
     * @return string   Сообщение для отправки.
     */
    public function replace_retrieve_password_message($message, $key, $user_login, $user_data)
    {
        // Создаем новое сообщение
        $msg = __('Здравствуйте!', 'personalize-login') . "\r\n\r\n";
        $msg .= sprintf(__('Вы отправляли запрос на сброс пароля для следующего Email адреса %s.', 'personalize-login'), $user_login) . "\r\n\r\n";
        $msg .= __("Если это ошибка, и Вы не отправляли запрос, просто проигнорируйте письмо.", 'personalize-login') . "\r\n\r\n";
        $msg .= __('Для сброса пароля перейдите по ссылке:', 'personalize-login') . "\r\n\r\n";
        $msg .= site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n\r\n";
        $msg .= __('Спасибо!', 'personalize-login') . "\r\n";

        return $msg;
    }

    /**
     * Редирект на кастомную страницу сброса пароля, или страницу логина, если ошибка
     */
    public function redirect_to_custom_password_reset()
    {
        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            // Подтверждения ключа
            $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
            if (!$user || is_wp_error($user)) {
                if ($user && $user->get_error_code() === 'expired_key') {
                    wp_redirect(home_url('member-login?login=expiredkey'));
                } else {
                    wp_redirect(home_url('member-login?login=invalidkey'));
                }
                exit;
            }

            $redirect_url = home_url('member-password-reset');
            $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
            $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);

            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Шорткод для рендеринга формы сброса пароля.
     *
     * @param array $attributes Аттрибуты шорткода.
     * @param string $content Контент.
     *
     * @return string  Вывод шорткода
     */
    public function render_password_reset_form($attributes, $content = null)
    {
        // Парсим аттрибуты
        $default_attributes = array('show_title' => false);
        $attributes = shortcode_atts($default_attributes, $attributes);

        if (is_user_logged_in()) {
            return __('Вы уше вошли.', 'personalize-login');
        } else {
            if (isset($_REQUEST['login']) && isset($_REQUEST['key'])) {
                $attributes['login'] = $_REQUEST['login'];
                $attributes['key'] = $_REQUEST['key'];

                // Сообщения об ошибке
                $errors = array();
                if (isset($_REQUEST['error'])) {
                    $error_codes = explode(',', $_REQUEST['error']);

                    foreach ($error_codes as $code) {
                        $errors [] = $this->get_error_message($code);
                    }
                }
                $attributes['errors'] = $errors;

                return $this->get_template_html('password_reset_form', $attributes);
            } else {
                return __('Неверная ссылка.', 'personalize-login');
            }
        }
    }

    /**
     * Сбросить пароль, если форма была успешно отправлена.
     */
    public function do_password_reset()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];

            $user = check_password_reset_key($rp_key, $rp_login);

            if (!$user || is_wp_error($user)) {
                if ($user && $user->get_error_code() === 'expired_key') {
                    wp_redirect(home_url('member-login?login=expiredkey'));
                } else {
                    wp_redirect(home_url('member-login?login=invalidkey'));
                }
                exit;
            }

            if (isset($_POST['pass1'])) {
                if ($_POST['pass1'] != $_POST['pass2']) {
                    // Пароли не совпадают
                    $redirect_url = home_url('member-password-reset');

                    $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                    $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                    $redirect_url = add_query_arg('error', 'password_reset_mismatch', $redirect_url);

                    wp_redirect($redirect_url);
                    exit;
                }

                if (empty($_POST['pass1'])) {
                    // Пароль пустой
                    $redirect_url = home_url('member-password-reset');

                    $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
                    $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
                    $redirect_url = add_query_arg('error', 'password_reset_empty', $redirect_url);

                    wp_redirect($redirect_url);
                    exit;
                }

                // Все ОК, сбрасываем
                reset_password($user, $_POST['pass1']);
                wp_redirect(home_url('member-login?password=changed'));
            } else {
                echo "Неверный запрос.";
            }

            exit;
        }
    }
}

// Инициализация плагина
$personalize_login_plugin = new Personalize_Login_Plugin();

// Создаём страницы при активации плагина
register_activation_hook(__FILE__, array('Personalize_Login_Plugin', 'plugin_activated'));