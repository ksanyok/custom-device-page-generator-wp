<?php

function cdpg_settings_init() {
    register_setting('cdpg_settings', 'cdpg_settings');

    // Общие настройки
    add_settings_section(
        'cdpg_general_settings',
        'Общие настройки',
        'cdpg_general_settings_section_callback',
        'cdpg_settings'
    );

    // Настройки генерации товаров
    add_settings_section(
        'cdpg_product_generation_settings',
        'Настройки генерации товаров',
        'cdpg_product_generation_settings_section_callback',
        'cdpg_settings'
    );

    // Добавление полей для настроек генерации товаров
    // Замените 'product_page_template' и прочие на действительные названия полей

add_settings_field(
    'product_page_template',
    'Шаблон страницы товара',
    'cdpg_page_template_field_callback',
    'cdpg_settings',
    'cdpg_product_generation_settings',
    array('label_for' => 'product_page_template')
);


 

 add_settings_field(
        'product_acf_group_estonian',
        'Группа полей ACF для эстонской версии товаров',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_product_generation_settings',
	    array('label_for' => 'product_acf_group_estonian')
    );
    add_settings_field(
        'product_acf_group_russian',
        'Группа полей ACF для русской версии товаров',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_product_generation_settings',
	    array('label_for' => 'product_acf_group_russian')	
    );
	
	
	
	
	// Добавление полей ID заполнителя для эстонской и русской версии
add_settings_field(
    'cdpg_filler_products_id_estonian',
    'ID заполнителя Товаров для Эстонской версии',
    'cdpg_filler_id_field_callback',
    'cdpg_settings',
    'cdpg_product_generation_settings',
    array('label_for' => 'cdpg_filler_products_id_estonian')
);
add_settings_field(
    'cdpg_filler_products_id_russian',
    'ID заполнителя Товаров для Русской версии',
    'cdpg_filler_id_field_callback',
    'cdpg_settings',
    'cdpg_product_generation_settings',
    array('label_for' => 'cdpg_filler_products_id_russian')
);




    // Настройки генерации услуг
    add_settings_section(
        'cdpg_service_generation_settings',
        'Настройки генерации услуг',
        'cdpg_service_generation_settings_section_callback',
        'cdpg_settings'
    );

    // Добавление полей для настроек генерации услуг
    // Замените 'service_page_template' и прочие на действительные названия полей
    add_settings_field(
        'service_page_template',
        'Шаблон страницы услуги',
        'cdpg_page_template_field_callback',
        'cdpg_settings',
        'cdpg_service_generation_settings',
	array('label_for' => 'service_page_template')	
    );
    add_settings_field(
        'service_acf_group_estonian',
        'Группа полей ACF для эстонской версии услуг',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_service_generation_settings',
	array('label_for' => 'service_acf_group_estonian')		
    );
    add_settings_field(
        'service_acf_group_russian',
        'Группа полей ACF для русской версии услуг',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_service_generation_settings',
	array('label_for' => 'service_acf_group_russian')		
    );
	
	
		// Добавление полей ID заполнителя УСЛУГ для эстонской и русской версии
add_settings_field(
    'cdpg_filler_services_id_estonian',
    'ID заполнителя Услуг для Эстонской версии',
    'cdpg_filler_id_field_callback',
    'cdpg_settings',
    'cdpg_service_generation_settings',
    array('label_for' => 'cdpg_filler_services_id_estonian')
);
add_settings_field(
    'cdpg_filler_services_id_russian',
    'ID заполнителя Услуг для Русской версии',
    'cdpg_filler_id_field_callback',
    'cdpg_settings',
    'cdpg_service_generation_settings',
    array('label_for' => 'cdpg_filler_services_id_russian')
);


    // Настройки массовой генерации
    add_settings_section(
        'cdpg_mass_generation_settings',
        'Настройки массовой генерации',
        'cdpg_mass_generation_settings_section_callback',
        'cdpg_settings'
    );

    // Добавление полей для настроек массовой генерации
    // Замените 'mass_page_template' и прочие на действительные названия полей
    add_settings_field(
        'mass_page_template',
        'Шаблон страниц для массовой генерации',
        'cdpg_page_template_field_callback',
        'cdpg_settings',
        'cdpg_mass_generation_settings',
		array('label_for' => 'mass_page_template')	
    );
    add_settings_field(
        'mass_acf_group_estonian',
        'Группа полей ACF для эстонской версии массовой генерации',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_mass_generation_settings',
		array('label_for' => 'mass_acf_group_estonian')	
    );
    add_settings_field(
        'mass_acf_group_russian',
        'Группа полей ACF для русской версии массовой генерации',
        'cdpg_acf_group_field_callback',
        'cdpg_settings',
        'cdpg_mass_generation_settings',
		array('label_for' => 'mass_acf_group_russian')	
    );

}

add_action('admin_init', 'cdpg_settings_init');



// Обратные вызовы для полей ID заполнителя
function cdpg_filler_id_field_callback($args) {
    $options = get_option('cdpg_settings');
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="cdpg_settings[' . esc_attr($args['label_for']) . ']" value="' . esc_attr($value) . '">';
}




function cdpg_general_settings_section_callback() {
    echo '<p>Здесь вы можете настроить общие параметры плагина.</p>';
}

function cdpg_product_generation_settings_section_callback() {
    echo '<p>Здесь вы можете настроить параметры генерации товаров.</p>';
}

function cdpg_service_generation_settings_section_callback() {
    echo '<p>Здесь вы можете настроить параметры генерации услуг.</p>';
}

function cdpg_mass_generation_settings_section_callback() {
    echo '<p>Здесь вы можете настроить параметры массовой генерации.</p>';
}

function cdpg_page_template_field_callback($args) {
    $options = get_option('cdpg_settings');
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

    $templates = get_page_templates();
    echo '<select id="' . esc_attr($args['label_for']) . '" name="cdpg_settings[' . esc_attr($args['label_for']) . ']">';
    foreach ($templates as $template_name => $template_filename) {
        echo '<option value="' . esc_attr($template_filename) . '"' . selected($value, $template_filename, false) . '>' . esc_html($template_name) . '</option>';
    }
    echo '</select>';
}

function cdpg_acf_group_field_callback($args) {
    $options = get_option('cdpg_settings');
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

    $groups = acf_get_field_groups();
    echo '<select id="' . esc_attr($args['label_for']) . '" name="cdpg_settings[' . esc_attr($args['label_for']) . ']">';
    foreach ($groups as $group) {
        echo '<option value="' . esc_attr($group['key']) . '"' . selected($value, $group['key'], false) . '>' . esc_html($group['title']) . '</option>';
    }
    echo '</select>';
}

$cdpg_settings = get_option('cdpg_settings');
$filler_id_estonian = isset($cdpg_settings['cdpg_filler_id_estonian']) ? $cdpg_settings['cdpg_filler_id_estonian'] : 0;
$acf_group_estonian = isset($cdpg_settings['product_acf_group_estonian']) ? $cdpg_settings['product_acf_group_estonian'] : '';


// Эта функция выводит HTML страницы настроек
function cdpg_settings_page() {
    ?>
    <div class="wrap">
        <h1>Настройки плагина Custom Device Page Generator</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('cdpg_settings');
            do_settings_sections('cdpg_settings');
            submit_button('Сохранить настройки');
            ?>
        </form>
    </div>
    <?php
}


?>