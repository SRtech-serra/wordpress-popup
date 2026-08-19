<?php
/**
 * Plugin Name:       Serra Popup
 * Plugin URI:        http://serra.org.tr
 * Description:       Önemli bilgileri ve özel teklifleri göstermek için özelleştirilebilir pop-up eklentisi.
 * Version:           1.3.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            SRtech Serra 🖤
 * Author URI:        http://serra.org.tr
 * Text Domain:       serra-popup
 */

// Doğrudan erişimi engelle
if (!defined('ABSPATH')) {
	exit;
}

// Eklenti etkinleştirildiğinde varsayılan ayarları ekle
register_activation_hook(__FILE__, 'serra_popup_default_options');

function serra_popup_default_options() {
	$default_options = array(
		'active'                  => '1',
		'logo'                    => plugin_dir_url(__FILE__) . 'default-logo.png',
		'background_image'        => plugin_dir_url(__FILE__) . 'default-background.jpg',
		'header_text'             => __('Sitemize Hoş Geldiniz!', 'serra-popup'),
		'description'             => __('Bu, önemli bilgileri göstermek için özelleştirilebilir bir pop-up\'tır.', 'serra-popup'),
		'button_text'             => __('Hemen İncele', 'serra-popup'),
		'button_url'              => '#',
		'button2_text'            => '',
		'button2_url'             => '',
		'close_on_redirect'       => '1',
		'redirect_close_duration' => '3',
		'close_duration_days'     => '3',
		'remind_later_active'     => '1',
		'remind_later_type'       => 'pages',
		'remind_later_value'      => '3',
		'theme_preset'            => 'slate',
		'custom_bg_color'         => '#ffffff',
		'custom_text_color'       => '#0f172a',
		'custom_btn_color'        => '#1e293b',
		'badge_active'            => '0',
		'badge_text'              => '🔥 ' . __('ÖZEL FIRSAT', 'serra-popup'),
		'countdown_active'        => '0',
		'countdown_end_time'      => '',
		'countdown_label'         => __('Teklifin bitmesine kalan süre:', 'serra-popup'),
		'animation_style'         => 'pop',
		'mobile_style'            => 'centered'
	);
	add_option('serra_popup_options', $default_options);
}

// Ön Yüz (Front-End) CSS ve JavaScript dosyalarını yükle
function serra_popup_enqueue_scripts() {
	$options = get_option('serra_popup_options', array());
	if (isset($options['active']) && $options['active'] !== '1') {
		return;
	}

	wp_enqueue_style('serra-popup-css', plugin_dir_url(__FILE__) . 'serra-popup.css', array(), '1.3.0');
	wp_enqueue_script('serra-popup-js', plugin_dir_url(__FILE__) . 'serra-popup.js', array('jquery'), '1.3.0', true);

	$popup_data = array(
		'close_on_redirect'       => isset($options['close_on_redirect']) ? $options['close_on_redirect'] : '1',
		'redirect_close_duration' => isset($options['redirect_close_duration']) ? $options['redirect_close_duration'] : '3',
		'close_duration_days'     => isset($options['close_duration_days']) ? $options['close_duration_days'] : '3',
		'remind_later_active'     => isset($options['remind_later_active']) ? $options['remind_later_active'] : '1',
		'remind_later_type'       => isset($options['remind_later_type']) ? $options['remind_later_type'] : 'pages',
		'remind_later_value'      => isset($options['remind_later_value']) ? $options['remind_later_value'] : '3',
		'countdown_active'        => isset($options['countdown_active']) ? $options['countdown_active'] : '0',
		'countdown_end_time'      => isset($options['countdown_end_time']) ? $options['countdown_end_time'] : '',
		'countdown_label'         => isset($options['countdown_label']) ? $options['countdown_label'] : __('Teklifin bitmesine kalan süre:', 'serra-popup'),
		'animation_style'         => isset($options['animation_style']) ? $options['animation_style'] : 'pop',
		'mobile_style'            => isset($options['mobile_style']) ? $options['mobile_style'] : 'centered',
	);
	wp_localize_script('serra-popup-js', 'serraPopupData', $popup_data);
}
add_action('wp_enqueue_scripts', 'serra_popup_enqueue_scripts');

// Admin Paneli Medya Yükleyici Scriptlerini Yükle
function serra_popup_admin_enqueue_scripts($hook) {
	if ($hook !== 'settings_page_serra_popup') {
		return;
	}
	wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'serra_popup_admin_enqueue_scripts');

