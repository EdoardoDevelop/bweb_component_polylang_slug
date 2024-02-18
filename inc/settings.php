<?php
class bcpolylangslug {
    private $bc_polylangslug_field_cpt;
    private $bc_polylangslug_field_tax;

	public function __construct(){
		$this->bc_polylangslug_field_cpt = get_option( 'bc_polylangslug_slug_cpt' );  
		$this->bc_polylangslug_field_tax = get_option( 'bc_polylangslug_slug_tax' );  
		add_action( 'admin_menu', array( $this, 'bc_polylangslug_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'bc_polylangslug_page_init' ) );
        
        
    }

	public function bc_polylangslug_add_plugin_page() {
		add_submenu_page(
            'bweb-component',
			'Polylang Slug', // page_title
			'Polylang Slug', // menu_title
			'manage_options', // capability
			'polylang_slug', // menu_slug
			array( $this, 'bc_polylangslug_create_admin_page' ), // function
		);
	}

	public function bc_polylangslug_create_admin_page() {
        if (is_plugin_active('polylang/polylang.php')) {
        ?>

		<div class="wrap">
			<h2 class="wp-heading-inline">Slug</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'bc_polylangslug_option_group' );
					do_settings_sections( 'bc_polylangslug-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php 
        }else{
            echo '<h2 class="wp-heading-inline">Polylang non installato/attivo!</h2>';
        }
    }

	public function bc_polylangslug_page_init() {
		register_setting(
			'bc_polylangslug_option_group', // option_group
			'bc_polylangslug_slug_cpt' // option_name
		);
		register_setting(
			'bc_polylangslug_option_group', // option_group
			'bc_polylangslug_slug_tax' // option_name
		);

		add_settings_section(
			'bc_polylangslug_field_section', // id
			'', // title
			'', // callback
			'bc_polylangslug-admin' // page
		);

        add_settings_field(
            'bc_polylangslug_slug_cpt', // id
            'Custom Post Type', // title
            array($this,'bc_polylangslug_slug_cpt_callback'), // callback
            'bc_polylangslug-admin', // page
            'bc_polylangslug_field_section' // section
        );
        add_settings_field(
            'bc_polylangslug_slug_tax', // id
            'Taxonomies', // title
            array($this,'bc_polylangslug_slug_tax_callback'), // callback
            'bc_polylangslug-admin', // page
            'bc_polylangslug_field_section' // section
        );

        
	}


	
    public function bc_polylangslug_slug_cpt_callback() {
        global $polylang;
        $languages = $polylang->model->get_languages_list();
        $custom_post_types = get_post_types( array( 'public' => true, ), 'objects' );
        foreach ( $custom_post_types as $post_type_obj ):
            if($post_type_obj->name != 'post' && $post_type_obj->name != 'page' && $post_type_obj->name != 'attachment'):
                $labels = get_post_type_labels( $post_type_obj );
                echo '<div><input type="hidden" name="bc_polylangslug_slug_cpt[slugs]['.esc_attr( $post_type_obj->name ).']" value="'.esc_attr( $post_type_obj->name ).'" >';
                echo '<h3>'.$labels->name.'</h3>';
               
                foreach ($languages as $language) {
                    //print_r($language);
                    printf(
                        '%s<br><input type="text" name="bc_polylangslug_slug_cpt[slugs][%s][%s][rewrite][slug]" value="%s" ><br><br>',
                        $language->name,
                        esc_attr( $post_type_obj->name ),
                        $language->slug,
                        isset( $this->bc_polylangslug_field_cpt['slugs'][esc_attr( $post_type_obj->name )][$language->slug]['rewrite']['slug'] ) ? esc_attr( $this->bc_polylangslug_field_cpt['slugs'][esc_attr( $post_type_obj->name )][$language->slug]['rewrite']['slug']) : ''
                    );
                }
                echo '</div>';
            endif;
        endforeach;
	}

    public function bc_polylangslug_slug_tax_callback() {
        global $polylang;
        $languages = $polylang->model->get_languages_list();
        $taxonomies = get_taxonomies(array('public'   => true,'_builtin' => false), 'objects');
        foreach ( $taxonomies as $tax_type_key => $taxonomy ) {
            echo '<div><input type="hidden" name="bc_polylangslug_slug_tax[slugs]['.esc_attr( $taxonomy->name ).']" value="'.esc_attr( $taxonomy->name ).'" >';
            echo '<h3>'.$taxonomy->label.' ('.$taxonomy->name.')</h3>';
            
            foreach ($languages as $language) {
                //print_r($language);
                printf(
                    '%s<br><input type="text" name="bc_polylangslug_slug_tax[slugs][%s][%s]" value="%s" ><br><br>',
                    $language->name,
                    esc_attr( $taxonomy->name ),
                    $language->slug,
                    isset( $this->bc_polylangslug_field_tax['slugs'][esc_attr( $taxonomy->name )][$language->slug] ) ? esc_attr( $this->bc_polylangslug_field_tax['slugs'][esc_attr( $taxonomy->name )][$language->slug]) : ''
                );
            }
            echo '</div>';
        }

	}

}
new bcpolylangslug();