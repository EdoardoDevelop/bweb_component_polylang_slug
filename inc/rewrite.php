<?php

require_once('wp-polylang-translate-rewrite-slugs/polylang-translate-rewrite-slugs.php');

class bcpolylangslugrewrite {
    private $bc_polylangslug_field_cpt;
    private $bc_polylangslug_field_tax;

	public function __construct(){
		$this->bc_polylangslug_field_cpt = get_option( 'bc_polylangslug_slug_cpt' ); 
		$this->bc_polylangslug_field_tax = get_option( 'bc_polylangslug_slug_tax' ); 
        
        add_filter('pll_translated_post_type_rewrite_slugs', array( $this, 'rewrite_cpt' ));
        add_filter('pll_translated_taxonomy_rewrite_slugs', array( $this, 'rewrite_tax' ));

    }

    public function rewrite_cpt($post_type_translated_slugs){
        if(isset($this->bc_polylangslug_field_cpt['slugs'])){
            $post_type_translated_slugs = $this->bc_polylangslug_field_cpt['slugs'];
        }
        return $post_type_translated_slugs;
    }
    public function rewrite_tax($taxonomy_translated_slugs){
        if(isset($this->bc_polylangslug_field_tax['slugs'])){
            $taxonomy_translated_slugs = $this->bc_polylangslug_field_tax['slugs'];
        }
        return $taxonomy_translated_slugs;
    }
    

}
new bcpolylangslugrewrite();