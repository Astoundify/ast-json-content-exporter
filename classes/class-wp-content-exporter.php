<?php

namespace AST\Content_Importer;

/**
 * The class that initialize the exporter menu page.
 *
 * @author Andrei Lupu <euthelup@gmail.com>.
 *
 * @since 1.0.0
 */
class WP_Submenu_Page {
    private $types = array();

    private $current_type = null;

    private $type_obj = null;

    /**
     * Autoload method
     * @return void
     */
    public function __construct() {
        add_action( 'admin_menu', array(&$this, 'register_sub_menu') );

        $this->types = array(
            'menu',
            'menu-items',
            'post',
            'page',
            'product',
            'term',
            'user',
            'vendor',
            'thememod',
            'setting'
        );

        if ( isset( $_GET['display_type'] ) && in_array( $_GET['display_type'], $this->types ) ) {
            $this->current_type = $_GET['display_type'];
        }
    }
 

    /**
     * Load types subclasses.
     * @return void
     */
    function load_types(){
        foreach ( glob( plugin_dir_path( __FILE__ ) . "class-abstract-*.php" ) as $filename) {
            include_once $filename;
        }

        foreach (glob( plugin_dir_path( __FILE__ ) . "class-type-*.php" ) as $filename) {
            include_once $filename;
        }

    }

    /**
     * Register submenu
     * @return void
     */
    public function register_sub_menu() {
        add_submenu_page(
            'tools.php', esc_html__( 'Content Exporter' ), esc_html__( 'Content Exporter' ), 'manage_options', 'content-exporter-page', array(&$this, 'submenu_page_callback')
        );
    }
 
    /**
     * Render submenu
     * @return void
     */
    public function submenu_page_callback() {
        
        $this->load_types();


        echo '<div class="wrap">';
        echo '<h2>' . \esc_html__( 'Content Exporter' ) . '</h2>';

        echo '<div class="content-wrap">';
        echo '<div class="ast-import-links-wrap">';
        echo '<ul class="ast-import-links" style="display: flex;width: 80vw;justify-content: space-around;margin: 10px 5px;">';
        $this->display_links();
        echo '</ul>';
        echo '</div>';
        if ( ! isset( $_GET['display_type'] ) || ! in_array( $_GET['display_type'], $this->types ) ) {
            
            echo '<p>No type selected. Nothing to show!</p>';
            
        } else {
            $type_obj = $this->get_current_type_object();

            if ( ! empty( $type_obj ) ) {
                $type_obj->display_data();
            } else {
                echo '<p>This type does not exists!</p>';
            }
        }

        echo '</div>';
        echo '</div>';
    }

    public function display_links() {

        if ( empty( $this->types ) ) {
            return false;
        }

        foreach( $this->types as $type ) { ?>
            <li>
                <a href="<?php echo admin_url( 'tools.php?page=content-exporter-page&display_type=' . \esc_attr( $type ) ); ?>"><?php echo $type; ?></a>
            </li>
        <?php }
    }

    private function get_current_type_object(){
        if ( ! empty( $this->type_obj ) ) {
            return $this->type_obj;
        }

        $classname = $this->get_current_type_class();

        if ( empty( $classname ) ) {
            return $false;
        }

        $this->type_obj = new $classname();

        return $this->type_obj;
    }

    private function get_current_type_class() {
        $words = explode( '-', $this->current_type );

        $words = array_map( 'ucwords', $words );

        $classname = 'AST\Content_Importer\\Type_' . implode( '_', $words );

        if ( class_exists( $classname ) ) {
            return $classname;
        }

        return false;
    }

    public function get_current_type() {
        return $this->current_type;
    }

}