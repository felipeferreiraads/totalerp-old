<?php 

add_filter('show_admin_bar', '__return_false');

function script_enqueue() {

  wp_enqueue_style( 'bootstrap',  get_bloginfo( 'stylesheet_directory' ) . "/css/bootstrap.min.css" ); 

  wp_enqueue_style( 'fontawesome',  get_template_directory_uri() . "/css/font-awesome.min.css" );  
  
  wp_enqueue_style( 'slick',  get_template_directory_uri() . "/css/slick.css" ); 

  wp_enqueue_style( 'datetimepicker',  get_template_directory_uri() . "/css/jquery.datetimepicker.min.css" ); 

  wp_enqueue_style( 'style',  get_template_directory_uri() . "/css/total.css" ); 

  wp_register_style('Roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,700,900');

   wp_register_style('Lato', 'https://fonts.googleapis.com/css?family=Lato:400');

  wp_enqueue_style( 'Roboto');

  wp_enqueue_style( 'Lato');

  wp_deregister_script('jquery');
   
  wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", '', false, true);

  wp_register_script('tether',  get_template_directory_uri() . "/js/tether.min.js", '', false, true);

  wp_register_script('bootstrapjs',  get_template_directory_uri() . "/js/bootstrap.min.js", '', false, true);

  wp_register_script('slick',  get_template_directory_uri() . "/js/slick.min.js", '', false, true);

  wp_register_script('datetimepicker',  get_template_directory_uri() . "/js/jquery.datetimepicker.min.js", '', false, true);

  wp_register_script('maps',  get_template_directory_uri() . "/js/maps.min.js", '', false, true);

  wp_register_script('youtube_api', 'https://www.youtube.com/player_api', '', false, true);

  wp_register_script('analytics',  get_template_directory_uri() . "/js/analytics.js", '', false, true);   

  wp_register_script('mainjs',  get_template_directory_uri() . "/js/main.js", '', false, true);
  
  wp_enqueue_script('jquery');

  wp_enqueue_script('tether');

  wp_enqueue_script('bootstrapjs');

  wp_enqueue_script('datetimepicker');

  wp_enqueue_script('slick'); 
  
  wp_enqueue_script('maps'); 

  wp_enqueue_script('youtube_api'); 
  
  wp_enqueue_script('mainjs'); 
  
  wp_enqueue_script('analytics');

  // set stylesheet directory on javascript files
  $directory_array = array( 
    'templateUrl' => get_stylesheet_directory_uri(),
    'siteUrl' => get_bloginfo('url') 
  );

  wp_localize_script( 'mainjs', 'object_name', $directory_array );
  
} 

if (!is_admin()) add_action("wp_enqueue_scripts", "script_enqueue");


// Google Maps API

function nr_load_scripts() {
    
  wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDhYhhIAu_7rrfudm3YyhMtx6WCN36MrK0',null,null,true);  
  wp_enqueue_script('googlemaps');
    
}
add_action( 'wp_enqueue_scripts', 'nr_load_scripts' );


// Fixing Google Maps Issue

function fix_gmaps_api_key() {
  if(mb_strlen(acf_get_setting("google_api_key")) <= 0){
    acf_update_setting("google_api_key", "AIzaSyDhYhhIAu_7rrfudm3YyhMtx6WCN36MrK0");
  }
}
add_action( 'admin_enqueue_scripts', 'fix_gmaps_api_key' );



add_theme_support( 'html5', array( 'search-form' ) );

add_action( 'after_setup_theme', 'wpfw_theme_setup' );


function wpfw_theme_setup() {

  add_theme_support( 'post-thumbnails' ); 

  add_theme_support( 'title-tag' );
  
}

function register_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));

  register_nav_menu('top-menu',__( 'Top Menu' ));

}

add_action( 'init', 'register_menu' );

function upload_logo() {
  $args = array(
    'flex-width'    => true,
    'width'         => 170,
    'flex-height'    => true,
    'height'        => 80,
    'default-image' => get_template_directory_uri() . '/img/logo.png',
  );

  add_theme_support( 'custom-header', $args );

}

add_action( 'init', 'upload_logo' );


function create_post_type() {
   
  register_post_type( 'produtos',
    array(
      'labels' => array(
        'name' => __( 'Produtos' ),
        'singular_name' => __( 'Produto' ),
        'add_new' => __( 'Novo Produto' )
      ),
      'public' => true,
      'has_archive' => true,
      'show_in_nav_menus' => true,
      'show_in_rest'       => true,
      'rest_base'          => 'produtos',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
       /*'map_meta_cap' => true,*/      
      'supports' => array( 'title', 'editor',  'excerpt', 'thumbnail' )
    )
  ); 

  register_post_type( 'depoimentos',
    array(
      'labels' => array(
        'name' => __( 'Depoimentos' ),
        'singular_name' => __( 'Depoimento' ),
        'add_new' => __( 'Novo Depoimento' )
      ),
      'public' => true,
      'has_archive' => false,
      'show_in_nav_menus' => false,
      'show_in_rest'       => false,      
       /*'map_meta_cap' => true,*/      
      'supports' => array( 'title', 'editor', 'thumbnail' )
    )
  ); 

  
}

