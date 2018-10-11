<?php

// хук для регистрации
add_action('init', 'create_taxnews', 0);
function create_taxnews(){
	// список параметров: http://wp-kama.ru/function/get_taxonomy_labels
	register_taxonomy('taxnews', array('news'), array(
		'label'                 => 'Категории новостей', // определяется параметром $labels->name
		'labels'                => array(
			'name'              => 'Категории новостей',
			'singular_name'     => 'Категория новостей',
			'search_items'      => 'Поиск категорий новостей',
			'all_items'         => 'Все категории новостей',
			'view_item '        => 'Просмотреть категорию новостей',
			'parent_item'       => 'Родительская',
			'parent_item_colon' => 'Родительская:',
			'edit_item'         => 'Изменить категорию новостей',
			'update_item'       => 'Обновить категорию новостей',
			'add_new_item'      => 'Добавить новую категорию новостей',
			'new_item_name'     => 'Название',
			'menu_name'         => 'Категории новостей',
		),
		'description'           => '', // описание таксономии
		'public'                => true,
		'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_in_menu'          => true, // равен аргументу show_ui
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		'hierarchical'          => true,
		'update_count_callback' => '',
		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => null, // по умолчанию значение show_ui
	) );
}

add_action( 'init', 'register_post_news' );
function register_post_news(){
	register_post_type('news', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'Новости', // основное название для типа записи
			'singular_name'      => 'Новость', // название для одной записи этого типа
			'add_new'            => 'Добавить новую', // для добавления новой записи
			'add_new_item'       => 'Добавление новость', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать новость', // для редактирования типа записи
			'new_item'           => 'Новая новость', // текст новой записи
			'view_item'          => 'Просмотреть новость', // для просмотра записи этого типа.
			'search_items'       => 'Поиск новостей', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => null, // для родителей (у древовидных типов)
			'menu_name'          => 'Новости', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => null, // зависит от public
		'exclude_from_search' => null, // зависит от public
		'show_ui'             => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => null, // зависит от public
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-admin-site', 
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','editor'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array(‘taxnews’),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}