// Yönetici menüsüne "Serra Popup Ayarları" ekle
function serra_popup_add_admin_menu() {
	add_options_page(
		__('Serra Popup Ayarları', 'serra-popup'),
		__('Serra Popup', 'serra-popup'),
		'manage_options',
		'serra_popup',
		'serra_popup_options_page'
	);
}
add_action('admin_menu', 'serra_popup_add_admin_menu');

// Ayar sayfasını oluştur
function serra_popup_options_page() {
	if (!current_user_can('manage_options')) {
		return;
	}
	?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?> (v1.3.0)</h1>
        <p><?php esc_html_e('Bu pop-up, önemli bilgileri ve özel teklifleri ziyaretçilere göstermek için kullanılır. Aşağıdaki alanlardan içerik, tema, rozet, geri sayım ve davranış ayarlarını yapılandırın.', 'serra-popup'); ?></p>
        <form action="options.php" method="post" enctype="multipart/form-data">
			<?php
			settings_fields('serra_popup_options_group');
			do_settings_sections('serra_popup');
			submit_button();
			?>
        </form>
    </div>
	<?php
}

// Ayarları kaydetmek için gerekli işlemler
function serra_popup_settings_init() {
	register_setting('serra_popup_options_group', 'serra_popup_options', 'serra_popup_options_validate');

	add_settings_section(
		'serra_popup_settings_section',
		__('Genel İçerik Ayarları', 'serra-popup'),
		'serra_popup_settings_section_cb',
		'serra_popup'
	);

	add_settings_field('serra_popup_active', __('Pop-up Aktif', 'serra-popup'), 'serra_popup_settings_active_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_logo', __('Popup Logo', 'serra-popup'), 'serra_popup_settings_logo_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_background_image', __('Popup Arka Plan Resmi', 'serra-popup'), 'serra_popup_settings_background_image_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_header_text', __('Popup Başlık Metni', 'serra-popup'), 'serra_popup_settings_header_text_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_description', __('Popup Açıklama Metni', 'serra-popup'), 'serra_popup_settings_description_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button_text', __('Buton 1 Metni', 'serra-popup'), 'serra_popup_settings_button_text_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button_url', __('Buton 1 Linki', 'serra-popup'), 'serra_popup_settings_button_url_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button2_text', __('Buton 2 Metni', 'serra-popup'), 'serra_popup_settings_button2_text_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button2_url', __('Buton 2 Linki', 'serra-popup'), 'serra_popup_settings_button2_url_cb', 'serra_popup', 'serra_popup_settings_section');

	add_settings_section(
		'serra_popup_design_section',
		__('Tasarım ve Görünüm Ayarları', 'serra-popup'),
		'serra_popup_design_section_cb',
		'serra_popup'
	);

	add_settings_field('serra_popup_theme_preset', __('Tema Şablonu', 'serra-popup'), 'serra_popup_settings_theme_preset_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_custom_colors', __('Özel Renkler (Özel Tema Seçildiğinde)', 'serra-popup'), 'serra_popup_settings_custom_colors_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_badge_active', __('Duyuru Rozeti (Badge)', 'serra-popup'), 'serra_popup_settings_badge_active_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_badge_text', __('Rozet Metni', 'serra-popup'), 'serra_popup_settings_badge_text_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_countdown_active', __('Geri Sayım Sayacı (Countdown)', 'serra-popup'), 'serra_popup_settings_countdown_active_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_countdown_end_time', __('Geri Sayım Bitiş Tarihi', 'serra-popup'), 'serra_popup_settings_countdown_end_time_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_countdown_label', __('Geri Sayım Başlığı', 'serra-popup'), 'serra_popup_settings_countdown_label_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_animation_style', __('Açılış Animasyonu', 'serra-popup'), 'serra_popup_settings_animation_style_cb', 'serra_popup', 'serra_popup_design_section');
	add_settings_field('serra_popup_mobile_style', __('Mobil Görünüm Stili', 'serra-popup'), 'serra_popup_settings_mobile_style_cb', 'serra_popup', 'serra_popup_design_section');

	add_settings_section(
		'serra_popup_behavior_section',
		__('Gösterim ve Davranış Ayarları', 'serra-popup'),
		'serra_popup_behavior_section_cb',
		'serra_popup'
	);

	add_settings_field('serra_popup_close_on_redirect', __('Yönlendirme Sonrası Kapatılsın mı?', 'serra-popup'), 'serra_popup_settings_close_on_redirect_cb', 'serra_popup', 'serra_popup_behavior_section');
	add_settings_field('serra_popup_redirect_close_duration', __('Yönlendirme Sonrası Gizleme Süresi (Gün)', 'serra-popup'), 'serra_popup_settings_redirect_close_duration_cb', 'serra_popup', 'serra_popup_behavior_section');
	add_settings_field('serra_popup_close_duration_days', __('Kapat (X) Butonu Gizleme Süresi (Gün)', 'serra-popup'), 'serra_popup_settings_close_duration_days_cb', 'serra_popup', 'serra_popup_behavior_section');
	add_settings_field('serra_popup_remind_later_active', __('"Daha Sonra Hatırlat" Butonu Aktif mi?', 'serra-popup'), 'serra_popup_settings_remind_later_active_cb', 'serra_popup', 'serra_popup_behavior_section');
	add_settings_field('serra_popup_remind_later_type', __('"Daha Sonra Hatırlat" Süre Tipi', 'serra-popup'), 'serra_popup_settings_remind_later_type_cb', 'serra_popup', 'serra_popup_behavior_section');
	add_settings_field('serra_popup_remind_later_value', __('"Daha Sonra Hatırlat" Süre/Miktar Değeri', 'serra-popup'), 'serra_popup_settings_remind_later_value_cb', 'serra_popup', 'serra_popup_behavior_section');
}
add_action('admin_init', 'serra_popup_settings_init');

