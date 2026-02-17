<?php
/**
 * Plugin Name: Serra Popup
 * Plugin URI:  http://serra.org.tr
 * Description: Önemli bilgileri göstermek için özelleştirilebilir popup.
 * Version:     1.1.0
 * Author: SRtech Serra 🖤
 * Author URI:  http://serra.org.tr
 */

// Eklenti etkinleştirildiğinde varsayılan ayarları ekle
register_activation_hook(__FILE__, 'serra_popup_default_options');

function serra_popup_default_options() {
	$default_options = array(
		'active' => '1', // Popup aktif varsayılan
		'logo' => plugins_url('default-logo.png', __FILE__),
		'background_image' => plugins_url('default-background.jpg', __FILE__),
		'header_text' => 'Sitemize Hoş Geldiniz!',
		'description' => 'Bu, önemli bilgileri göstermek için özelleştirilebilir bir pop-up\'tır.',
		'button_text' => 'Hemen İncele',
		'button_url' => '#'
	);
	add_option('serra_popup_options', $default_options);
}

// CSS ve JavaScript dosyalarını yükle
function serra_popup_enqueue_scripts() {
	wp_enqueue_style('serra-popup-css', plugins_url('serra-popup.css', __FILE__));
	wp_enqueue_script('serra-popup-js', plugins_url('serra-popup.js', __FILE__), array('jquery'), '1.1.0', true);
	wp_enqueue_media();
}
add_action('wp_enqueue_scripts', 'serra_popup_enqueue_scripts');

// Yönetici menüsüne "Serra Popup Ayarları" ekle
function serra_popup_add_admin_menu() {
	add_options_page(
		'Serra Popup Ayarları',
		'Serra Popup',
		'manage_options',
		'serra_popup',
		'serra_popup_options_page'
	);
}
add_action('admin_menu', 'serra_popup_add_admin_menu');

// Ayar sayfasını oluştur
function serra_popup_options_page() {
	?>
    <div class="wrap">
        <h1>Serra Popup Ayarları</h1>
        <p>Bu pop-up, önemli bilgileri ziyaretçilere göstermek için kullanılır. Aşağıdaki alanlardan logo, arka plan resmi, başlık ve açıklama metnini ayarlayın. Pop-up'ı aktif/pasif duruma getirmek için "Pop-up Aktif" seçeneğini kullanın.</p>
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
		'Popup Ayarları',
		'serra_popup_settings_section_cb',
		'serra_popup'
	);

	add_settings_field('serra_popup_active', 'Pop-up Aktif', 'serra_popup_settings_active_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_logo', 'Popup Logo', 'serra_popup_settings_logo_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_background_image', 'Popup Arka Plan Resmi', 'serra_popup_settings_background_image_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_header_text', 'Popup Başlık Metni', 'serra_popup_settings_header_text_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_description', 'Popup Açıklama Metni', 'serra_popup_settings_description_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button_text', 'Buton Metni', 'serra_popup_settings_button_text_cb', 'serra_popup', 'serra_popup_settings_section');
	add_settings_field('serra_popup_button_url', 'Buton Linki', 'serra_popup_settings_button_url_cb', 'serra_popup', 'serra_popup_settings_section');
}
add_action('admin_init', 'serra_popup_settings_init');

function serra_popup_settings_section_cb() {
	echo '<p>Pop-up\'ın görünüm ve içeriğini özelleştirin. Aşağıdaki alanlardan logo, arka plan resmi, başlık ve açıklama metnini ayarlayın.</p>';
}

function serra_popup_options_validate($input) {
	$input['active'] = isset($input['active']) ? '1' : '0'; // Aktif/pasif durumu
	$input['logo'] = esc_url_raw($input['logo']);
	$input['background_image'] = esc_url_raw($input['background_image']);
	$input['header_text'] = sanitize_text_field($input['header_text']);
	$input['description'] = sanitize_textarea_field($input['description']);
	$input['button_text'] = sanitize_text_field($input['button_text']);
	$input['button_url'] = esc_url_raw($input['button_url']);
	return $input;
}

