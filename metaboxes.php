<?php 
// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_extra_fields', 1);

function my_extra_fields() {
	add_meta_box( 'extra_fields', 'Дополнительные поля', 'extra_fields_box_func', 'post', 'normal', 'high'  );
}
 
// код блока
function extra_fields_box_func( $post ){
	?>
	<p><label><input type="text" name="extra[title]" value="<?php echo get_post_meta($post->ID, 'title', 1); ?>" style="width:50%" /> ? заголовок страницы (title)</label></p>

	<p>Описание статьи (description):
		<textarea type="text" name="extra[description]" style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'description', 1); ?></textarea>
	</p>

	<p>Видимость поста: <?php $mark_v = get_post_meta($post->ID, 'robotmeta', 1); ?>
		 <label><input type="radio" name="extra[robotmeta]" value="" <?php checked( $mark_v, '' ); ?> /> index,follow</label>
		 <label><input type="radio" name="extra[robotmeta]" value="nofollow" <?php checked( $mark_v, 'nofollow' ); ?> /> nofollow</label>
		 <label><input type="radio" name="extra[robotmeta]" value="noindex" <?php checked( $mark_v, 'noindex' ); ?> /> noindex</label>
		 <label><input type="radio" name="extra[robotmeta]" value="noindex,nofollow" <?php checked( $mark_v, 'noindex,nofollow' ); ?> /> noindex,nofollow</label>
	</p>

	<p><select name="extra[select]">
			<?php $sel_v = get_post_meta($post->ID, 'select', 1); ?>
			<option value="0">----</option>
			<option value="1" <?php selected( $sel_v, '1' )?> >Выбери меня</option>
			<option value="2" <?php selected( $sel_v, '2' )?> >Нет, меня</option>
			<option value="3" <?php selected( $sel_v, '3' )?> >Лучше меня</option>
		</select> ? выбор за вами</p>

	<input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}

// включаем обновление полей при сохранении
add_action( 'save_post', 'my_extra_fields_update', 0 );

## Сохраняем данные, при сохранении поста
function my_extra_fields_update( $post_id ){
	// базовая проверка
	if (
		   empty( $_POST['extra'] )
		|| ! wp_verify_nonce( $_POST['extra_fields_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	)
		return false;

	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['extra'] = array_map( 'sanitize_text_field', $_POST['extra'] ); // чистим все данные от пробелов по краям
	foreach( $_POST['extra'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key ); // удаляем поле если значение пустое
			continue;
		}

		update_post_meta( $post_id, $key, $value ); // add_post_meta() работает автоматически
	}

	return $post_id;
}

// ========== custom fields metaboxes ==========
new My_Best_Metaboxes;

class My_Best_Metaboxes {

	public $post_type = 'news';

	static $meta_key = 'info_news';

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'show_assets' ), 10, 999 );
	}

	## Добавляет матабоксы
	public function add_metabox() {
		add_meta_box( 'box_info_company', 'Дополнительная информация', array( $this, 'render_metabox' ), $this->post_type, 'advanced', 'high' );
	}

	## Отображает метабокс на странице редактирования поста
	public function render_metabox( $post ) {

		?>
		<table class="form-table company-info">

			<tr>
				<th>
					Название новости <span class="dashicons dashicons-plus-alt add-company-address"></span>
				</th>
				<td class="company-address-list">
					<?php
					$input = '
					<span class="item-address">
						<input type="text" name="'. self::$meta_key .'[]" value="%s">
						<span class="dashicons dashicons-trash remove-company-address"></span>
					</span>
					';

					$title_news = get_post_meta( $post->ID, self::$meta_key, true );

					if ( is_array( $title_news ) ) {
						foreach ( $title_news as $addr ) {
							printf( $input, esc_attr( $addr ) );
						}
					} else {
						printf( $input, '' );
					}
					?>
				</td>
			</tr>

		</table>

		<?php
	}

	## Очищает и сохраняет значения полей
	public function save_metabox( $post_id ) {

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		if ( isset( $_POST[self::$meta_key] ) && is_array( $_POST[self::$meta_key] ) ) {
			$title_news = $_POST[self::$meta_key];

			$title_news = array_map( 'sanitize_text_field', $title_news ); // очистка

			$title_news = array_filter( $title_news ); // уберем пустые адреса

			if ( $title_news ) 
				update_post_meta( $post_id, self::$meta_key, $title_news );
			else 
				delete_post_meta( $post_id, self::$meta_key );

		}
	}

	## Подключает скрипты и стили
	public function show_assets() {
		if ( is_admin() && get_current_screen()->id == $this->post_type ) {
			$this->show_styles();
			$this->show_scripts();
		}
	}

	## Выводит на экран стили
	public function show_styles() {
		?>
		<style>
			.add-company-address {
				color: #00a0d2;
				cursor: pointer;
			}
			.company-address-list .item-address {
				display: flex;
				align-items: center;
			}
			.company-address-list .item-address input {
				width: 100%;
				max-width: 400px;
			}
			.remove-company-address {
				color: brown;
				cursor: pointer;
			}
		</style>
		<?php
	}

	## Выводит на экран JS
	public function show_scripts() {
		?>
		<script>
			jQuery(document).ready(function ($) {

				var $companyInfo = $('.company-info');

				// Добавляет бокс с вводом адреса фирмы
				$('.add-company-address', $companyInfo).click(function () {
					var $list = $('.company-address-list');
						$item = $list.find('.item-address').first().clone();

					$item.find('input').val(''); // чистим знанчение

					$list.append( $item );
				});

				// Удаляет бокс с вводом адреса фирмы
				$companyInfo.on('click', '.remove-company-address', function () {
					if ($('.item-address').length > 1) {
						$(this).closest('.item-address').remove();
					}
					else {
						$(this).closest('.item-address').find('input').val('');
					}
				});

			});
		</script>
		<?php
	}

}

 
