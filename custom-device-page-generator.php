<?php
/**
 * Plugin Name: Custom Device Page Generator
 * Description: Генератор страниц для устройств с использованием ACF и шаблона page-serv.php
 * Version: 6.0.0
 * Author: BuyReadySite.com
 * Author URI: https://buyreadysite.com
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Инициализация плагина
function cdpg_init() {
    add_action('admin_menu', 'cdpg_create_menu');
}

add_action('init', 'cdpg_init');

function cdpg_enqueue_scripts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
}
add_action('admin_enqueue_scripts', 'cdpg_enqueue_scripts');

define('CDPG_VERSION', '6.0.0'); // версия вашего плагина
define('CDPG_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Подключение стилей и скриптов в админке
function cdpg_enqueue_admin_scripts() {
    wp_enqueue_style('cdpg-admin-css-service', plugin_dir_url(__FILE__) . 'css/admin-styles-service.css');
	wp_enqueue_style('cdpg-admin-css-main', plugin_dir_url(__FILE__) . 'css/admin-styles.css');
	
    wp_enqueue_script('cdpg-admin-js-service', plugin_dir_url(__FILE__) . 'js/admin-scripts-service.js', array('jquery'), false, true);
	
    wp_enqueue_style('cdpg-admin-css-product', plugin_dir_url(__FILE__) . 'css/admin-styles-product.css');
    wp_enqueue_script('cdpg-admin-js-product', plugin_dir_url(__FILE__) . 'js/admin-scripts-product.js', array('jquery'), false, true);
	
    wp_enqueue_script('cdpg-ajax', plugin_dir_url(__FILE__) . 'js/cdpg-ajax.js', array('jquery'), false, true);
wp_localize_script('cdpg-ajax', 'cdpg_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('cdpg-button-click-nonce')
));

}
add_action('admin_enqueue_scripts', 'cdpg_enqueue_admin_scripts');


require_once plugin_dir_path(__FILE__) . 'service/service-page-generator.php';
require_once plugin_dir_path(__FILE__) . 'product/product-page-generator.php';
require_once plugin_dir_path(__FILE__) . 'bulk/bulk-page-generator.php';
require_once plugin_dir_path(__FILE__) . 'bulk/button-single-generator.php';
require_once plugin_dir_path(__FILE__) . 'bulk/button-bulk-generator.php';
require_once plugin_dir_path(__FILE__) . 'settings.php';

// Создание меню в админ-панели
function cdpg_create_menu() {
    add_menu_page('Генератор страниц для устройств', 'Генератор страниц', 'manage_options', 'custom-device-page-generator', 'cdpg_main_page');
    add_submenu_page('custom-device-page-generator', 'Генератор страниц услуг', 'Генератор страниц услуг', 'manage_options', 'service-page-generator', 'cdpg_service_page');
    add_submenu_page('custom-device-page-generator', 'Генератор страниц товаров', 'Генератор страниц товаров', 'manage_options', 'product-page-generator', 'cdpg_product_page');
	add_submenu_page('custom-device-page-generator', 'Массовая генерация', 'Массовая генерация', 'manage_options', 'bulk-page-generator', 'cdpg_bulk_page');
	add_submenu_page('custom-device-page-generator', 'Настройки', 'Настройки', 'manage_options', 'cdpg_settings', 'cdpg_settings_page');
    add_submenu_page('custom-device-page-generator', 'Логи', 'Логи', 'manage_options', 'logs', 'cdpg_logs_page');
	
}




function cdpg_main_page() {
    // Основная информация и логотип
	echo '<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; margin-right: 20px; box-sizing: border-box; overflow: auto;">';
    echo '<img src="https://buyreadysite.com/wp-content/uploads/2023/03/logo-buyreadysite.com_.svg" alt="BuyReadySite Logo" style="width: 200px; height: auto; display: block; margin-left: auto; margin-right: auto; margin-bottom: 15px;">';
	echo '<h2>Генератор страниц для устройств (версия ' . CDPG_VERSION . ')</h2>';
    echo '<p>Этот плагин разработан компанией <a href="https://buyreadysite.com/" target="_blank">buyreadysite.com</a> в лице Александра Крикуна. Плагин предназначен для генерации страниц услуг и товаров и обладает следующими преимуществами:</p>';
    echo '<ul>';
    echo '<li>Быстрая генерация страниц на основе ваших данных</li>';
    echo '<li>Поддержка различных форматов данных</li>';
    echo '<li>Удобный интерфейс для генерации и управления страницами</li>';
    echo '</ul>';
    echo '</div>';

// Контактная информация
echo '<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; margin-right: 20px; box-sizing: border-box; overflow: auto;">';
echo '<h3>Контакты</h3>';
echo '<ul style="list-style-type: none; padding: 0; display: flex; flex-wrap: wrap; justify-content: center;">';
echo '<li style="margin: 10px;"><a href="tel:+380686851897" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fas fa-phone" style="margin-right: 5px;"></i> +380 (68) 685-18-97</a></li>';
echo '<li style="margin: 10px;"><a href="tel:+380739964959" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fas fa-phone" style="margin-right: 5px;"></i> +38 (073) 996-49-59</a></li>';
echo '<li style="margin: 10px;"><a href="https://t.me/buyreadysite" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fab fa-telegram-plane" style="margin-right: 5px;"></i> Telegram</a></li>';
echo '<li style="margin: 10px;"><a href="https://wa.me/380686851897" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fab fa-whatsapp" style="margin-right: 5px;"></i> WhatsApp</a></li>';
echo '<li style="margin: 10px;"><a href="https://www.facebook.com/BuyReadySite" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fab fa-facebook-f" style="margin-right: 5px;"></i> Facebook</a></li>';
echo '<li style="margin: 10px;"><a href="https://www.facebook.com/ksanyokm/" style="text-decoration: none; color: #000; font-size: 1.2em;"><i class="fab fa-facebook-f" style="margin-right: 5px;"></i> Facebook автора</a></li>';
echo '</ul>';
echo '</div>';


    // Кнопки для генерации страниц
    echo '<div style="display: flex; justify-content: space-between; margin-bottom: 20px;  margin-right: 20px;">';
    echo '<a href="' . admin_url('admin.php?page=service-page-generator') . '" class="button button-primary" style="width: 48%; height: 60px; line-height: 60px; text-align: center; font-size: 20px;">Генератор страниц услуг</a>';
    echo '<a href="' . admin_url('admin.php?page=product-page-generator') . '" class="button button-primary" style="width: 48%; height: 60px; line-height: 60px; text-align: center; font-size: 20px;">Генератор страниц товаров</a>';
    echo '</div>';
	  echo '<div style="display: flex; justify-content: space-between; margin-bottom: 20px;  margin-right: 20px;">';
    echo '<a href="' . admin_url('admin.php?page=bulk-page-generator') . '" class="button button-primary" style="width: 100%; height: 60px; line-height: 60px; text-align: center; font-size: 20px;">Массовая генерация услуг</a>';
    echo '</div>';

    // Логи
    echo '<h3>Последние генерации</h3>';
    $logs_paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
cdpg_logs_page($logs_paged);

}



require_once plugin_dir_path(__FILE__) . 'service/service-page-generator.php';
require_once plugin_dir_path(__FILE__) . 'product/product-page-generator.php';


function cdpg_logs_page($paged = 1, $items_per_page = 20) {
    $logs_services_file = CDPG_PLUGIN_DIR . 'service/logs-services.txt';
    $logs_products_file = CDPG_PLUGIN_DIR . 'product/logs-products.txt';
    $logs_bulk_file = CDPG_PLUGIN_DIR . 'bulk/logs-services.txt';

    if (!file_exists($logs_services_file) || !file_exists($logs_products_file) || !file_exists($logs_bulk_file)) {
        echo 'Файлы логов не найдены.';
        return;
    }

    $logs_services = file($logs_services_file);
    $logs_products = file($logs_products_file);
    $logs_bulk = file($logs_bulk_file);

    $logs = array_merge($logs_services, $logs_products, $logs_bulk);
    $logs = array_reverse($logs); // переворачиваем массив, чтобы более новые логи были в начале
    $total_items = count($logs);
    $total_pages = ceil($total_items / $items_per_page);

    $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $start_offset = ($paged - 1) * $items_per_page;

    $logs = array_slice($logs, $start_offset, $items_per_page);

    if (!empty($logs)) {
        echo '<table>';
        echo '<tr><th>Название страницы</th><th>Язык</th><th>Дата-время</th><th>Ссылка</th><th>Тип</th></tr>';
        foreach ($logs as $log) {
            $log_parts = explode('|', $log);
            echo '<tr>';
            echo '<td><a href="' . get_edit_post_link($log_parts[0]) . '">' . get_the_title($log_parts[0]) . '</a></td>';
            echo '<td>' . $log_parts[1] . '</td>';
            echo '<td>' . $log_parts[2] . '</td>';
            echo '<td><a href="' . get_permalink($log_parts[0]) . '">Просмотреть</a></td>';

            if (in_array($log, $logs_services)) {
                echo '<td>Услуги</td>';
            } elseif (in_array($log, $logs_products)) {
                echo '<td>Товар</td>';
            } else {
                echo '<td>Массовая генерация</td>';
            }

            echo '</tr>';
        }
        echo '</table>';

        $big = 999999999; // уникальное число для замены
        echo '<div class="pagination">';
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(admin_url('admin.php?page=logs&paged=' . $big))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $total_pages
        ));
                echo '</div>';
    } else {
        echo 'Записи логов не найдены.';
    }
}



add_action('admin_menu', 'cdpg_create_menu');