// Popup HTML yapısını footer'a ekle
function serra_popup_html() {
	$options = get_option('serra_popup_options');

	// Eğer ayar seçenekleri mevcut değilse varsayılan ayarları yükle
	if (!$options || !is_array($options)) {
		$options = array(
			'active' => '1', // Varsayılan olarak aktif
			'logo' => plugins_url('default-logo.png', __FILE__),
			'background_image' => plugins_url('default-background.jpg', __FILE__),
			'header_text' => 'Sitemize Hoş Geldiniz!',
			'description' => 'Bu, önemli bilgileri göstermek için özelleştirilebilir bir pop-up\'tır.',
			'button_text' => 'Hemen İncele',
			'button_url' => '#'
		);
	}

	// Eğer pop-up aktif değilse çık
	if ($options['active'] !== '1') {
		return;
	}

	?>
    <div id="serraPopup" class="serra-popup-container">
        <div class="serra-popup-content" style="background-image: url('<?php echo esc_url($options['background_image']); ?>');">
			<?php if (!empty($options['logo'])): ?>
                <img src="<?php echo esc_url($options['logo']); ?>" alt="Site Logosu" class="serra-popup-logo" />
			<?php else: ?>
                <h2><?php echo esc_html(get_bloginfo('name')); ?></h2>
			<?php endif; ?>
            <h1><?php echo esc_html($options['header_text']); ?></h1>
            <p><?php echo esc_html($options['description']); ?></p>
			<?php if (!empty($options['button_text']) && !empty($options['button_url'])): ?>
                <a href="<?php echo esc_url($options['button_url']); ?>" class="serra-popup-btn">
					<?php echo esc_html($options['button_text']); ?>
                </a>
			<?php endif; ?>
            <div class="serra-popup-actions">
                <button id="serraPopupRemindLater" class="serra-popup-link">Daha sonra hatırlat</button>
            </div>
            <button id="serraPopupClose">×</button>
        </div>
    </div>
	<?php
}
add_action('wp_footer', 'serra_popup_html');

// Ayar alanları callback fonksiyonları
function serra_popup_settings_active_cb() {
	$options = get_option('serra_popup_options');
	$active_value = isset($options['active']) ? $options['active'] : '1';
	echo '<input type="checkbox" id="serra_popup_active" name="serra_popup_options[active]" value="1"' . checked($active_value, '1', false) . ' />';
}

function serra_popup_settings_logo_cb() {
	$options = get_option('serra_popup_options');
	$logo_value = isset($options['logo']) ? $options['logo'] : '';
	echo '<input type="text" id="serra_popup_logo" name="serra_popup_options[logo]" value="' . esc_attr($logo_value) . '" />';
	echo '<button class="upload_image_button button">Logo Yükle</button>';
}

function serra_popup_settings_background_image_cb() {
	$options = get_option('serra_popup_options');
	$background_image_value = isset($options['background_image']) ? $options['background_image'] : '';
	echo '<input type="text" id="serra_popup_background_image" name="serra_popup_options[background_image]" value="' . esc_attr($background_image_value) . '" />';
	echo '<button class="upload_image_button button">Arka Plan Yükle</button>';
}

function serra_popup_settings_header_text_cb() {
	$options = get_option('serra_popup_options');
	$header_text_value = isset($options['header_text']) ? $options['header_text'] : '';
	echo '<input type="text" id="serra_popup_header_text" name="serra_popup_options[header_text]" value="' . esc_attr($header_text_value) . '" />';
}

function serra_popup_settings_description_cb() {
	$options = get_option('serra_popup_options');
	$description_value = isset($options['description']) ? $options['description'] : '';
	echo '<textarea id="serra_popup_description" name="serra_popup_options[description]">' . esc_textarea($description_value) . '</textarea>';
}

function serra_popup_settings_button_text_cb() {
	$options = get_option('serra_popup_options');
	$button_text_value = isset($options['button_text']) ? $options['button_text'] : '';
	echo '<input type="text" id="serra_popup_button_text" name="serra_popup_options[button_text]" value="' . esc_attr($button_text_value) . '" />';
}

function serra_popup_settings_button_url_cb() {
	$options = get_option('serra_popup_options');
	$button_url_value = isset($options['button_url']) ? $options['button_url'] : '';
	echo '<input type="text" id="serra_popup_button_url" name="serra_popup_options[button_url]" value="' . esc_attr($button_url_value) . '" />';
}

// Medya yükleme işlevi için JavaScript
function serra_popup_media_uploader() {
	?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('.upload_image_button').click(function(e) {
                e.preventDefault();
                var button = $(this);

                // Medya yükleyici zaten açıksa, onu aç
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                // Yeni bir medya yükleyici oluştur
                mediaUploader = wp.media({
                    title: 'Logo Yükle',
                    button: {
                        text: 'Seç'
                    },
                    multiple: false // Çoklu yükleme kapalı
                });

                // Medya yükleyici kapandığında yapılacak işlemler
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    button.prev('input').val(attachment.url); // URL'yi input alanına ekle
                });

                // Medya yükleyiciyi aç
                mediaUploader.open();
            });
        });
    </script>
	<?php
}
add_action('admin_footer', 'serra_popup_media_uploader');
