<?php

class CongVanDen extends CongVanBase
{
    const ADMIN_MENU_SLUG = 'cong-van-den';
    const POST_TYPE = 'cong-van-den';

    private $tinh_trang_xu_ly = 1;

    /**
     * @return int
     */
    public function get_tinh_trang_xu_ly()
    {
        return $this->tinh_trang_xu_ly;
    }

    /**
     * @param int $tinh_trang_xu_ly
     */
    public function set_tinh_trang_xu_ly($tinh_trang_xu_ly)
    {
        $options_file = dirname(__DIR__).'/settings/tinh_trang_xu_ly.csv';
        $keyValuePair = CongVanCustomContent::get_hash_from_file($options_file);
        $this->tinh_trang_xu_ly = $keyValuePair[$tinh_trang_xu_ly];
    }
    
    public function generate_content() {
        //$tpl = file_get_contents(dirname(dirname(__FILE__)).'/tpls/cong_van_den_content.twig');
        $tpl = 'cong_van_den_content.twig';
        $custom_fields = CongVanDenCustomContent::get_custom_fields();
        $data = array();
        foreach ($custom_fields as $field) {
            $get_method = 'get_'.$field['name'];
            $data[$field['name']] = $this->$get_method();
            if ($field['name'] == CongVanCustomContent::FIELD_TOAN_VAN) {
                $data[$field['name']] = $this->get_toan_van()['url'];
            }
        }
        $context = Timber::get_context();
        $context['post'] = $data;
        $content = Timber::compile(array($tpl), $context);
        //$content = Utils::parse($tpl, $data);
        return $content;
    }

    public static function register_my_post_type() {
        register_post_type(
            self::POST_TYPE,
            array(
                'label' => 'Công văn đến',
                'labels' => array(
                    'name' => 'Công văn đến',
                    'singular_name' => 'Công văn đến',
                    'add_new' => 'Thêm mới',
                    'add_new_item' => 'Thêm mới công văn đến',
                    'edit_item' => 'Sửa',
                    'new_item' => 'Tạo mới',
                    'view_item' => 'Xem',
                    'view_items' => 'Xem',
                    'search_items' => 'Tìm kiếm công văn đến',
                    'all_items' => 'Toàn bộ',
                    'archives' => 'Công văn đến lưu trữ',
                ),
                'public' => true,
                'show_ui' => true,
                'menu_position' => 5,
                'supports' => array('title', 'comments'),
                'has_archive' => true,
                'taxonomies' => array('category'),
                'query_var' => true,
                'rewrite' => true,
            )
        );

        /* IMPORTANT: Only use once if you have to.  */
        //flush_rewrite_rules( false );
    }
}