add_action( 'init', 'create_post_type' );




// Filter - google maps

add_filter('clean_url', 'so_handle_038', 99, 3);
function so_handle_038($url, $original_url, $_context) {
    if (strstr($url, "googleapis.com") !== false) {
        $url = str_replace("&#038;", "&", $url); // or $url = $original_url
    }

    return $url;
}

// Remove from panel


function remove_menus(){
  
  remove_menu_page( 'edit-comments.php' );          //Comments
  //remove_menu_page( 'tools.php' );  
  //remove_menu_page( 'admin.php?page=wpcf7' );
  remove_menu_page( 'edit.php?post_type=owl-carousel' ); // Owl Carousel
}

add_action( 'admin_menu', 'remove_menus' );


 // hide ACF and Contact Form 7 menus
//function remove_acf_menu() {
  //remove_menu_page('edit.php?post_type=acf');
//}

//add_action( 'admin_menu', 'remove_acf_menu', 999);


//function wpse_136058_remove_menu_pages() {

   // remove_menu_page( 'edit.php?post_type=acf' );
    //remove_menu_page( 'wpcf7' );
//
//add_action( 'admin_init', 'wpse_136058_remove_menu_pages' );


//Replace WP logo

function my_login_logo() { ?>
    <style type="text/css">
        .login h1 a {            
          background-image: url(http://patiosdecordoba.com.br/wordpress/wp-content/themes/tema/img/logo.png);
          padding-bottom: 0;
          background-position-x: 50%;
          width: 100%;
          height: 210px;
          background-size: 170px;
        }
        
    </style>
<?php }

//add_action( 'login_enqueue_scripts', 'my_login_logo' );




//protect admin
add_action('pre_user_query','isa_pre_user_query');
  function isa_pre_user_query($user_search) {
    $user = wp_get_current_user();
    if ($user->ID!=1) { // Is not administrator, remove administrator
    global $wpdb;
    $user_search->query_where = str_replace('WHERE 1=1',
    "WHERE 1=1 AND {$wpdb->users}.ID<>1",$user_search->query_where);
  }
}


require_once('wp_bootstrap_navwalker.php');


// Replace ajax loader on Contact Form 7
add_filter('wpcf7_ajax_loader', 'my_wpcf7_ajax_loader');

function my_wpcf7_ajax_loader () {
    return get_template_directory_uri() . '/images/rolling.svg';
}



function new_excerpt_more($more) {
  global $post;
  return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');


function new_excerpt_length($length) {
  return 20;
}
add_filter('excerpt_length', 'new_excerpt_length');

add_image_size ( 'min-width', 110 , 110 , false );


function wpmc_register_taxonomies() {
     
    // register taxonomy called 'Pacotes'
    register_taxonomy( 'pacotes', 'produtos',
        array(
            'labels' => array(
                'name'              => 'Pacotes',
                'singular_name'     => 'Pacote',
                'search_items'      => 'Procurar Pacote',
                'all_items'         => 'Todos os Pacotes',
                'edit_item'         => 'Editar Pacote',
                'update_item'       => 'Atualizar Pacote',
                'add_new_item'      => 'Adicionar Pacote',
                'new_item_name'     => 'Novo nome de Pacote',
                'menu_name'         => 'Pacotes',
            ),
            'hierarchical' => true,
            'sort' => true,
            'args' => array( 'orderby' => 'term_order' ),
            'rewrite' => array( 'slug' => 'pacotes' ),
            'show_admin_column' => true
        )
    );

     register_taxonomy( 'categorias-do-produto', 'produtos',
        array(
            'labels' => array(
                'name'              => 'Categorias do Produto',
                'singular_name'     => 'Categoria',
                'search_items'      => 'Procurar Categoria',
                'all_items'         => 'Todos as Categorias',
                'edit_item'         => 'Editar Categoria',
                'update_item'       => 'Atualizar Categoria',
                'add_new_item'      => 'Adicionar Categoria',
                'new_item_name'     => 'Novo nome de Categoria',
                'menu_name'         => 'Categoria',
            ),
            'hierarchical' => true,
            'sort' => true,
            'args' => array( 'orderby' => 'term_order' ),
            'rewrite' => array( 'slug' => 'categorias-do-produto' ),
            'show_admin_column' => true
        )
    );
     
}
add_action( 'init', 'wpmc_register_taxonomies' );