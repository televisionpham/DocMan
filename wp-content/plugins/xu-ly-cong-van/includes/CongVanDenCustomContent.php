<?php

/**
 * Created by PhpStorm.
 * User: vanpt
 * Date: 9/6/2017
 * Time: 11:01 PM
 */
class CongVanDenCustomContent
{
    const PREFIX = 'cong_van_den_custom_content_';
    const CUSTOM_FIELDS_ID = 'cong-van-den-custom-fields';
    const NONCE_ACTION = 'cong_van_den_update_custom_content_fields';
    const NONCE_NAME = 'cong_van_den_custom_content_fields_nonce';

    public static function create_meta_box() {
        add_meta_box(
            self::CUSTOM_FIELDS_ID,
            'Công văn đến',
            'CongVanDenCustomContent::print_custom_fields',
            CongVanDen::POST_TYPE,
            'normal',
            'high',
            CongVanDen::POST_TYPE
        );
    }

    public static function print_custom_fields($post, $callback_args='') {               
        $tpl = file_get_contents(dirname(dirname(__FILE__)).'/tpls/admin_cong_van_den.twig');
        $custom_fields = self::get_custom_fields();
        foreach ($custom_fields as &$field) {
            $field_name = $field['name'];            
            if (empty($field['value'])) {
                if ($field_name == 'toan_van') {
                    $field['value'] = get_post_meta($post->ID, $field['name'], true);                            
                } else {
                    $field['value'] = htmlspecialchars(get_post_meta($post->ID, $field['name'], true));
                }
            }            
        }                
        $output = Timber::compile_string($tpl, $custom_fields);
        print '<div class="form-wrap">';
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
        print $output;
        print '</div>';
    }

    public static function save_custom_fields($post_id, $post) {        
        if (!empty($_POST) && check_admin_referer(self::NONCE_ACTION, self::NONCE_NAME)) {
            if ($post->post_type != CongVanDen::POST_TYPE) {
                return;
            }
            $toan_van_old_value = get_post_meta($post_id, "toan_van", true);            
            $cong_van_den = new CongVanDen();            
            $custom_fields = self::get_custom_fields();
            foreach ($custom_fields as $field) {                
                if ( isset( $_POST[ self::PREFIX . $field['name'] ] ) )
				{                    
					$value = trim($_POST[ self::PREFIX . $field['name'] ]);
					// Auto-paragraphs for any WYSIWYG
					if ( $field['type'] == 'wysiwyg' )
					{
						$value = wpautop( $value );
                    }                    
                    $set_method = 'set_'.$field['name'];
                    $cong_van_den->$set_method($value);
					update_post_meta( $post_id, $field[ 'name' ], $value );
				}				
				else
				{
					update_post_meta( $post_id, $field[ 'name' ], '' );
                }
                // Tệp đính kèm                                
                if ($field['name'] == 'toan_van') {
                    $urls = array();
                    for ($i = 0; $i < 10; $i++) {
                        $name = self::PREFIX. $field['name'].'_'.$i;                        
                        if (isset($_POST[$name])) {                            
                            $url = str_replace("\'", '', $_POST[$name]);                                 
                            $dom = new DOMDocument();
                            $dom->loadHTML($url);
                            $xpath = new DOMXPath($dom);
                            $nodes = $xpath->query('//a/@href');                            
                            if ($nodes->length > 0)
                            {
                                foreach($nodes as $href) {                                
                                    $urls[] = $href->nodeValue;                               
                                }
                            } else {
                                $urls[] = $url;
                            }
                        }
                    }                    
                    $cong_van_den->set_toan_van($urls);
                    update_post_meta( $post_id, $field[ 'name' ], $urls );
                    foreach ($toan_van_old_value as $old_value) {
                        $found = false;
                        foreach ($urls as $url) {
                            if ($url == $old_value) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $attachment_id = Utils::getIdFromGuid($old_value);
                            wp_delete_attachment($attachment_id);
                        }
                    }
                }
            }        
            
            // Đưa post vào category Công văn đến
            remove_action('save_post', 'CongVanDenCustomContent::save_custom_fields', 1, 2);
            $content = $cong_van_den->generate_content();              
            $my_post = array(
                'ID' => $post->ID,
                'post_title' => $cong_van_den->get_so_van_ban().'/'.$cong_van_den->get_ky_hieu(),
                'post_content' => $content,
            );
            wp_update_post($my_post);
            $category = get_category_by_slug('cong-van-den');
            wp_set_post_categories( $post->ID, array( $category->term_id ) );
            add_action('save_post', 'CongVanDenCustomContent::save_custom_fields', 1, 2);
        }
    }

    public static function get_custom_fields() {
        $result = array();
        $fields_file = dirname(dirname(__FILE__)).'/settings/cong_van_den_fields.csv';
        $handle = fopen($fields_file, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $parts = explode(';', $line);
                $result[$parts[0]] = array(
                    'name' => $parts[0],
                    'title' => $parts[1],
                    'type' => $parts[2],
                );
                if ($parts[2] === 'lanh_dao') {
                    $users = get_users();
                    $result[$parts[0]]['options'][0] = '';
                    foreach ($users as $user) {
                        $result[$parts[0]]['options'][$user->ID] = $user->display_name;
                    }
                }
            }
            fclose($handle);
            $special_fields = array('the_loai_van_ban', 'loai_ban');
            foreach ($special_fields as $key) {
                $options_file = dirname($fields_file).'/'.$key.'.txt';
                $result[$key]['options'] =
                    CongVanCustomContent::get_values_from_file($options_file);
            }
            $special_fields = array('do_khan', 'do_mat', 'tinh_trang_xu_ly', 'thoi_han_bao_quan');
            foreach ($special_fields as $key) {
                $options_file = dirname($fields_file).'/'.$key.'.csv';
                $result[$key]['options'] =
                    CongVanCustomContent::get_hash_from_file($options_file);
            }
        } else {
            print '<p>Không tìm thấy file '.$fields_file.'</p>';
        }
        return $result;
    }
}