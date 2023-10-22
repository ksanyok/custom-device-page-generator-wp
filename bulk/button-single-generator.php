<?php




function cdpg_generate_single_service_button($post_id, $row_id) {
    error_log('Inside cdpg_generate_single_service_button function');
    global $wpdb;
    
    // Проверка nonce
    check_ajax_referer('cdpg-button-click-nonce', 'nonce');
	
    // Получить ID страницы на эстонском и русском языках
    $estonian_id = $post_id;
    $russian_id = apply_filters('wpml_object_id', $estonian_id, 'page', false, 'ru');

    // Получить данные таблицы услуг для каждой страницы
    $table_rows_ee = get_field('prise_tab', $estonian_id);  
    $table_rows_ru = get_field('prise_tab', $russian_id);
	
    // Получение идентификатора услуги
    $service_id = intval($post_id);
    $row_id = intval($row_id);  // добавлено преобразование $row_id в число
    
	
    if($service_id !== null && $row_id !== null) {  // проверка на наличие $row_id
        error_log('Service ID and Row ID are not empty');

        // Используйте $table_rows_ru и $table_rows_ee вместо $_POST
        $service_ru = sanitize_text_field($table_rows_ru[$row_id]['prise_tab_name']);  // Изменено на использование данных из таблицы
        $other_service_ru = sanitize_text_field($table_rows_ru[$row_id]['other_service_ru']);
        $device_model_ru = sanitize_text_field($table_rows_ru[$row_id]['device_model_ru']);
        $additional_field_ru = sanitize_text_field($table_rows_ru[$row_id]['additional_field_ru']);

        $service_ee = sanitize_text_field($table_rows_ee[$row_id]['prise_tab_name']);  // Изменено на использование данных из таблицы
        $other_service_ee = sanitize_text_field($table_rows_ee[$row_id]['other_service_ee']);
        $device_model_ee = sanitize_text_field($table_rows_ee[$row_id]['device_model_ee']);
        $additional_field_ee = sanitize_text_field($table_rows_ee[$row_id]['additional_field_ee']);

        if ($service_ee == 'other') {
            $service_ee = $other_service_ee;
        }
        $page_title_ee = $service_ee;

        if ($service_ru == 'other') {
            $service_ru = $other_service_ru;
        }
        $page_title_ru = $service_ru;



$page_exists_ee = get_page_by_title(trim($page_title_ee), OBJECT, 'page');
$page_exists_ru = get_page_by_title(trim($page_title_ru), OBJECT, 'page');

if ($page_exists_ee || $page_exists_ru) {
    echo '<div class="notice notice-warning">';
    echo '<p>Страница(ы) уже существует(ют)! Вы можете их отредактировать или просмотреть.</p>';
    if ($page_exists_ee) {
        echo '<p><a href="' . get_edit_post_link($page_exists_ee->ID) . '">Редактировать EE версию</a></p>';
        echo '<p><a href="' . get_permalink($page_exists_ee->ID) . '">Просмотреть EE версию</a></p>';
    }
    if ($page_exists_ru) {
        echo '<p><a href="' . get_edit_post_link($page_exists_ru->ID) . '">Редактировать RU версию</a></p>';
        echo '<p><a href="' . get_permalink($page_exists_ru->ID) . '">Просмотреть RU версию</a></p>';
    }
    echo '</div>';
    wp_die();
}




        // Создаем новые страницы
        $new_page_ee = [
            'post_type' => 'page',
            'post_title' =>  trim($page_title_ee),
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'page_template' => 'page-uslug-generate.php'
        ];
     /*   $page_id_ee = wp_insert_post($new_page_ee); */

        $new_page_ru = [
            'post_type' => 'page',
            'post_title' =>  trim($page_title_ru),
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'page_template' => 'page-uslug-generate.php'
        ];
    /*    $page_id_ru = wp_insert_post($new_page_ru);  */

        // Получить все поля ACF для новых страниц
        $fields_ee = get_fields($page_id_ee);
		$fields_ee_template = get_fields($estonian_id);
		
		/* $fields_ee_template = get_fields(700); */
		
        // Заполнение полей для эстонской версии
        // После создания новой страницы
$page_id_ee = wp_insert_post($new_page_ee);

foreach ($fields_ee_template as $field_name => $field_value) {
    // Проверяем, является ли значение URL изображения
    if (filter_var($field_value, FILTER_VALIDATE_URL)) {
        // Если это URL изображения, копируем его в медиатеку и обновляем поле изображения
        $image_url = $field_value;
        $tmp = download_url( $image_url );
        if( is_wp_error( $tmp ) ){
            // Если произошла ошибка при загрузке изображения, записываем ее в лог
            error_log('Error downloading image: ' . $tmp->get_error_message());
        } else {
            $file_array = array();
            // Используем имя файла (без расширения) в качестве названия нового поста. Это станет заголовком вложения.
            $file_array['name'] = basename($image_url);
            $file_array['tmp_name'] = $tmp;
            // "Загружаем" изображение в медиатеку. Это просто перемещает его в нужное место и создает запись в базе данных.
            $image_copy_id = media_handle_sideload( $file_array, $page_id_ee );
            if (is_wp_error($image_copy_id)) {
                error_log('Error copying image: ' . $image_copy_id->get_error_message());
            } else {
                error_log('Image copied successfully: ' . $image_copy_id);
                update_field($field_name, $image_copy_id, $page_id_ee);
            }
        }
    } else {
        // Это не поле изображения. Просто обновим поле.
        update_field($field_name, $field_value, $page_id_ee);
    }
}


        pll_set_post_language($page_id_ee, 'et');

        $fields_ru = get_fields($page_id_ru);
		$fields_ru_template = get_fields($russian_id);
		
		/* $fields_ru_template = get_fields(5649);*/
		
        // Заполнение полей для русской версии
        $page_id_ru = wp_insert_post($new_page_ru);

// Заполнение полей ACF для русской версии
foreach ($fields_ru_template as $field_name => $field_value) {
    // Проверяем, является ли значение URL изображения
    if (filter_var($field_value, FILTER_VALIDATE_URL)) {
        // Если это URL изображения, копируем его в медиатеку и обновляем поле изображения
        $image_url = $field_value;
        $tmp = download_url( $image_url );
        if( is_wp_error( $tmp ) ){
            // Если произошла ошибка при загрузке изображения, записываем ее в лог
            error_log('Error downloading image: ' . $tmp->get_error_message());
        } else {
            $file_array = array();
            // Используем имя файла (без расширения) в качестве названия нового поста. Это станет заголовком вложения.
            $file_array['name'] = basename($image_url);
            $file_array['tmp_name'] = $tmp;
            // "Загружаем" изображение в медиатеку. Это просто перемещает его в нужное место и создает запись в базе данных.
            $image_copy_id = media_handle_sideload( $file_array, $page_id_ru );
            if (is_wp_error($image_copy_id)) {
                error_log('Error copying image: ' . $image_copy_id->get_error_message());
            } else {
                error_log('Image copied successfully: ' . $image_copy_id);
                update_field($field_name, $image_copy_id, $page_id_ru);
            }
        }
    } else {
        // Это не поле изображения. Просто обновим поле.
        update_field($field_name, $field_value, $page_id_ru);
    }
}

        pll_set_post_language($page_id_ru, 'ru');

        // Связываем страницы вместе
        if ($page_id_ee && $page_id_ru) {
            pll_save_post_translations(array('et' => $page_id_ee, 'ru' => $page_id_ru));

            $fields_ee = acf_get_fields('group_628f6d5008b5d');
            if ($fields_ee) {
                foreach ($fields_ee as $field) {
                    if (isset ($table_rows_ee['acf'][$field['key']])) {
                        update_field($field['key'], $table_rows_ee['acf'][$field['key']], $page_id_ee);
                    }
                }
            }

            $fields_ru = acf_get_fields('group_645ccc6724d12'); // Русская группа полей
            if ($fields_ru) {
                foreach ($fields_ru as $field) {
                    if (isset($table_rows_ru['acf'][$field['key']])) {
                        update_field($field['key'], $table_rows_ru['acf'][$field['key']], $page_id_ru);
                    }
                }
            }

            // Запись логов в файл
            file_put_contents(plugin_dir_path(__FILE__) . 'logs-services.txt', 
                            $page_id_ee . '|' . 'et' . '|' . current_time('mysql') . "\n", 
                            FILE_APPEND);

            file_put_contents(plugin_dir_path(__FILE__) . 'logs-services.txt', 
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
    } else {
        error_log('Service ID or Row ID is empty');
    }

    wp_die();
}


?>