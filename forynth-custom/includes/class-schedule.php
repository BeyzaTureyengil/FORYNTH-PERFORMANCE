
<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * FORYNTH haftalık ders programı.
 * Saat ve salon bilgileri örnektir.
 */

function forynth_schedule_lessons() {

    $program = [
        [
            'days' => [1, 3, 5],
            'time' => '17:00',
            'end' => '18:00',
            'name' => 'Grup Fitness',
            'trainer' => 'Ryan James',
            'room' => 'Grup Ders Alanı'
        ],
        [
            'days' => [1, 3, 5],
            'time' => '18:00',
            'end' => '19:00',
            'name' => 'Kardiyo ve Kondisyon',
            'trainer' => 'Ryan James',
            'room' => 'Kardiyo Alanı'
        ],
        [
            'days' => [2, 4, 6],
            'time' => '10:00',
            'end' => '11:00',
            'name' => 'Pilates',
            'trainer' => 'Frida Neason',
            'room' => 'Pilates Stüdyosu'
        ],
        [
            'days' => [1, 3, 5],
            'time' => '12:00',
            'end' => '13:00',
            'name' => 'Esneklik ve Mobilite',
            'trainer' => 'Angellica Parker',
            'room' => 'Pilates Stüdyosu'
        ],
        [
            'days' => [1, 3, 5],
            'time' => '09:00',
            'end' => '10:00',
            'name' => 'Kuvvet Antrenmanı',
            'trainer' => 'James Foster',
            'room' => 'Fitness Alanı'
        ],
        [
            'days' => [2, 4, 6],
            'time' => '14:00',
            'end' => '15:00',
            'name' => 'Bireysel PT',
            'trainer' => 'Brandon Grey',
            'room' => 'Fitness Alanı'
        ]
    ];

    $lessons = [];

    foreach ($program as $item) {
        foreach ($item['days'] as $day) {

            $lessons[] = [
                'day' => $day,
                'time' => $item['time'],
                'end' => $item['end'],
                'name' => $item['name'],
                'trainer' => $item['trainer'],
                'room' => $item['room']
            ];
        }
    }

    usort($lessons, function ($a, $b) {
        return ($a['day'] <=> $b['day'])
            ?: strcmp($a['time'], $b['time']);
    });

    return $lessons;
}


function forynth_render_schedule() {
    $days = [
        1 => 'Pazartesi',
        2 => 'Salı',
        3 => 'Çarşamba',
        4 => 'Perşembe',
        5 => 'Cuma',
        6 => 'Cumartesi',
        7 => 'Pazar'
    ];

    $lessons = forynth_schedule_lessons();

    $trainers = array_unique(
        array_column($lessons, 'trainer')
    );

    $booking_url = home_url('/online-randevu/');

    ob_start();
    ?>

    <section class="fp-schedule" aria-label="Haftalık ders programı">

        <div class="fp-schedule-header">
            <span class="fp-eyebrow">FORYNTH PERFORMANCE</span>
            <h2>HAFTALIK DERS PROGRAMI</h2>
            <p>
                Hedefine uygun dersi keşfet,
                antrenörünü seç ve antrenmanını planla.
            </p>
        </div>

        <div class="fp-filters">

            <label>
                Gün Seç
                <select class="fp-day-filter">
                    <option value="all">Tüm Günler</option>
                    <?php foreach ($days as $id => $name): ?>
                        <option value="<?php echo esc_attr($id); ?>">
                            <?php echo esc_html($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Antrenör Seç
                <select class="fp-trainer-filter">
                    <option value="all">Tüm Antrenörler</option>
                    <?php foreach ($trainers as $trainer): ?>
                        <option value="<?php echo esc_attr($trainer); ?>">
                            <?php echo esc_html($trainer); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

        </div>

        <div class="fp-schedule-grid">

            <?php foreach ($lessons as $lesson): ?>
                <article
                    class="fp-lesson"
                    data-day="<?php echo esc_attr($lesson['day']); ?>"
                    data-trainer="<?php echo esc_attr($lesson['trainer']); ?>"
                >
                    <div class="fp-lesson-top">
                        <span>
                            <?php echo esc_html($days[$lesson['day']]); ?>
                        </span>
                        <strong>
                            <?php
                            echo esc_html(
                                $lesson['time'] . ' - ' . $lesson['end']
                            );
                            ?>
                        </strong>
                    </div>

                    <h3><?php echo esc_html($lesson['name']); ?></h3>

                    <p>
                        Antrenör:
                        <strong><?php echo esc_html($lesson['trainer']); ?></strong>
                    </p>

                    <p>
                        Salon:
                        <strong><?php echo esc_html($lesson['room']); ?></strong>
                    </p>

                    <a class="fp-book-btn"
                       href="<?php echo esc_url($booking_url); ?>">
                        RANDEVU AL
                    </a>
                </article>
            <?php endforeach; ?>

        </div>

        <p class="fp-empty" role="status" hidden>
            Seçtiğiniz filtrelere uygun ders bulunamadı.
        </p>

        <p class="fp-schedule-note">
            Ders programı bilgilendirme amaçlıdır.
            Güncel müsaitlik ve rezervasyon saatleri
            randevu ekranında kontrol edilir.
        </p>
    </section>

    <?php
    return ob_get_clean();
}

add_shortcode('forynth_schedule', 'forynth_render_schedule');