function serra_popup_settings_section_cb() {
	echo '<p>' . esc_html__('Pop-up\'ın ana içerik elemanlarını ayarlayın.', 'serra-popup') . '</p>';
}

function serra_popup_design_section_cb() {
	echo '<p>' . esc_html__('Pop-up renk temasını, rozeti, canlı geri sayım sayacını, animasyon stilini ve mobil kart görünümünü ayarlayın.', 'serra-popup') . '</p>';
}

function serra_popup_behavior_section_cb() {
	echo '<p>' . esc_html__('Pop-up buton yönlendirme, kapatma ve erteleme hafızası kurallarını belirleyin.', 'serra-popup') . '</p>';
}

function serra_popup_options_validate($input) {
	$valid = array();

	$valid['active']           = isset($input['active']) ? '1' : '0';
	$valid['logo']             = esc_url_raw($input['logo'] ?? '');
	$valid['background_image'] = esc_url_raw($input['background_image'] ?? '');
	$valid['header_text']      = sanitize_text_field($input['header_text'] ?? '');
	$valid['description']      = sanitize_textarea_field($input['description'] ?? '');
	$valid['button_text']      = sanitize_text_field($input['button_text'] ?? '');
	$valid['button_url']       = esc_url_raw($input['button_url'] ?? '');
	$valid['button2_text']     = sanitize_text_field($input['button2_text'] ?? '');
	$valid['button2_url']      = esc_url_raw($input['button2_url'] ?? '');

	// Tasarım doğrulamaları
	$allowed_themes        = array('slate', 'neon', 'emerald', 'clean', 'custom');
	$valid['theme_preset'] = in_array($input['theme_preset'] ?? 'slate', $allowed_themes, true) ? $input['theme_preset'] : 'slate';

	$valid['custom_bg_color']   = sanitize_hex_color($input['custom_bg_color'] ?? '#ffffff') ?: '#ffffff';
	$valid['custom_text_color'] = sanitize_hex_color($input['custom_text_color'] ?? '#0f172a') ?: '#0f172a';
	$valid['custom_btn_color']  = sanitize_hex_color($input['custom_btn_color'] ?? '#1e293b') ?: '#1e293b';

	$valid['badge_active'] = isset($input['badge_active']) ? '1' : '0';
	$valid['badge_text']   = sanitize_text_field($input['badge_text'] ?? '');

	$valid['countdown_active']   = isset($input['countdown_active']) ? '1' : '0';
	$valid['countdown_end_time'] = sanitize_text_field($input['countdown_end_time'] ?? '');
	$valid['countdown_label']    = sanitize_text_field($input['countdown_label'] ?? '');

	$allowed_anims            = array('pop', 'slide_up', 'fade', 'bounce');
	$valid['animation_style'] = in_array($input['animation_style'] ?? 'pop', $allowed_anims, true) ? $input['animation_style'] : 'pop';

	$allowed_mobiles       = array('centered', 'bottom_sheet');
	$valid['mobile_style'] = in_array($input['mobile_style'] ?? 'centered', $allowed_mobiles, true) ? $input['mobile_style'] : 'centered';

	// Davranış doğrulamaları
	$valid['close_on_redirect']       = isset($input['close_on_redirect']) ? '1' : '0';
	$valid['redirect_close_duration'] = absint($input['redirect_close_duration'] ?? 3);
	$valid['close_duration_days']     = absint($input['close_duration_days'] ?? 3);
	$valid['remind_later_active']     = isset($input['remind_later_active']) ? '1' : '0';

	$allowed_types             = array('pages', 'hours', 'days');
	$valid['remind_later_type']  = in_array($input['remind_later_type'] ?? 'pages', $allowed_types, true) ? $input['remind_later_type'] : 'pages';
	$valid['remind_later_value'] = absint($input['remind_later_value'] ?? 3);

	return $valid;
}

