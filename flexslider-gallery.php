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

class FlexSliderTextDomain {
	
	var $domain = 'gallerylist';
	var $loaded = false;

	function load() {
		if ($this->loaded) {
			return;
		}
		$locale = get_locale();
		$mofile = dirname(__FILE__);
		$mofile .= "/{$this->domain}-{$locale}.mo";
		load_textdomain($this->domain, $mofile);
		$this->loaded = true;
	}
}
$flexslidergallerytextdomain = new FlexSliderTextDomain;

/* ==================================================
■サムネイル画像
 ================================================== */
add_image_size( 'single-post-thumbnail', 348, 348); // 個別投稿、個別の固定ページでのサムネイル
add_image_size( 'top-post-thumbnail', 148, 148, true); // 個別投稿、個別の固定ページでのサムネイル
add_image_size( 'thumbnail', 58, 58, true);

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
		'numberposts' => 999
	));

	foreach ($images as $image) {
		$thumnailimg = wp_get_attachment_metadata($image->ID);
		$mainimg = wp_get_attachment_url($image->ID);
		$imgmimetype1 = str_replace ("image/","",$image->post_mime_type);
		$imgmimetype2 = str_replace ("jpeg","jpg",$imgmimetype1);
		$imgmimetype3 = str_replace ("jpg",".jpg",$imgmimetype2);

		$upload_dir = wp_upload_dir();//http://shop.eternal-tears.jp/wp-content/uploads

		$mainsizew = $thumnailimg["width"];
		$mainsizeh = $thumnailimg["height"];
		$thumnailw = $thumnailimg["sizes"]["thumbnail"]["width"];
		$thumnailh = $thumnailimg["sizes"]["thumbnail"]["height"];

		$thumbnailimgsize = "-" . $thumnailw . "x" . $thumnailh .  "." . $imgmimetype2;
		$mainimgmedium2 = str_replace ($thumbnailimgsize, $imgmimetype3, $thumnailimg["sizes"]["thumbnail"]["file"]);

		$upfiledir = str_replace ($mainimgmedium2, "", $thumnailimg["file"]);

		$mainimgmedium = $upload_dir['baseurl']."/". $upfiledir .$thumnailimg["sizes"]["single-post-thumbnail"]["file"];
		$thumnail_url = $upload_dir['baseurl']."/". $upfiledir .$thumnailimg["sizes"]["thumbnail"]["file"];

//echo '<pre>';
//var_dump($thumbnailimgsize);
//echo '</pre>';

		$flexslidergalleryoutput .= '<li>';
		$flexslidergalleryoutput .= '<img';
		$flexslidergalleryoutput .= ' src="' . esc_attr($thumnail_url) . '"';
		$flexslidergalleryoutput .= ' width="' . esc_attr($thumnailw) . '"';
		$flexslidergalleryoutput .= ' height="' . esc_attr($thumnailh) . '"';
		$flexslidergalleryoutput .= ' /></li>' . "\n";

	}

	if (!empty($flexslidergalleryoutput)) {
		$flexslidergalleryoutput = '<ul class="slides">' . "\n"
			  . $flexslidergalleryoutput
			  . '</ul>' . "\n";
	}
		echo $flexslidergalleryoutput;
}

function flexslider_gallery_main(){
	global $post;

	$flexslidergalleryoutput = '';

	$images = get_posts(array(
		'post_parent' => $post->ID,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'numberposts' => 999
	));

	foreach ($images as $image) {
		$thumnailimg = wp_get_attachment_metadata($image->ID);
		$mainimg = wp_get_attachment_url($image->ID);
		$imgmimetype1 = str_replace ("image/","",$image->post_mime_type);
		$imgmimetype2 = str_replace ("jpeg","jpg",$imgmimetype1);
		$imgmimetype3 = str_replace ("jpg",".jpg",$imgmimetype2);

		$upload_dir = wp_upload_dir();//http://shop.eternal-tears.jp/wp-content/uploads

		$mainsizew = $thumnailimg["sizes"]["single-post-thumbnail"]["width"];
		$mainsizeh = $thumnailimg["sizes"]["single-post-thumbnail"]["height"];
		$thumnailw = $thumnailimg["sizes"]["thumbnail"]["width"];
		$thumnailh = $thumnailimg["sizes"]["thumbnail"]["height"];

		$thumbnailimgsize = "-" . $thumnailw . "x" . $thumnailh . "." . $imgmimetype2;
		$mainimgmedium2 = str_replace ($thumbnailimgsize, $imgmimetype3, $thumnailimg["sizes"]["thumbnail"]["file"]);

		$upfiledir = str_replace ($mainimgmedium2, "", $thumnailimg["file"]);

		$mainimgmedium = $upload_dir['baseurl']."/". $upfiledir .$thumnailimg["sizes"]["single-post-thumbnail"]["file"];
		$thumnail_url = $upload_dir['baseurl']."/". $upfiledir .$thumnailimg["sizes"]["thumbnail"]["file"];

//echo '<pre>';
//var_dump($mainimgmedium);
//echo '</pre>';

		$flexslidergalleryoutput .= '<li>';
		$flexslidergalleryoutput .= '<img';
		$flexslidergalleryoutput .= ' src="' . esc_attr($mainimgmedium) . '"';
		$flexslidergalleryoutput .= ' width="' . esc_attr($mainsizew) . '"';
		$flexslidergalleryoutput .= ' height="' . esc_attr($mainsizeh) . '"';
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
	echo '<div id="slider" class="flexslider">' . "\n";
	flexslider_gallery_main($post->ID);
	echo '</div>' . "\n";
	echo '<div id="carousel" class="flexslider">' . "\n";
	flexslider_gallery_thumb($post->ID);
	echo '</div>' . "\n";
	echo '</div>' . "\n";
}


/* ==================================================
■ヘッダーにソース
 ================================================== */
function add_flexslider_js() {
echo '<script type="text/javascript" src="' . plugins_url( "js/jquery.flexslider-min.js" , __FILE__ ) . '"></script>' . "\n";
echo '<script type="text/javascript" src="' . plugins_url( "js/flexslider-gallery-head.js" , __FILE__ ) . '"></script>' . "\n";
echo '<link rel="stylesheet" href="' . plugins_url( "css/flexslider.css" , __FILE__ ) . '" type="text/css" media="screen" />' . "\n";
}
add_action('wp_head','add_flexslider_js');

?>"\n";
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
	echo '<script type="text/javascript" src="' . plugins_url( "js/jquery.flexslider-min.js" , __FILE__ ) . '"></script>' . "\n";

	//管理画面でサムネイル有りを選択した場合
	if(get_option('flexslidergallery_slider')==1){
		echo '<script type="text/javascript" src="' . plugins_url( "js/flexslider-thumbsilider.js" , __FILE__ ) . '"></script>' . "\n";

	//管理画面でサムネイルなしを選択した場合
	}elseif(get_option('flexslidergallery_slider')==2){
		echo '<script type="text/javascript" src="' . plugins_url( "js/flexslider-basicslider.js" , __FILE__ ) . '"></script>' . "\n";
	}
echo '<link rel="stylesheet" href="' . plugins_url( "css/flexslider.css" , __FILE__ ) . '" type="text/css" media="screen" />' . "\n";
}
add_action('wp_head','add_flexslider_js');

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
	add_submenu_page('plugins.php', 'flexslidergalleryの設定', 'flexslidergalleryの設定', 8, __FILE__, 'flexslidergallery_admin_page');
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
	<h2>flexslidergalleryの設定</h2>
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