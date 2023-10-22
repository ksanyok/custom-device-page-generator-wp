<?php


function cdpg_product_page()
{
        // Проверка прав пользователя
    if (!current_user_can('manage_options')) {
        wp_die(__('У вас недостаточно прав для доступа к этой странице.'));
    }

    // Проверка на наличие ACF и Polylang
    if (!function_exists('get_field_object') || !function_exists('pll_register_string')) {
        echo 'Advanced Custom Fields или Polylang не установлены или не активированы. Установите и активируйте их перед использованием данного плагина.';
        return;
    }

    // Загрузка необходимых скриптов и стилей ACF
    acf_enqueue_scripts();

    // Обработка отправки формы
    if (isset($_POST['cdpg_generate'])) {
        cdpg_generate_product_page();
    }

   echo '<h1>Генератор товара</h1>';

// Вкладки
echo '<div class="cdpg-tabs">';
echo '<div class="cdpg-tab cdpg-tab-active" id="cdpg-tab-generation">Генерация страницы товара</div>';
echo '<div class="cdpg-tab" id="cdpg-tab-logs">Логи</div>';
echo '</div>'; // Закрытие div'a для вкладок

// Содержимое вкладок
echo '<div class="cdpg-tab-content">';

echo '<div class="cdpg-tab-content-item cdpg-tab-content-generation cdpg-tab-content-active" id="cdpg-tab-generation-content">';
echo '<form method="post" action="">';

// Табы для языков
echo '<div class="cdpg-inner-tabs">';
echo '<div class="cdpg-inner-tab cdpg-inner-tab-active" id="cdpg-inner-tab-ee">Эстонская версия</div>';
echo '<div class="cdpg-inner-tab" id="cdpg-inner-tab-ru">Русская версия</div>';
echo '</div>'; // Закрытие div'a для внутренних вкладок

// Содержимое вкладок языков
echo '<div class="cdpg-inner-tab-content">';

echo '<div class="cdpg-inner-tab-content-item cdpg-inner-tab-content-ee cdpg-inner-tab-content-active" id="cdpg-inner-tab-ee-content">';
    echo '<h3>Описание товара на Эстонском языке</h3>';

echo '<div class="device-model-box">';

echo '<div class="fields-row">';
echo '<div class="field">';
echo '<label for="service_ee">Услуга на эстонском:</label>';
echo '<select name="service_ee" id="service_ee" required>';
echo '<option value="Remont">Remont</option>';
echo '<option value="other">Other</option>';
echo '</select>';

echo '<input type="text" name="other_service_ee" id="other_field_ee" style="display: none;">';
echo '</div>';
echo '<div class="field">';
echo '<label for="device_model_ee">Модель устройства на эстонском:</label>';
echo '<input type="text" name="device_model_ee" id="device_model_ee" required>';
echo '</div>';
echo '<div class="field">';
echo '<label for="additional_field_ee">Дополнительное поле на эстонском:</label>';
echo '<input type="text" name="additional_field_ee" id="additional_field_ee">';
echo '</div>';
echo '</div>';
echo '<div class="preview-row">';
// добавьте это, чтобы отобразить предварительный просмотр заголовка
echo '<p class="preview-label">Ваш заголовок будет: <strong id="preview_title_ee"></strong></p>';
echo '</div>';
echo '</div>';

	
    // Эстонская форма
    acf_form(array(
        'post_id' => 5495,
        'field_groups' => array('group_628f6d5008b5d'), // Эстонская группа полей
        'form' => false,
        'field_el' => 'div',
        'html_before_fields' => '',
        'html_after_fields' => '',
        'instruction_placement' => 'label',
        'id' => 'cdpg-settings-form-ee', 
    ));
echo '</div>';

echo '<div class="cdpg-inner-tab-content-item cdpg-inner-tab-content-ru" id="cdpg-inner-tab-ru-content">';
    echo '<h3>Описание товара на Русском языке</h3>';

echo '<div class="device-model-box">';
echo '<div class="fields-row">';
echo '<div class="field">';
echo '<label for="service_ru">Услуга на русском:</label>';
echo '<select name="service_ru" id="service_ru" required>';
echo '<option value="Ремонт">Ремонт</option>';
echo '<option value="other">Другое</option>';
echo '</select>';

echo '<input type="text" name="other_service_ru" id="other_field_ru" style="display: none;">';
echo '</div>';
echo '<div class="field">';
echo '<label for="device_model_ru">Модель устройства на русском:</label>';
echo '<input type="text" name="device_model_ru" id="device_model_ru" required>';
echo '</div>';
echo '<div class="field">';
echo '<label for="additional_field_ru">Дополнительное поле на русском:</label>';
echo '<input type="text" name="additional_field_ru" id="additional_field_ru">';
echo '</div>';
echo '</div>';
echo '<div class="preview-row">';
// добавьте это, чтобы отобразить предварительный просмотр заголовка
echo '<p class="preview-label">Ваш заголовок будет: <strong id="preview_title_ru"></strong></p>';
echo '</div>';
echo '</div>';




 // Русская форма
    acf_form(array(
        'post_id' => 6098,
        'field_groups' => array('group_645ccc6724d12'), // Русская группа полей
        'form' => false,
        'field_el' => 'div',
        'html_before_fields' => '',
        'html_after_fields' => '',
        'instruction_placement' => 'label',
        'id' => 'cdpg-settings-form-ru', 
    ));
echo '</div>';

echo '</div>'; // Закрытие div'a для содержимого внутренних вкладок

echo '<input type="submit" name="cdpg_generate" value="Сгенерировать">';
echo '</form>';
echo '</div>'; // Закрытие div'a для содержимого вкладки "Генерация страниц"

echo '<div class="cdpg-tab-content-item cdpg-tab-content-logs" id="cdpg-tab-logs-content">';
// Содержимое для "Логи"
    $logs = file(plugin_dir_path(__FILE__) . 'logs/logs-products.txt'); // Чтение логов из файла
    if ($logs) {
        echo '<table>';
        echo '<tr><th>Название страницы</th><th>Язык</th><th>Дата-время</th><th>Ссылка</th></tr>';
        foreach ($logs as $log) {
            $log_parts = explode('|', $log);
            echo '<tr>';
            echo '<td><a href="' . get_edit_post_link($log_parts[0]) . '">' . get_the_title($log_parts[0]) . '</a></td>';
            echo '<td>' . $log_parts[1] . '</td>';
            echo '<td>' . $log_parts[2] . '</td>';
            echo '<td><a href="' . get_permalink($log_parts[0]) . '">Просмотреть</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo 'Логи пусты.';
    }
echo '</div>';

echo '</div>'; // Закрытие div'a для содержимого вкладок

}

// Функция генерации страницы
function cdpg_generate_product_page()
{
  
$service_ru = sanitize_text_field($_POST['service_ru']);
$other_service_ru = sanitize_text_field($_POST['other_service_ru']);
$device_model_ru = sanitize_text_field($_POST['device_model_ru']);
$additional_field_ru = sanitize_text_field($_POST['additional_field_ru']);

$service_ee = sanitize_text_field($_POST['service_ee']);
$other_service_ee = sanitize_text_field($_POST['other_service_ee']);
$device_model_ee = sanitize_text_field($_POST['device_model_ee']);
$additional_field_ee = sanitize_text_field($_POST['additional_field_ee']);


// Если была выбрана опция "Другое", используйте значение из поля "other_service_ee"
if ($service_ee == 'other') {
    $service_ee = $other_service_ee;
}

// Формирование названия страницы
$page_title_ee = $service_ee . ' ' . $device_model_ee . ' ' . $additional_field_ee;



// Если была выбрана опция "Другое", используйте значение из поля "other_service_ru"
if ($service_ru == 'other') {
    $service_ru = $other_service_ru;
}

// Формирование названия страницы
$page_title_ru = $service_ru . ' ' . $device_model_ru . ' ' . $additional_field_ru;



    // Создание страницы на эстонском языке
    $new_page_ee = [
        'post_type' => 'page',
        'post_title' =>  trim($page_title_ee), // Удаление лишних пробелов
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'page_template' => 'page-uslug-est.php'
    ];

    $page_id_ee = wp_insert_post($new_page_ee);
    pll_set_post_language($page_id_ee, 'et'); // Установка языка страницы на эстонский

    // Создание страницы на русском языке
    $new_page_ru = [
        'post_type' => 'page',
        'post_title' =>  trim($page_title_ru), // Удаление лишних пробелов
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'page_template' => 'page-uslug-est.php'
    ];

    $page_id_ru = wp_insert_post($new_page_ru);
    pll_set_post_language($page_id_ru, 'ru'); // Установка языка страницы на русский

    // Проверка успешного создания страниц
    if ($page_id_ee && $page_id_ru) {
        // Связывание страниц
        pll_save_post_translations(array('et' => $page_id_ee, 'ru' => $page_id_ru));

        // Заполнение ACF полей для эстонской версии
        $fields_ee = acf_get_fields('group_628f6d5008b5d');
        if ($fields_ee) {
            foreach ($fields_ee as $field) {
                if (isset($_POST['acf'][$field['key']])) {
                    update_field($field['key'], $_POST['acf'][$field['key']], $page_id_ee);
                }
            }
        }

        // Заполнение ACF полей для русской версии
        $fields_ru = acf_get_fields('group_645ccc6724d12'); // Русская группа полей
        if ($fields_ru) {
            foreach ($fields_ru as $field) {
                if (isset($_POST['acf'][$field['key']])) {
                    update_field($field['key'], $_POST['acf'][$field['key']], $page_id_ru);
                }
            }
        }

        // Запись логов в файл
        file_put_contents(plugin_dir_path(__FILE__) . 'logs/logs-products.txt', 
                          $page_id_ee . '|' . 'et' . '|' . current_time('mysql') . "\n", 
                          FILE_APPEND);

        file_put_contents(plugin_dir_path(__FILE__) . 'logs/logs-products.txt', 
                          $page_id_ru . '|' . 'ru' . '|' . current_time('mysql') . "\n", 
                          FILE_APPEND);

        echo '<div class="notice notice-success">';
        echo '<p>Страницы успешно созданы! Вы можете их отредактировать или просмотреть.</p>';
        echo '<p><a href="' . get_edit_post_link($page_id_ee) . '">Редактировать EE версию</a></p>';
        echo '<p><a href="' . get_permalink($page_id_ee) . '">Просмотреть EE версию</a></p>';
        echo '<p><a href="' . get_edit_post_link($page_id_ru) . '">Редактировать RU версию</a></p>';
        echo '<p><a href="' . get_permalink($page_id_ru) . '">Просмотреть RU версию</a></p>';
        echo '</div>';
    } else {
        echo '<div class="notice notice-error">';
        echo '<p>Не удалось создать страницы. Проверьте введенные данные и попробуйте снова.</p>';
        echo '</div>';
    }
}


?>