// Popup HTML yapısını footer'a ekle
function serra_popup_html() {
	$options = get_option('serra_popup_options');

	if (!$options || !is_array($options)) {
		return;
	}

	if (($options['active'] ?? '0') !== '1') {
		return;
	}

	$theme_preset        = $options['theme_preset'] ?? 'slate';
	$animation_style     = $options['animation_style'] ?? 'pop';
	$mobile_style        = $options['mobile_style'] ?? 'centered';
	$badge_active        = $options['badge_active'] ?? '0';
	$badge_text          = $options['badge_text'] ?? '';
	$countdown_active    = $options['countdown_active'] ?? '0';
	$countdown_label     = $options['countdown_label'] ?? __('Teklifin bitmesine kalan süre:', 'serra-popup');
	$remind_later_active = $options['remind_later_active'] ?? '1';

	$container_classes = 'serra-popup-container' . ($mobile_style === 'bottom_sheet' ? ' mobile-bottom-sheet' : '');
	$content_classes   = 'serra-popup-content theme-' . esc_attr($theme_preset) . ' anim-' . esc_attr($animation_style);

	$custom_inline_style = '';
	if ($theme_preset === 'custom') {
		$bg                  = esc_attr($options['custom_bg_color'] ?? '#ffffff');
		$color               = esc_attr($options['custom_text_color'] ?? '#0f172a');
		$custom_inline_style = 'style="background-color: ' . $bg . '; color: ' . $color . ';"';
	}

	?>
    <div id="serraPopup" class="<?php echo esc_attr($container_classes); ?>">
        <div class="<?php echo esc_attr($content_classes); ?>" style="background-image: url('<?php echo esc_url($options['background_image'] ?? ''); ?>');" <?php echo $custom_inline_style; ?>>
			<?php if ($badge_active === '1' && !empty($badge_text)): ?>
                <div class="serra-popup-badge"><?php echo esc_html($badge_text); ?></div>
			<?php endif; ?>

			<?php if (!empty($options['logo'])): ?>
                <img src="<?php echo esc_url($options['logo']); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="serra-popup-logo" />
			<?php else: ?>
                <h2><?php echo esc_html(get_bloginfo('name')); ?></h2>
			<?php endif; ?>

            <h1><?php echo esc_html($options['header_text'] ?? ''); ?></h1>
            <p><?php echo esc_html($options['description'] ?? ''); ?></p>

			<?php if ($countdown_active === '1'): ?>
                <div class="serra-popup-countdown" id="serraPopupCountdown">
                    <div class="serra-countdown-label"><?php echo esc_html($countdown_label); ?></div>
                    <div class="serra-countdown-timer">
                        <div class="serra-timer-unit"><span class="serra-timer-val" id="serraTimerDays">00</span><span class="serra-timer-unit-label"><?php esc_html_e('Gün', 'serra-popup'); ?></span></div>
                        <div class="serra-timer-colon">:</div>
                        <div class="serra-timer-unit"><span class="serra-timer-val" id="serraTimerHours">00</span><span class="serra-timer-unit-label"><?php esc_html_e('Saat', 'serra-popup'); ?></span></div>
                        <div class="serra-timer-colon">:</div>
                        <div class="serra-timer-unit"><span class="serra-timer-val" id="serraTimerMinutes">00</span><span class="serra-timer-unit-label"><?php esc_html_e('Dakika', 'serra-popup'); ?></span></div>
                        <div class="serra-timer-colon">:</div>
                        <div class="serra-timer-unit"><span class="serra-timer-val" id="serraTimerSeconds">00</span><span class="serra-timer-unit-label"><?php esc_html_e('Saniye', 'serra-popup'); ?></span></div>
                    </div>
                </div>
			<?php endif; ?>

            <div class="serra-popup-btns">
				<?php if (!empty($options['button_text']) && !empty($options['button_url'])): ?>
                    <a href="<?php echo esc_url($options['button_url']); ?>" class="serra-popup-btn primary">
						<?php echo esc_html($options['button_text']); ?>
                    </a>
				<?php endif; ?>
				<?php if (!empty($options['button2_text']) && !empty($options['button2_url'])): ?>
                    <a href="<?php echo esc_url($options['button2_url']); ?>" class="serra-popup-btn secondary">
						<?php echo esc_html($options['button2_text']); ?>
                    </a>
				<?php endif; ?>
            </div>

			<?php if ($remind_later_active === '1'): ?>
                <div class="serra-popup-actions">
                    <button id="serraPopupRemindLater" class="serra-popup-link"><?php esc_html_e('Daha sonra hatırlat', 'serra-popup'); ?></button>
                </div>
			<?php endif; ?>
            <button id="serraPopupClose" aria-label="<?php esc_attr_e('Kapat', 'serra-popup'); ?>">×</button>
        </div>
    </div>
	<?php
}
add_action('wp_footer', 'serra_popup_html');

