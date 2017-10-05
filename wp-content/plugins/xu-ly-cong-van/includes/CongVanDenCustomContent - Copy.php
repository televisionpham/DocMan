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
        $output = file_get_contents(dirname(dirname(__FILE__)).'/tpls/admin_cong_van_den.twig');
        $data = array();
        $custom_fields = self::get_custom_fields();
        foreach ($custom_fields as $field) {
            $field_name = $field['name'];
            if ($field_name === CongVanCustomContent::FIELD_TOAN_VAN) {
                $toan_van = get_post_meta($post->ID, $field_name, true);
                if (!empty($toan_van)) {
                    $field['value'] = $toan_van['url'];
                    $field['has_file'] = true;
                }
            }
            if (empty($field['value'])) {
                $field['value'] = htmlspecialchars(get_post_meta($post->ID, $field['name'], true));
            }
            $field['name'] = self::PREFIX . $field['name'];
            switch ($field['type']) {
                case 'lanh_dao':
                    $data[$field_name] = CongVanCustomContent::get_dropdown_element($field);
                    break;
                case 'uploader':
                    $data[$field_name] = CongVanCustomContent::get_uploader_element($field);
                    break;
                case 'date':
                    $data[$field_name] = CongVanCustomContent::get_datepicker_element($field);
                    break;
                case 'dropdown':
                    $data[$field_name] = CongVanCustomContent::get_dropdown_element($field);
                    break;
                case 'textarea':
                    $data[$field_name] = CongVanCustomContent::get_textarea_element($field);
                    break;
                case 'text':
                default:
                    $data[$field_name] = CongVanCustomContent::get_text_element($field);
                    break;
            }
            if ($field['type'] != 'date') {
                $data[$field_name] = '<div class="form-field form-required">' . $data[$field_name] . '</div>';
            } else {
                $data[$field_name] = '<div>'.$data[$field_name].'</div>';
            }
        }
        $output = Utils::parse($output, $data);
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
            $cong_van_den = new CongVanDen();

            if(!empty($_FILES[self::PREFIX.CongVanCustomContent::FIELD_TOAN_VAN]['name'])) {
                $supported_types = array('application/pdf');
                $arr_file_type = wp_check_filetype(basename($_FILES[self::PREFIX.'toan_van']['name']));
                $uploaded_type = $arr_file_type['type'];
                if (in_array($uploaded_type, $supported_types)) {
                    $upload = wp_upload_bits($_FILES[self::PREFIX.CongVanCustomContent::FIELD_TOAN_VAN]['name'],
                        null,
                        file_get_contents($_FILES[self::PREFIX.CongVanCustomContent::FIELD_TOAN_VAN]['tmp_name']));
                    if(isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {
                        add_post_meta($post_id, CongVanCustomContent::FIELD_TOAN_VAN, $upload);
                        update_post_meta($post_id, CongVanCustomContent::FIELD_TOAN_VAN, $upload);
                        $cong_van_den->set_toan_van($upload);
                    }
                }
            }
            $custom_fields = self::get_custom_fields();
            foreach ($custom_fields as $field) {
                if ($field['name'] == CongVanCustomContent::FIELD_TOAN_VAN) {
                    continue;
                }
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
				// if not set, then it's an unchecked checkbox, so blank out the value.
				else
				{
					update_post_meta( $post_id, $field[ 'name' ], '' );
				}
            }

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