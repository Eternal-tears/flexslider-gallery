<?php
/*
 Plugin Name: flexslider-gallery
 Plugin URI: http://lovelog.eternal-tears.com/
 Description: ギャラリーサイトを作成する時に便利です。
 Version: 1.0
 Author: Eternal-tears
 Author URI: http://lovelog.eternal-tears.com/

 jQuery's plugin by jQuery FlexSlider v2.0(http://www.woothemes.com/flexslider/)
 */
load_plugin_textdomain( 'flexslidergallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/* ==================================================
■ギャラリー画像専用の画像サイズ設定
 ================================================== */
add_image_size( 'single-post-thumbnail', 348, 348); //大画像設定
add_image_size( 'top-post-thumbnail', 148, 148, true); // 中画像サイズ設定
add_image_size( 'thumbnail', 58, 58, true);//ミニサムネイル画像設定

/* ==================================================
■サムネイル画像とメイン画像の表示ソース
 ================================================== */
function flexslider_gallery_thumb() {
	global $post;

	$flexslidergalleryoutput = '';

	$images = get_posts(array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => -1
	));

	foreach ($images as $image) {
		$thumb_attributes = wp_get_attachment_image_src($image->ID,'top-post-thumbnail');

//echo '<pre>';
//var_dump($thumb_attributes);
//echo '</pre>';

		//サムネイル画像の表示部分
		$flexslidergalleryoutput .= '<li>';
		$flexslidergalleryoutput .= '<img';
		$flexslidergalleryoutput .= ' src="' . esc_attr($thumb_attributes[0]) . '"';
		$flexslidergalleryoutput .= ' width="' . esc_attr($thumb_attributes[1]) . '"';
		$flexslidergalleryoutput .= ' height="' . esc_attr($thumb_attributes[2]) . '"';
		$flexslidergalleryoutput .= ' /></li>' . "\n";

	}

	if (!empty($flexslidergalleryoutput)) {
		$flexslidergalleryoutput = '<ul class="slides">' . "\n"
			. $flexslidergalleryoutput
			. '</ul>' . "\n";
	}
		echo $flexslidergalleryoutput;
}

//メイン画像
function flexslider_gallery_main(){
	global $post;

	$flexslidergalleryoutput = '';

	$images = get_posts(array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => -1
	));

	foreach ($images as $image) {
		$mainimg_attributes = wp_get_attachment_image_src($image->ID,'single-post-thumbnail');

		//メイン画像の表示部分
		$flexslidergalleryoutput .= '<li>';
		$flexslidergalleryoutput .= '<img';
		$flexslidergalleryoutput .= ' src="' . esc_attr($mainimg_attributes[0]) . '"';
		$flexslidergalleryoutput .= ' width="' . esc_attr($mainimg_attributes[1]) . '"';
		$flexslidergalleryoutput .= ' height="' . esc_attr($mainimg_attributes[2]) . '"';
		$flexslidergalleryoutput .= ' /></li>' . "\n";

	}

	if (!empty($flexslidergalleryoutput)) {
		$flexslidergalleryoutput = '<ul class="slides">' . "\n"
			  . $flexslidergalleryoutput
			  . '</ul>' . "\n";
	}
		echo $flexslidergalleryoutput;
}


/* ==================================================
■表示したい部分に入れるソース
 ================================================== */

function add_flexslider_gallery(){
	global $post;
	echo '<div class="itemimg">' . "\n";

	if(get_option('flexslidergallery_slider')==1){
		echo '<div id="slider" class="flexslider">' . "\n";
		flexslider_gallery_main($post->ID);
		echo '</div>' . "\n";
		echo '<div id="carousel" class="flexslider">' . "\n";
		flexslider_gallery_thumb($post->ID);
		echo '</div>' . "\n";
	}elseif(get_option('flexslidergallery_slider')==2){
		echo '<div class="flexslider">' . "\n";
		flexslider_gallery_main($post->ID);
		echo '</div>' . "\n";
	}
	echo '</div>' . "\n";
}

/* ==================================================
■ヘッダーにソース
 ================================================== */
function add_flexslider_js() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery.flexslider-min',plugins_url('js/jquery.flexslider-min.js', __FILE__),array('jquery'),false,false);

	//管理画面でサムネイル有りを選択した場合
	if(get_option('flexslidergallery_slider')==1){
	wp_enqueue_script('flexslider-thumbsilider',plugins_url('js/flexslider-thumbsilider.js', __FILE__),array('jquery'),false,false);

	//管理画面でサムネイルなしを選択した場合
	}elseif(get_option('flexslidergallery_slider')==2){
	wp_enqueue_script('flexslider-basicslider',plugins_url('js/flexslider-basicslider.js', __FILE__),array('jquery'),false,false);
	}
}
function add_flexslider_css(){
	wp_register_style( 'flexslider', plugins_url('css/flexslider.css', __FILE__),'screen' );
	wp_enqueue_style('flexslider');
}
add_action('wp_enqueue_scripts','add_flexslider_js');
add_action('wp_enqueue_scripts','add_flexslider_css');

//プラグイン設定画面get_option('flexslidergallery_slider')
// 設定の初期値を保存
function flexslidergallery_init_options() {
	// 初期化の処理を行う
	if (!get_option('flexslidergallery_installed')) {
		update_option('flexslidergallery_slider', 1);
	}
}
register_activation_hook(__FILE__, 'flexslidergallery_init_options');

// 「プラグイン」メニューのサブメニュー
function flexslidergallery_add_admin_menu() {
	add_submenu_page('options-general.php', 'flexslidergalleryの設定', 'flexslidergalleryの設定', activate_plugins, __FILE__, 'flexslidergallery_admin_page');
}
add_action('admin_menu', 'flexslidergallery_add_admin_menu');

// 設定画面の表示
function flexslidergallery_admin_page() {


	// 「変更を保存」ボタンがクリックされたときは、設定を保存する
	if ($_POST['posted'] == 'Y') {
		update_option('flexslidergallery_slider', intval($_POST['slider']));
	}
?>
<?php if($_POST['posted'] == 'Y') : ?><div class="updated"><p><strong>設定を保存しました</strong></p></div><?php endif; ?>
<div class="wrap">
	<h2><?php echo __( 'flexslidergalleryの設定', 'flexslidergallery' ); ?></h2>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="posted" value="Y">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="slider">表示タイプ<label></th>
				<td>
					<select name="slider" id="slider">
					<?php
						$options = array(
							array('value' => '1', 'text' => 'サムネイル付き'),
							array('value' => '2', 'text' => 'サムネイルなし'),
						);
						foreach ($options as $option) : ?>
						<option value="<?php echo esc_attr($option['value']); ?>"<?php if (get_option('flexslidergallery_slider') == $option['value']) : ?> selected="selected"<?php endif; ?>><?php echo esc_attr($option['text']); ?></option>
					<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="変更を保存" />
		</p>
	</form>
	<h2>プラグインの使い方</h2>
	<p>テーマ内の表示したい箇所に下記のソースを表示してください。<br>
	&lt;?php add_flexslider_gallery(); ?&gt;
	</p>

</div>
<?php
}
?>