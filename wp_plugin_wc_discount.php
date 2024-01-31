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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    
        add_action('wp_ajax_custom_woo_discount', array($this, 'ajax_custom_woo_discount_callback'));
     }

    public function add_admin_menu() {

        add_menu_page(
            'Woo Discount',
            'Woo Discount Management',
            'manage_options',
            'woo_discount_page',
            array($this, 'render_admin_page'),
            'dashicons-controls-repeat',
            100
        );
    }

    public function enqueue_scripts() {
        
        wp_enqueue_style('custom-woo-discount-style', plugin_dir_url(__FILE__) . 'dist/main.css', [] );
        wp_enqueue_script('custom-woo-discount-script', plugin_dir_url(__FILE__) . 'dist/main.js', array('jquery'), '1.0', true);
    }

    public function render_admin_page() {
        
        $template_path = plugin_dir_path(__FILE__) . 'templates/admin-page-template.php';

        $data = array(
            'title'     => 'Custom Woo Discount',
            'category'  => self::get_product_cat_list(),
        );

        $option = get_option('custom_woo_discount');

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

    private function ajax_validation($data){
        $success    = true;
        $result     = '';
        
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
            $result['error'] = 'Discount free category incorrect';
        }

        if(!$success){
            wp_send_json_error($result);
            wp_die();
        }

        return;
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