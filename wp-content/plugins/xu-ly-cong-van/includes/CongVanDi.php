<?php

class CongVanDi extends CongVanBase
{
    const ADMIN_MENU_SLUG = 'cong-van-di';
    const POST_TYPE = 'cong-van-di';

    private $noi_nhan;

    /**
     * @return mixed
     */
    public function getNoiNhan()
    {
        return $this->noi_nhan;
    }

    /**
     * @param mixed $noi_nhan
     */
    public function setNoiNhan($noi_nhan)
    {
        $this->noi_nhan = $noi_nhan;
    }



    public function generate_content() {
        $tpl = 'cong_van_di_content.twig';
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
        return $content;
    }

    public static function register_my_post_type() {
        register_post_type(
            self::POST_TYPE,
            array(
                'label' => 'Công văn đi',
                'labels' => array(
                    'name' => 'Công văn đi',
                    'singular_name' => 'Công văn đi',
                    'add_new' => 'Thêm mới',
                    'add_new_item' => 'Thêm mới công văn đi',
                    'edit_item' => 'Sửa',
                    'new_item' => 'Tạo mới',
                    'view_item' => 'Xem',
                    'view_items' => 'Xem',
                    'search_items' => 'Tìm kiếm công văn đi',
                    'all_items' => 'Toàn bộ',
                    'archives' => 'Công văn đi lưu trữ',
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
    }
}