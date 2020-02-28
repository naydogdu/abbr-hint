<?php 
defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class ABBR_Hint {

	public function __construct(){

    add_action('init', array($this,'init')); //register location content type

    register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
		
	}
	
	public function init() {		
		$this->register_hint_content_type();
		add_filter('the_content', array($this,'filter_content_for_hinting')); 
	}
	
	public function plugin_activate(){  

    $this->hint_init();
    flush_rewrite_rules();
		
	}
	
	//trigered on deactivation of the plugin (called only once)
	public function plugin_deactivate(){
		
		//flush permalinks
		flush_rewrite_rules();
		
	}
	
	//register the hint content type
	public function register_hint_content_type(){
    //Labels for post type
    $labels = array(
			'name'               => __('Hint', 'abbr_hint'),
			'singular_name'      => __('Hint', 'abbr_hint'),
			'menu_name'          => __('Hints', 'abbr_hint'),
			'name_admin_bar'     => __('Hint', 'abbr_hint'),
			'add_new'            => __('Add New', 'abbr_hint'), 
			'add_new_item'       => __('Add New Hint', 'abbr_hint'),
			'new_item'           => __('New Hint', 'abbr_hint'), 
			'edit_item'          => __('Edit Hint', 'abbr_hint'),
			'view_item'          => __('View Hint', 'abbr_hint'),
			'all_items'          => __('All Hints', 'abbr_hint'),
			'search_items'       => __('Search Hints', 'abbr_hint'),
			'parent_item_colon'  => __('Parent Hint:', 'abbr_hint'), 
			'not_found'          => __('No Hints found.', 'abbr_hint'), 
			'not_found_in_trash' => __('No Hints found in Trash.', 'abbr_hint'),
		);
		//arguments for post type
		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'publicly_queryable'=> true,
			'show_ui'           => true,
			'show_in_nav'       => false,
			'show_in_menu'      => 'tools.php',
			'query_var'         => true,
			'hierarchical'      => false,
			'supports'          => array('title', 'excerpt'),
			'has_archive'       => false,
			'menu_position'     => 20,
			'show_in_admin_bar' => true,
			'menu_icon'         => 'dashicons-editor-help',
			'rewrite'            => false
		);
		//register post type
		register_post_type('hint', $args);
	}

	public function filter_content_for_hinting( $content ) {
	
		// WORK IN PROGRESS
		
		// query hints
		
		// search registered hints
		
		return $content;
	}
	
}