// Ayar alanları callback fonksiyonları
function serra_popup_settings_active_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['active'] ?? '1';
	echo '<input type="checkbox" id="serra_popup_active" name="serra_popup_options[active]" value="1"' . checked($val, '1', false) . ' />';
}

function serra_popup_settings_logo_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['logo'] ?? '';
	echo '<input type="text" id="serra_popup_logo" name="serra_popup_options[logo]" value="' . esc_attr($val) . '" class="regular-text" />';
	echo ' <button class="upload_image_button button">' . esc_html__('Logo Yükle', 'serra-popup') . '</button>';
}

function serra_popup_settings_background_image_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['background_image'] ?? '';
	echo '<input type="text" id="serra_popup_background_image" name="serra_popup_options[background_image]" value="' . esc_attr($val) . '" class="regular-text" />';
	echo ' <button class="upload_image_button button">' . esc_html__('Arka Plan Yükle', 'serra-popup') . '</button>';
}

function serra_popup_settings_header_text_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['header_text'] ?? '';
	echo '<input type="text" id="serra_popup_header_text" name="serra_popup_options[header_text]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_description_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['description'] ?? '';
	echo '<textarea id="serra_popup_description" name="serra_popup_options[description]" class="large-text" rows="4">' . esc_textarea($val) . '</textarea>';
}

function serra_popup_settings_button_text_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['button_text'] ?? '';
	echo '<input type="text" id="serra_popup_button_text" name="serra_popup_options[button_text]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_button_url_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['button_url'] ?? '';
	echo '<input type="text" id="serra_popup_button_url" name="serra_popup_options[button_url]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_button2_text_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['button2_text'] ?? '';
	echo '<input type="text" id="serra_popup_button2_text" name="serra_popup_options[button2_text]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_button2_url_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['button2_url'] ?? '';
	echo '<input type="text" id="serra_popup_button2_url" name="serra_popup_options[button2_url]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_theme_preset_cb() {
	$options = get_option('serra_popup_options');
	$theme   = $options['theme_preset'] ?? 'slate';
	?>
    <select id="serra_popup_theme_preset" name="serra_popup_options[theme_preset]">
        <option value="slate" <?php selected($theme, 'slate'); ?>>🌙 Slate Glass (<?php esc_html_e('Varsayılan Dark Slate', 'serra-popup'); ?>)</option>
        <option value="neon" <?php selected($theme, 'neon'); ?>>🔮 Neon Purple (<?php esc_html_e('Mor Degrade', 'serra-popup'); ?>)</option>
        <option value="emerald" <?php selected($theme, 'emerald'); ?>>🍃 Emerald Luxury (<?php esc_html_e('Zümrüt Lüks', 'serra-popup'); ?>)</option>
        <option value="clean" <?php selected($theme, 'clean'); ?>>☀️ Clean Light (<?php esc_html_e('Aydınlık Sade', 'serra-popup'); ?>)</option>
        <option value="custom" <?php selected($theme, 'custom'); ?>>🎨 <?php esc_html_e('Özel Renk Seçimi', 'serra-popup'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Pop-up genel renk şablonunu seçin.', 'serra-popup'); ?></p>
	<?php
}

