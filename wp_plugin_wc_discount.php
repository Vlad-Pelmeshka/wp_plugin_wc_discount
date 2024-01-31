<?php
/*
Plugin Name: Woo Custom Discount
Description: This plugin will allow am to manage discounts
Version: 1.0
Author: Vladislav Cheredaiko
Author URI: https://www.linkedin.com/in/vladislavweb/
*/

if (!defined('ABSPATH')) {
    exit;
}

class Custom_Woo_Discount_Plugin {

    public function __construct() {
        
        // Admin part
        add_action('admin_menu',                    array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts',         array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_custom_woo_discount',   array($this, 'ajax_custom_woo_discount_callback'));

        //Public cart part
        add_action('wp_enqueue_scripts',                    array($this, 'enqueue_scripts'));
        add_action('woocommerce_cart_contents',             array($this, 'cart_content_block'));
        add_action('wp_ajax_add_free_product_woo_discount', array($this, 'ajax_add_free_product_woo_discount_callback'));
        add_action('woocommerce_before_calculate_totals',   array($this, 'set_price_to_zero_for_free_products'));
    }

    function set_price_to_zero_for_free_products($cart) {
        foreach ($cart->get_cart() as $cart_item) {
            // var_dump($cart_item);
            if (isset($cart_item['free_custom']) && $cart_item['free_custom'] === true) {
                $cart_item['data']->set_price(0);
            }
        }
    }


    public function add_admin_menu() {

        add_menu_page(
            'Woo Discount',
            'Woo Discount Management',
            'manage_options',
            'woo_discount_page',
            array($this, 'render_admin_page'),
            'dashicons-calculator',
            100
        );
    }

    public function enqueue_scripts() {
        
        wp_enqueue_style('custom-woo-discount-public-style',    plugin_dir_url(__FILE__) . 'dist/public.css', [] );
        wp_enqueue_script('custom-woo-discount-public-script',  plugin_dir_url(__FILE__) . 'dist/public.js', array('jquery'), '1.0', true);
    }

    public function admin_enqueue_scripts() {
        
        wp_enqueue_style('custom-woo-discount-style',   plugin_dir_url(__FILE__) . 'dist/main.css', [] );
        wp_enqueue_script('custom-woo-discount-script', plugin_dir_url(__FILE__) . 'dist/main.js', array('jquery'), '1.0', true);
    }

    public function render_admin_page() {
        
        $template_path  = plugin_dir_path(__FILE__) . 'templates/admin-page-template.php';
        $options        = json_decode(get_option('custom_woo_discount'), true);


        $data = array(
            'title'     => 'Custom Woo Discount',
            'category'  => self::get_product_cat_list(),
            'data'      => array(
                'discount_cat'        => $options['discount_cat']       ?: get_option('default_product_cat'),
                'discount_count'      => $options['discount_count']     ?: 1,
                'discount_cat_free'   => $options['discount_cat_free']  ?: get_option('default_product_cat'),
            )
        );

        if (file_exists($template_path)) {
            include $template_path;
        }
    }

    public function ajax_custom_woo_discount_callback() {
        if (isset($_POST['data'])) {

            $data = $_POST['data'];

            self::ajax_validation($data);

            update_option('custom_woo_discount', json_encode($data));

            wp_send_json_success(['success' => 'Data success updated']);
            
        }

        wp_send_json_error(['error' => 'Data incorrect']);
    }

    public function ajax_add_free_product_woo_discount_callback() {
        if (isset($_POST['product_id'])) {

            $product_id = $_POST['product_id'];

            $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, [],['free_custom'=>true]);
            
            

            wp_send_json_success(['success' => 'Data success updated']);
            wp_die();
            
        }

        wp_send_json_error(['error' => 'Data incorrect']);
    }

    public function cart_content_block(){
        $custom_woo_discount = get_option('custom_woo_discount');

        if(!$custom_woo_discount)
            return;

        $data = json_decode($custom_woo_discount, true);

        $products_count = self::get_cart_product_count_by_category($data['discount_cat']);

        if($products_count >= $data['discount_count']):

            if(self::is_free_product_in_cart())
                return;

            $free_products = self::get_products_by_category($data['discount_cat_free']);

            if($free_products):
            ?>
            <tr>
                    <td></td>
                    <td>Product Free</td>
                    <td>
                        <select name="woo_free_discount_product" id="woo-free-discount-product">
                            <?php foreach($free_products as $product_key => $free_product): ?>
                                <option value="<?php echo $product_key; ?>"><?php echo $free_product; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button id="add_product_free">Add</button>
                    </td>
                </tr>
            <?php
            endif;
        endif;
        
    }

    private function is_free_product_in_cart(){
        foreach (WC()->cart->get_cart() as $cart_item) {
            if (isset($cart_item['free_custom']) && $cart_item['free_custom'] === true) {
                return true;
            }
        }
        return false;
    }

    private function ajax_validation($data){
        $success    = true;
        $result     = [];
        
        if(empty($data['discount_cat']) || !term_exists((int)$data['discount_cat'], 'product_cat')){

            $success = false;
            $result['error'] = 'Discount category incorrect';
        }
        
        if(empty($data['discount_count']) || $data['discount_count'] < 1 || $data['discount_count'] > 999){

            $success = false;
            $result['error'] = 'Discount count incorrect';
        }

        if(empty($data['discount_cat_free']) || !term_exists((int)$data['discount_cat_free'], 'product_cat')){

            $success = false;
            $result['error'] = 'Free products category incorrect';
        }

        if(!$success){
            wp_send_json_error($result);
            wp_die();
        }

        return;
    }

    private function get_cart_product_count_by_category($category_id) {
        $count = 0;
        
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            
            if (has_term($category_id, 'product_cat', $product_id)) {
                $count += $cart_item['quantity'];
            }
        }
        
        return $count;
    }

    private function get_products_by_category($category_id) {
        $products = array();
    
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category_id,
                ),
            ),
        );
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product_id     = get_the_ID();
                $product_name   = get_the_title();
    
                $products[$product_id] = $product_name;
            }
        }
    
        wp_reset_postdata();
    
        return $products;
    }

    private function get_product_cat_list(){
        $product_categories = get_terms('product_cat', array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
        ));
        
        $category_array = array();
        
        foreach ($product_categories as $category) {
            $category_array[$category->term_id] = $category->name;
        }

        return $category_array;
    }

}

new Custom_Woo_Discount_Plugin();