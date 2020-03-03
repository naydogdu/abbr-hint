<?php 
defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class ABBR_Hint {

	public function __construct(){
    add_action('init', array($this,'init')); 
    register_activation_hook(__FILE__, array($this,'plugin_activate')); 
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); 
	}
	
	public function init() {		
		$this->register_hint_content_type();
		add_filter('the_content', array($this,'filter_content_for_hinting')); 
	}
	
	public function plugin_activate(){  
    $this->hint_init();
    //flush permalinks - pas besoin
		//flush_rewrite_rules();
	}
	
	public function plugin_deactivate(){
		//flush permalinks - pas besoin
		//flush_rewrite_rules();
	}
	
	public function register_hint_content_type(){
    /* on créé le post type */
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
		/* arguments */
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
		/* on enregistre */
		register_post_type('hint', $args);
	}

	public function filter_content_for_hinting( $content ) {
	
		global $wpdb;

		/* on s'assure d'être sur une vue Singular */
		if( !is_singular() )
			return $content;
		
		/* on stock le post type */
    $post_type = 'hint'; 
		
		/* on passe par get_results pour de meilleures performances, notamment s'il y a beaucoup de contenus. */
    $hints = $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 
				$post_type 
			)
		);

    /* On sort si aucun résultat */
    if ( !$hints )
			return $content;
		
		/* On déclare la variable $abbrs qui va servir à détecter les correspondances dans le contenu */
		$abbrs = '';
		
		/* on alimente avec les abbréviations disponibles */
		foreach( $hints as $hint ) 
			$abbrs .= ( !empty( $abbrs ) ? '|' : '(' ) . esc_attr( $hint->post_title );
		
		/* on ferme la parenthèse si elle a été ouverte, c-à-d si non vide */
		if( !empty( $abbrs ) )
			$abbrs .= ')';
		
		/* on détecte si au moins un des mots se trouve dans le contenu */
		$check = preg_match_all($abbrs, $content, $matches);
		
		/* on sort si aucune correspondance */
		if( !$check || !isset( $matches[0] ) )
			return $content;
			
		/* on commence notre boucle pour encapsuler un à un les mots par la balise <abbr> */
		foreach( $matches[0] as $ms ) {
			/* on récupère, maintenant qu'on en a besoin, le titre de l'abbréviation, stocké via la colonne "post_excerpt" */
			$excerpt = $wpdb->get_col( 
				$wpdb->prepare( 
					"SELECT post_excerpt FROM {$wpdb->posts} WHERE post_title = %s and post_status = 'publish' LIMIT 1", 
					esc_attr( $ms )
				)
			);
			/* si on a bien un titre on encapsule via la fonction Html */
			if( isset( $excerpt[0] ) && !empty( $excerpt[0] ) )
				$content = preg_replace('/\b'.$ms.'\b/', $this->Html($ms, $excerpt[0]), $content, 1);
			
		}
		
		/* Pfiou !! On retourne le contenu "amélioré" avec une meilleure #a11y ! */
		return $content;
		
	}
	
	public function Html($text='', $explanation='') {
		
		if( empty( $text ) )
			return;
		
		$pattern = '';
		
		/*
		* On ajoute la balise <abbr>, et l'attribut title si $explanation n'est pas vide 
		* Support attribut "lang" à venir 
		* à tester avec WPML
		*/
		$pattern .= '<abbr' . ( !empty( $explanation ) ? ' title="'. $explanation . '"' : '' ) . '>';		
			$pattern .= $text;
		$pattern .= '</abbr>';
		
		return $pattern;
		
	}
	
}