function serra_popup_settings_custom_colors_cb() {
	$options = get_option('serra_popup_options');
	$bg      = $options['custom_bg_color'] ?? '#ffffff';
	$text    = $options['custom_text_color'] ?? '#0f172a';
	$btn     = $options['custom_btn_color'] ?? '#1e293b';
	?>
    <label><?php esc_html_e('Kart Arka Plan:', 'serra-popup'); ?> <input type="color" name="serra_popup_options[custom_bg_color]" value="<?php echo esc_attr($bg); ?>" /></label> &nbsp;&nbsp;
    <label><?php esc_html_e('Metin Rengi:', 'serra-popup'); ?> <input type="color" name="serra_popup_options[custom_text_color]" value="<?php echo esc_attr($text); ?>" /></label> &nbsp;&nbsp;
    <label><?php esc_html_e('Ana Buton Rengi:', 'serra-popup'); ?> <input type="color" name="serra_popup_options[custom_btn_color]" value="<?php echo esc_attr($btn); ?>" /></label>
    <p class="description"><?php esc_html_e('Tema Şablonu "Özel Renk Seçimi" olarak belirlendiğinde geçerlidir.', 'serra-popup'); ?></p>
	<?php
}

function serra_popup_settings_badge_active_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['badge_active'] ?? '0';
	echo '<input type="checkbox" id="serra_popup_badge_active" name="serra_popup_options[badge_active]" value="1"' . checked($val, '1', false) . ' />';
	echo '<p class="description">' . esc_html__('Pop-up başlığının üzerinde dikkat çekici rozet (etiket) gösterilsin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_badge_text_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['badge_text'] ?? '🔥 ÖZEL FIRSAT';
	echo '<input type="text" id="serra_popup_badge_text" name="serra_popup_options[badge_text]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_countdown_active_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['countdown_active'] ?? '0';
	echo '<input type="checkbox" id="serra_popup_countdown_active" name="serra_popup_options[countdown_active]" value="1"' . checked($val, '1', false) . ' />';
	echo '<p class="description">' . esc_html__('Pop-up içine canlı geri sayım sayacı eklensin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_countdown_end_time_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['countdown_end_time'] ?? '';
	echo '<input type="datetime-local" id="serra_popup_countdown_end_time" name="serra_popup_options[countdown_end_time]" value="' . esc_attr($val) . '" />';
	echo '<p class="description">' . esc_html__('Geri sayım sayacının sıfırlanacağı bitiş tarihi ve saati.', 'serra-popup') . '</p>';
}

function serra_popup_settings_countdown_label_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['countdown_label'] ?? __('Teklifin bitmesine kalan süre:', 'serra-popup');
	echo '<input type="text" id="serra_popup_countdown_label" name="serra_popup_options[countdown_label]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function serra_popup_settings_animation_style_cb() {
	$options = get_option('serra_popup_options');
	$anim    = $options['animation_style'] ?? 'pop';
	?>
    <select id="serra_popup_animation_style" name="serra_popup_options[animation_style]">
        <option value="pop" <?php selected($anim, 'pop'); ?>><?php esc_html_e('Büyüyerek Açılma (Pop/Scale - Varsayılan)', 'serra-popup'); ?></option>
        <option value="slide_up" <?php selected($anim, 'slide_up'); ?>><?php esc_html_e('Aşağıdan Yukarı Kayma (Slide Up)', 'serra-popup'); ?></option>
        <option value="fade" <?php selected($anim, 'fade'); ?>><?php esc_html_e('Yavaşça Belirme (Fade In)', 'serra-popup'); ?></option>
        <option value="bounce" <?php selected($anim, 'bounce'); ?>><?php esc_html_e('Zıplayarak Açılma (Bounce)', 'serra-popup'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Pop-up ekrana ilk geldiğinde çalışacak animasyon.', 'serra-popup'); ?></p>
	<?php
}

function serra_popup_settings_mobile_style_cb() {
	$options = get_option('serra_popup_options');
	$mobile  = $options['mobile_style'] ?? 'centered';
	?>
    <select id="serra_popup_mobile_style" name="serra_popup_options[mobile_style]">
        <option value="centered" <?php selected($mobile, 'centered'); ?>><?php esc_html_e('Ortalanmış Pop-up (Varsayılan)', 'serra-popup'); ?></option>
        <option value="bottom_sheet" <?php selected($mobile, 'bottom_sheet'); ?>><?php esc_html_e('Mobil Alt Kart (Bottom Sheet)', 'serra-popup'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Mobil cihazlarda pop-up\'ın ekranın altında kart şeklinde mi yoksa ortada mı duracağını belirler.', 'serra-popup'); ?></p>
	<?php
}

function serra_popup_settings_close_on_redirect_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['close_on_redirect'] ?? '1';
	echo '<input type="checkbox" id="serra_popup_close_on_redirect" name="serra_popup_options[close_on_redirect]" value="1"' . checked($val, '1', false) . ' />';
	echo '<p class="description">' . esc_html__('Pop-up üzerindeki butonlara tıklandığında pop-up otomatik kapatılsın ve tekrar gösterilmesin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_redirect_close_duration_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['redirect_close_duration'] ?? '3';
	echo '<input type="number" min="1" max="365" id="serra_popup_redirect_close_duration" name="serra_popup_options[redirect_close_duration]" value="' . esc_attr($val) . '" class="small-text" />';
	echo ' ' . esc_html__('gün', 'serra-popup') . ' <p class="description">' . esc_html__('Yönlendirme butonlarına tıklandıktan sonra kaç gün boyunca pop-up gösterilmesin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_close_duration_days_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['close_duration_days'] ?? '3';
	echo '<input type="number" min="1" max="365" id="serra_popup_close_duration_days" name="serra_popup_options[close_duration_days]" value="' . esc_attr($val) . '" class="small-text" />';
	echo ' ' . esc_html__('gün', 'serra-popup') . ' <p class="description">' . esc_html__('Ziyaretçi pop-up\'ı kapat (X) butonu ile kapattığında kaç gün gösterilmesin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_remind_later_active_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['remind_later_active'] ?? '1';
	echo '<input type="checkbox" id="serra_popup_remind_later_active" name="serra_popup_options[remind_later_active]" value="1"' . checked($val, '1', false) . ' />';
	echo '<p class="description">' . esc_html__('Pop-up altında "Daha sonra hatırlat" seçeneği gösterilsin.', 'serra-popup') . '</p>';
}

function serra_popup_settings_remind_later_type_cb() {
	$options = get_option('serra_popup_options');
	$type    = $options['remind_later_type'] ?? 'pages';
	?>
    <select id="serra_popup_remind_later_type" name="serra_popup_options[remind_later_type]">
        <option value="pages" <?php selected($type, 'pages'); ?>><?php esc_html_e('Sayfa Gezinmesi (Adet)', 'serra-popup'); ?></option>
        <option value="hours" <?php selected($type, 'hours'); ?>><?php esc_html_e('Saat', 'serra-popup'); ?></option>
        <option value="days" <?php selected($type, 'days'); ?>><?php esc_html_e('Gün', 'serra-popup'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('"Daha sonra hatırlat" tıklandığında erteleme türü.', 'serra-popup'); ?></p>
	<?php
}

function serra_popup_settings_remind_later_value_cb() {
	$options = get_option('serra_popup_options');
	$val     = $options['remind_later_value'] ?? '3';
	echo '<input type="number" min="1" max="365" id="serra_popup_remind_later_value" name="serra_popup_options[remind_later_value]" value="' . esc_attr($val) . '" class="small-text" />';
	echo '<p class="description">' . esc_html__('Erteleme türüne göre değer (örn: 3 sayfa, 6 saat, 1 gün).', 'serra-popup') . '</p>';
}

// Medya yükleme işlevi için JavaScript (Sadece admin panelinde çalışır)
function serra_popup_media_uploader_script() {
	$screen = get_current_screen();
	if (!$screen || $screen->id !== 'settings_page_serra_popup') {
		return;
	}
	?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('.upload_image_button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: '<?php echo esc_js(__('Resim Yükle', 'serra-popup')); ?>',
                    button: {
                        text: '<?php echo esc_js(__('Seç', 'serra-popup')); ?>'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    button.prev('input').val(attachment.url);
                });

                mediaUploader.open();
            });
        });
    </script>
	<?php
}
add_action('admin_footer', 'serra_popup_media_uploader_script');
