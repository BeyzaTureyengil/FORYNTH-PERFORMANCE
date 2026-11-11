<?php
/**
 * Plugin Name: FORYNTH Custom
 * Description: FORYNTH PERFORMANCE özel geliştirmeleri.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Giriş yapmayan kullanıcıyı online randevu sayfasında login sayfasına yönlendir.
 */
function forynth_protect_booking_page() {

    if (!is_page('online-randevu')) {
        return;
    }

    if (is_user_logged_in()) {
        return;
    }

    $login_url = home_url('/login/');

    wp_safe_redirect($login_url);
    exit;
}
add_action('template_redirect', 'forynth_protect_booking_page');


/**
 * Online randevu sayfasına özel CSS yükle.
 */
function forynth_enqueue_booking_styles() {

    if (!is_page('online-randevu')) {
        return;
    }

    $css_file = plugin_dir_path(__FILE__) . 'assets/css/booking.css';

    wp_enqueue_style(
        'forynth-booking',
        plugin_dir_url(__FILE__) . 'assets/css/booking.css',
        array(),
        file_exists($css_file) ? filemtime($css_file) : '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'forynth_enqueue_booking_styles');


/**
 * Easy Appointments içindeki bazı İngilizce metinleri Türkçeleştir.
 */
function forynth_translate_easyappointments_strings($translated, $text, $domain) {

    $translations = array(
        'THANK YOU FOR BOOKING!' => 'RANDEVU TALEBİNİZ ALINDI!',
        'Thank you for booking!' => 'Randevu talebiniz alındı!',
        'Personal information'   => 'Kişisel Bilgiler',
        'Location'               => 'Konum',
        'Service'                => 'Hizmet',
        'Description'            => 'Açıklama',
        'Date & time'            => 'Tarih ve Saat',
        'Worker'                 => 'Antrenör',
        'Book New Appointment'   => 'Yeni Randevu Oluştur',
        'Add to Google Calendar' => 'Google Takvim’e Ekle',
        'Name'                   => 'Ad Soyad',
        'Email'                  => 'E-posta',
        'Phone'                  => 'Telefon',
        'Comment'                => 'Not',
    );

    if (isset($translations[$text])) {
        return $translations[$text];
    }

    return $translated;
}
add_filter('gettext', 'forynth_translate_easyappointments_strings', 20, 3);

/**
 * Rezervasyon sayfasına Türkçeleştirme
 * JavaScript dosyasını yükle.
 */
function forynth_enqueue_booking_script() {

    if (!is_page('online-randevu')) {
        return;
    }

    $file = plugin_dir_path(__FILE__) .
        'assets/js/booking.js';

    wp_enqueue_script(
        'forynth-booking-js',
        plugin_dir_url(__FILE__) .
            'assets/js/booking.js',
        array(),
        file_exists($file) ? filemtime($file) : '1.0.0',
        true
    );
}

add_action(
    'wp_enqueue_scripts',
    'forynth_enqueue_booking_script'
);

/**
 * Haftalık ders programı modülü.
 */
require_once plugin_dir_path(__FILE__) .
    'includes/class-schedule.php';

function forynth_enqueue_schedule_assets() {

    $css_path = plugin_dir_path(__FILE__) .
        'assets/css/schedule.css';

    $js_path = plugin_dir_path(__FILE__) .
        'assets/js/schedule.js';

    wp_enqueue_style(
        'forynth-schedule',
        plugin_dir_url(__FILE__) . 'assets/css/schedule.css',
        [],
        file_exists($css_path) ? filemtime($css_path) : '1.0.0'
    );

    wp_enqueue_script(
        'forynth-schedule',
        plugin_dir_url(__FILE__) . 'assets/js/schedule.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : '1.0.0',
        true
    );
}

add_action(
    'wp_enqueue_scripts',
    'forynth_enqueue_schedule_assets'
);
