<html <?php language_attributes(); ?>>

<meta charset="<?php bloginfo('charset');?>" >
<title><?php wp_title( '|' , true ); ?></title>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" media="all" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script('comment-reply'); ?>

 <!--WP_head begin-->
    <?php wp_head(); //wp-head hook, needed for plugins, do not delete, set before </head>?>
<!--WP_head end-->
 <!--WP_head begin-->
    <?php wp_footer();  //wp-footer hook, needed for plugins, do not delete, set before </body>?>
<!--WP_head end-->

<body <?php body_class(); ?>>

<a href="<?php echo home_url(); ?>" class="logo">

<?php if ( ! dynamic_sidebar( 'Find Widgets' ) ) : ?>
<?php endif; // end sidebar widget area ?>

<ul class="loop-list">
	<?php if ( have_posts() ) : ?>
      	<?php while ( have_posts() ) : the_post(); ?>
        	<li>			            	
                <a class="section-image" href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail($page->ID, array(220, 160), 'thumbnail');  ?></a>
                <h3><a class="loop-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </li>
		<?php endwhile; ?>
    <?php else : ?>
	<?php endif; ?>
</ul>


<?php  
    $projects = new WP_Query(array(
        'post_type' => 'projects',
        'posts_per_page' => 3,
        'paged' => 1
    ));
    while($projects->have_posts()){ $projects->the_post(); ?>
        <li>
			<a class="project-image" href="<?php the_permalink(); ?>">
				<?php echo get_the_post_thumbnail($page->ID, 'thumbnail');  ?> 
				 
			</a>

			<h3 class="project-title"><a class="project-title" href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
			<a href="<?php the_permalink(); ?>" class="main-more">Подробнее</a>
		</li>         
    <?php } ?>  
    <?php wp_reset_postdata();
?>	

<?php require 'form_processing.php'; ?>
<?php
if(isset($_POST['callFormSubmit'])){
		$name = trim(strip_tags($_POST['name'])) . "<br>";
		$phone = abs((int)$_POST['phone']) . "<br>";
		$email = $_POST['email'] . "<br>";
		$theme = 'Звонок специалисту';
		$comment = $_POST['comment'];
		$siteEmail = 'shift_s@mail.ru';
		$message = array(
			"Имя клиента: $name",
			"Телефон клиента: $phone",
			"Электронная почта клиента: $email",
			"Комментарий: $comment"
		);
		$headers = "Content-type: text/html; charset=utf-8";
		mail($siteEmail, $theme, implode("\r\n", $message), $headers);
		header("Location: " . "http://smartsystem.hostenko.com/otpravka-formyi/");
		exit();
}
?>


<!-- functions tools -->

<?php

if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

define( 'HH_TEMPLATE_VERSION' , '0.1.3' );
define( 'HH_THEME_URI' , get_template_directory_uri() );

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 300, 180 );
    add_image_size('main-thumb', 300, 0, true);
}

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Head Widgets',
		'id'   => 'head-widgets',
		'description'   => 'Widgets for the header.',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	));
}


add_action('init', 'services_init');
function services_init() 
{
    $services_labels = array(
        'name' => 'Услуги',
        'singular_name' => 'Услуга',
        'all_items' => 'Все услуги',
        'add_new' => 'Добавить новую услугу',
        'add_new_item' => 'Добавить новую услугу',
        'edit_item' => 'Редактировать услугу',
        'new_item' => 'Новая услуга',
        'view_item' => 'Просмотреть услугу',
        'search_items' => 'Искать в услугах',
        'not_found' =>  'Ни одной услуги не найдено',
        'not_found_in_trash' => 'Ни одной услуги не найдено в корзине', 
        'parent_item_colon' => ''
    );

    $services = array(
        'labels' => $services_labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title', 'thumbnail', 'editor'),
        'has_archive' => 'true'
    ); 
    register_post_type('services',$services);
}


add_action( 'init', 'services_section_taxonomy', 0 );

function services_section_taxonomy()  {

    $section = array(
        'name'                       => 'Раздел услуг', 'Taxonomy General Name',
        'singular_name'              => 'Раздел услуги', 'Taxonomy Singular Name',
        'menu_name'                  => 'Разделы',
        'all_items'                  => 'Все разделы услуг',
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => 'Новый раздел услуг',
        'add_new_item'               => 'Добавить раздел услуг',
        'edit_item'                  => 'Редактировать услуг', 
        'update_item'                => 'Обновить раздел услуг', 
        'separate_items_with_commas' => null,
        'search_items'               => 'Искать в разделах услуг',
        'add_or_remove_items'        => null, 
        'choose_from_most_used'      => null
    );
    $sectionArgs = array(
        'labels'                     => $section,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,

    );
    register_taxonomy( 'section_services', array('services'), $sectionArgs );
}


<div class="login_form_widget">
<?php if (!is_user_logged_in()) { 
		echo "<p>Здравствуйте, <strong>гость</strong>!</p>";
		wp_login_form(); ?>
		<p><?php if ( get_option( 'users_can_register' ) ) : ?><a href="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login' ) ); ?>"><?php _e( 'Register' ); ?></a> | <?php endif; ?><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword">Забыли пароль?</a></p><?php } 
	else { ?>
		<?php global $user_identity;
		get_currentuserinfo(); ?>
		<p>Здравствуйте, <strong><?php echo $user_identity; ?></strong>!</p>
		<ul>
			<li><a href="<?php bloginfo('url'); ?>/wp-admin/profile.php">Изменить профиль</a></li>
			<li><a href="<?php echo wp_logout_url(get_permalink()); ?>">Выйти</a></li>
		</ul>
<?php } ?>
</div>


