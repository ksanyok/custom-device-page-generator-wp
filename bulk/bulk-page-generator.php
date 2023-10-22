<?php


function cdpg_bulk_page() {
    echo '<h1>Массовая генерация услуг</h1>';
     
	 
// Добавим стили для таблицы и пагинации
echo '

	<div id="cdpg-modal">
        <div id="cdpg-modal-content">
            <span id="cdpg-modal-close">&times;</span>
            <p>Some text in the Modal..</p>
        </div>
    </div>

    <script>
        var modal = document.getElementById("cdpg-modal");
        var closeButton = document.getElementById("cdpg-modal-close");

        closeButton.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
';


	
$paged = (isset($_GET['paged'])) ? absint($_GET['paged']) : 1;


$s = (isset($_GET['s'])) ? sanitize_text_field($_GET['s']) : '';
$posts_per_page = (isset($_GET['posts_per_page'])) ? absint($_GET['posts_per_page']) : 15;

$args = array(
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-uslug-est.php',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'lang' => 'et',
    's' => $s
);

    
    $pages = new WP_Query($args);
    






echo '<div class="container">';
	
// Поиск
echo '<form method="get">';
echo '<input type="hidden" name="page" value="bulk-page-generator">';
echo '<input type="text" name="s" placeholder="Поиск по моделям" value="' . (isset($_GET['s']) ? $_GET['s'] : '') . '">';
echo '<input type="hidden" name="posts_per_page" value="' . (isset($_GET['posts_per_page']) ? $_GET['posts_per_page'] : '15') . '">';  // сохраняем количество записей
echo '<input class="submit-btn" type="submit" value="Поиск">';
echo '</form>';

// Выбор количества строк на одной странице
echo '<form method="get">';
echo '<input type="hidden" name="page" value="bulk-page-generator">';
echo '<select name="posts_per_page">';
echo '<option value="10"' . (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == 10 ? ' selected' : '') . '>10 строк</option>';
echo '<option value="20"' . (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == 20 ? ' selected' : '') . '>20 строк</option>';
echo '<option value="30"' . (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == 30 ? ' selected' : '') . '>30 строк</option>';
echo '</select>';
echo '<input type="hidden" name="s" value="' . (isset($_GET['s']) ? $_GET['s'] : '') . '">';  // сохраняем состояние поиска
echo '<input class="submit-btn" type="submit" value="Применить">';
echo '</form>';

echo '</div>';


// Общее количество найденных товаров
$total = $pages->found_posts;
echo '<div class="total-count">Общее количество найденных товаров: <strong>' . $total . '</strong></div>';


    if ($pages->have_posts()) {
        echo '<table>';
        echo '<tr><th>Название страницы на эстонском</th><th>ID страницы на эстонском</th><th>Название страницы на русском</th><th>ID страницы на русском</th><th>Количество строк в таблице</th><th>Действие</th></tr>';


while ($pages->have_posts()) {
    $pages->the_post();
    $estonian_id = get_the_ID();
    $estonian_title = get_the_title();
    
    $russian_id = apply_filters('wpml_object_id', $estonian_id, 'page', false, 'ru');
    $russian_title = get_the_title($russian_id);
    
    $table_rows_ee = get_field('prise_tab', $estonian_id);  
    $table_rows_ru = get_field('prise_tab', $russian_id);
    
    echo '<tr>';
    echo '<td><a href="' . get_permalink($estonian_id) . '">' . $estonian_title . '</a></td>';
    echo '<td><a href="' . get_edit_post_link($estonian_id) . '">' . $estonian_id . '</a></td>';
    echo '<td><a href="' . get_permalink($russian_id) . '">' . $russian_title . '</a></td>';
    echo '<td><a href="' . get_edit_post_link($russian_id) . '">' . $russian_id . '</a></td>';
    echo '<td class="row-count" data-post-id="' . $estonian_id . '">' . count($table_rows_ee) . '<span class="expand-rows">+</span></td>';
	echo '<td><button id="generate-all-' . $estonian_id . '">Генерировать все услуги</button></td>';

	
	/* echo '<td><button id="generate-all-' . $estonian_id . '">Генерировать все услуги</button></td>';  */

    echo '</tr>';
    
    if ($table_rows_ee && $table_rows_ru) {
        echo '<tr class="sub-row" data-post-id="' . $estonian_id . '" style="display: none;">';
        echo '<td colspan="6">';
        echo '<table style="width: 100%;">'; 
        echo '<thead><tr><th>Название услуги (EE)</th><th>Название услуги (RU)</th><th>Действие</th></tr></thead>'; 
        for ($i = 0; $i < count($table_rows_ee); $i++) {
    $service_name_ee = $table_rows_ee[$i]['prise_tab_name'];
    $service_name_ru = $table_rows_ru[$i]['prise_tab_name'];
    
    // Получаем страницы с такими названиями услуг
    $service_page_ee = get_page_by_title($service_name_ee, OBJECT, 'page');
    $service_page_ru = get_page_by_title($service_name_ru, OBJECT, 'page');
    
    echo '<tr>';
    echo '<td style="width: 33%;">';
    // Если страница с таким названием услуги существует, делаем название ссылкой
    if ($service_page_ee) {
        echo '<a href="' . get_permalink($service_page_ee->ID) . '">' . $service_name_ee . '</a>';
    } else {
        echo $service_name_ee;
    }
    echo '</td>';
    
    echo '<td style="width: 33%;">';
    if ($service_page_ru) {
        echo '<a href="' . get_permalink($service_page_ru->ID) . '">' . $service_name_ru . '</a>';
    } else {
        echo $service_name_ru;
    }
    echo '</td>';
	
    echo '<td style="width: 33%;">';
    // Если страница с таким названием услуги существует, блокируем кнопку
    if ($service_page_ee || $service_page_ru) {
        echo '<button class="generate-single-service-button" data-id="' . $estonian_id . '" data-row-id="' . $i . '" disabled>Генерировать услугу</button>';
    } else {
        echo '<button class="generate-single-service-button" data-id="' . $estonian_id . '" data-row-id="' . $i . '">Генерировать услугу</button>';
    }
    echo '</td>';

    echo '</tr>';
}
        echo '</table>';  
        echo '</td>';
        echo '</tr>';
    }
}


        echo '</table>';
      
// Поместите эту часть после цикла
// Выводим пагинацию
$big = 999999999;
echo '<div class="pagination">';
echo paginate_links(array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?paged=%#%',
    'current' => $paged,
    'total' => $pages->max_num_pages
));
echo '</div>';
	  
        wp_reset_postdata();
    } else {
        echo 'Страницы с шаблоном page-uslug-est.php не найдены.';
    }

}


