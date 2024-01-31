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

class Text_Manage_Plugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    
        // add_action('wp_ajax_text_manage_search', array($this, 'ajax_text_manage_search_callback'));
        // add_action('wp_ajax_text_manage_replace', array($this, 'ajax_text_manage_replace_callback'));
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

        $data_to_pass = array(
            'example_variable' => 'Hello, World!',
            'another_variable' => 123,
        );

        if (file_exists($template_path)) {
            // extract($data_to_pass);
            include $template_path;
        }
    }

    /*
    public function ajax_text_manage_search_callback() {
        if (isset($_POST['search_text'])) {
            
            global $wpdb;
            $search_text = sanitize_text_field($_POST['search_text']);
            $results = array(
                'title' => array(),
                'content' => array(),
                'meta_title' => array(),
                'meta_description' => array(),
            );
    
            // Search in Title
            $title_posts = $wpdb->get_col($wpdb->prepare("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'post'
                AND post_status = 'publish'
                AND BINARY post_title LIKE BINARY '%" . $wpdb->esc_like($search_text) . "%'"));
                
            foreach ($title_posts as $post_id) {
                $post = get_post($post_id);
                $post->result_string = $this->strip_found_string(wp_strip_all_tags($post->post_title), $search_text);
                $results['title'][] = $post;
            }
    
            // Search in Content
            $content_posts = $wpdb->get_col($wpdb->prepare("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'post'
                AND post_status = 'publish'
                AND post_content LIKE BINARY '%" . $wpdb->esc_like($search_text) . "%'"));
            foreach ($content_posts as $post_id) {
                $post = get_post($post_id);
                $post->result_string = $this->strip_found_string(wp_strip_all_tags($post->post_content), $search_text);
                $results['content'][] = $post;
            }
    
            // Search in Meta-title
            $meta_title_posts = $wpdb->get_col($wpdb->prepare("
                SELECT post_id
                FROM $wpdb->postmeta
                WHERE meta_key = '_yoast_wpseo_title'
                AND BINARY meta_value LIKE BINARY '%" . $wpdb->esc_like($search_text) . "%'"));
            foreach ($meta_title_posts as $post_id) {
                $post = get_post($post_id);
                $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
                $post->result_string = $this->strip_found_string(wp_strip_all_tags($meta_title), $search_text);
                $results['meta_title'][] = $post;
            }
    
            // Search in Meta-description
            $meta_description_posts = $wpdb->get_col($wpdb->prepare("
                SELECT post_id
                FROM $wpdb->postmeta
                WHERE meta_key = '_yoast_wpseo_metadesc'
                AND BINARY meta_value LIKE BINARY '%" . $wpdb->esc_like($search_text) . "%'"));
            foreach ($meta_description_posts as $post_id) {
                $post = get_post($post_id);
                $meta_description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
                $post->result_string = $this->strip_found_string(wp_strip_all_tags($meta_description), $search_text);
                $results['meta_description'][] = $post;
            }
    
            wp_send_json($results);
        }
    }

    public function ajax_text_manage_replace_callback() {

        // var_dump($_POST);
            
        if (isset($_POST['type'], $_POST['replace_text'])) {

            global $wpdb;

            $type           = sanitize_text_field($_POST['type']);
            $replaceText    = sanitize_text_field($_POST['replace_text']);
            $replaced       = sanitize_text_field($_POST['replaced_text']);

            switch($type){ 
                case 'content':
                    $wpdb->query(
                        $wpdb->prepare("
                        UPDATE $wpdb->posts 
                        SET post_content = REPLACE(post_content, '" . $wpdb->esc_like($replaced) . "', '" . $wpdb->esc_like($replaceText) . "')
                        WHERE post_type = 'post'
                        AND post_status = 'publish'
                        AND post_content LIKE BINARY '%" . $wpdb->esc_like($replaced) . "%'")
                    );
                    break;
                    
                case 'title':
                    $wpdb->query(
                        $wpdb->prepare("
                        UPDATE $wpdb->posts 
                        SET post_title = REPLACE(post_title, '" . $wpdb->esc_like($replaced) . "', '" . $wpdb->esc_like($replaceText) . "')
                        WHERE post_type = 'post'
                        AND post_status = 'publish'
                        AND post_title LIKE BINARY '%" . $wpdb->esc_like($replaced) . "%'")
                    );
                    break;

                case 'meta-title':
                    $wpdb->query(
                        $wpdb->prepare("
                        UPDATE $wpdb->postmeta 
                        SET meta_value = REPLACE(meta_value, '" . $wpdb->esc_like($replaced) . "', '" . $wpdb->esc_like($replaceText) . "')
                        WHERE meta_key = '_yoast_wpseo_title'
                        AND meta_value LIKE BINARY '%" . $wpdb->esc_like($replaced) . "%'")
                    );
                    break;

                case 'meta-description':
                    $wpdb->query(
                        $wpdb->prepare("
                        UPDATE $wpdb->postmeta 
                        SET meta_value = REPLACE(meta_value, '" . $wpdb->esc_like($replaced) . "', '" . $wpdb->esc_like($replaceText) . "')
                        WHERE meta_key = '_yoast_wpseo_metadesc'
                        AND meta_value LIKE BINARY '%" . $wpdb->esc_like($replaced) . "%'")
                    );
                    break;
            }
    
            wp_send_json('Success');
        }
    }


    private function strip_found_string($text, $target_char, $distance = 10) {

        $len        = strlen($target_char);
        $result     = ''; 
        $matches    = array(); 
    
        $last_match_pos = 0;
    
        preg_match_all("/$target_char/", $text, $matches, PREG_OFFSET_CAPTURE);
    
        foreach ($matches[0] as $key => $match) {
    
            $position   = $match[1]; 
            $start      = max(0, $position - $distance );
            $end        = $position + $len;
    
            if($last_match_pos == 0){
    
                $result .= ($start > $last_match_pos ? '...' : '') . substr($text, $start, $position - $start + $len  );
    
            }  else{
    
                if($start > $last_match_pos + $distance){
                    $result .= substr($text, $last_match_pos, $distance) . '...' . substr($text, $start, $position - $start + $len);
                }else{
                    $result .= substr($text, $last_match_pos, $position - $last_match_pos + $len);
                }
            }
    
            $last_match_pos = $end;
            
        }
    
    
    
        $result .= substr($text, $last_match_pos, $distance);
        $result .= (strlen(substr($text, $last_match_pos)) > $distance) ? '...' : '';
        $result = preg_replace("/$target_char/", '<b>$0</b>', $result);
    
        return $result;
    }
    */
}

new Text_Manage_Plugin();