function get_table_rows() {
    $post_id = $_POST['post_id'];
    $table_rows = get_field('field_628f6d500bf90', $post_id); 

    $data = array();
    foreach ($table_rows as $row) {
        $value = $row['field_628f6d50496f1'];
        if ($value != 'field_628f6d50496f1') {
            $data[] = $value;
        }
    }

    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_my_action', 'get_table_rows');
add_action('wp_ajax_nopriv_my_action', 'get_table_rows');


function cdpg_generate_all() {
    $post_id = $_POST['post_id'];
    include 'button-bulk-generator.php';
    button_bulk_generator($post_id);
    wp_die(); // Завершаем выполнение скрипта
}
add_action('wp_ajax_generate_all', 'cdpg_generate_all');


function cdpg_generate_single_service() {
    // проверка nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'cdpg-button-click-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
        return;
    }

    // проверка postId
    if ( ! isset( $_POST['post_id'] ) ) {
        wp_send_json_error( 'No post ID provided' );
        return;
    }

    // проверка rowId
    if ( ! isset( $_POST['row_id'] ) ) {
        wp_send_json_error( 'No row ID provided' );
        return;
    }

    // обработка postId и rowId
    $postId = intval( $_POST['post_id'] );
    $rowId = intval( $_POST['row_id'] );  // добавлено извлечение значения row_id

    cdpg_generate_single_service_button($postId, $rowId);  // добавлено передача значения row_id в функцию

    // отправка ответа
    wp_send_json_success( 'Service generated for post ID: ' . $_POST['post_id'] . ', row ID: ' . $_POST['row_id'] );  // изменено сообщение об успехе
}

add_action( 'wp_ajax_cdpg_generate_single_service', 'cdpg_generate_single_service' );
add_action( 'wp_ajax_nopriv_cdpg_generate_single_service', 'cdpg_generate_single_service' );



?>