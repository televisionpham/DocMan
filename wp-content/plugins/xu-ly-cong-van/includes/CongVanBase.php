<?php

abstract class CongVanBase
{
    private $ma_dang_ky = 0;
    private $do_khan = 0;
    private $do_mat = 0;
    private $phan_loai_co_quan;

    // Trích ngang
    private $so_van_ban = 0;
    private $ky_hieu;
    private $ngay_dang_ky;
    private $ngay_van_ban;
    private $the_loai_van_ban;
    private $loai_ban = 1;
    private $nguoi_ky;
    private $so_trang = 0;
    private $tac_gia_van_ban;
    private $so_ban = 1;
    private $chuyen_de;
    private $trich_yeu;
    
    // Toàn văn nội dung văn bản
    private $toan_van;

    // Chuyển xử lý và yêu cầu xử lý
    private $chu_tri_xu_ly;
    private $phoi_hop_xu_ly;
    private $han_giai_quyet_xong;
    private $han_thu_hoi;
    private $noi_dung_yeu_cau_xu_ly;

    // Lưu trữ
    private $phong_luu_tru_so;
    private $muc_luc_ho_so_so;
    private $luu_ho_so_so;
    private $thoi_han_bao_quan = 0;
    private $ngay_luu_ho_so;

    /**
     * @return int
     */
    public function get_ma_dang_ky()
    {
        return $this->ma_dang_ky;
    }

    /**
     * @param int $ma_dang_ky
     */
    public function set_ma_dang_ky($ma_dang_ky)
    {
        $this->ma_dang_ky = $ma_dang_ky;
    }

    /**
     * @return int
     */
    public function get_do_khan()
    {
        return $this->do_khan;
    }

    /**
     * @param int $do_khan
     */
    public function set_do_khan($do_khan)
    {
        $options_file = dirname(__DIR__).'/settings/do_khan.csv';
        $keyValuePair = CongVanCustomContent::get_hash_from_file($options_file);
        $this->do_khan = $keyValuePair[$do_khan];
    }

    /**
     * @return int
     */
    public function get_do_mat()
    {
        return $this->do_mat;
    }

    /**
     * @param int $do_mat
     */
    public function set_do_mat($do_mat)
    {
        $options_file = dirname(__DIR__).'/settings/do_mat.csv';
        $keyValuePair = CongVanCustomContent::get_hash_from_file($options_file);
        $this->do_mat = $keyValuePair[$do_mat];
    }

    /**
     * @return mixed
     */
    public function get_phan_loai_co_quan()
    {
        return $this->phan_loai_co_quan;
    }

    /**
     * @param mixed $phan_loai_co_quan
     */
    public function set_phan_loai_co_quan($phan_loai_co_quan)
    {
        $this->phan_loai_co_quan = $phan_loai_co_quan;
    }

    /**
     * @return int
     */
    public function get_so_van_ban()
    {
        return $this->so_van_ban;
    }

    /**
     * @param int $so_van_ban
     */
    public function set_so_van_ban($so_van_ban)
    {
        $this->so_van_ban = $so_van_ban;
    }

    /**
     * @return mixed
     */
    public function get_ky_hieu()
    {
        return $this->ky_hieu;
    }

    /**
     * @param mixed $ky_hieu
     */
    public function set_ky_hieu($ky_hieu)
    {
        $this->ky_hieu = $ky_hieu;
    }

    /**
     * @return mixed
     */
    public function get_ngay_dang_ky()
    {
        return $this->ngay_dang_ky;
    }

    /**
     * @param mixed $ngay_dang_ky
     */
    public function set_ngay_dang_ky($ngay_dang_ky)
    {
        $this->ngay_dang_ky = $ngay_dang_ky;
    }

    /**
     * @return mixed
     */
    public function get_ngay_van_ban()
    {
        return $this->ngay_van_ban;
    }

    /**
     * @param mixed $ngay_van_ban
     */
    public function set_ngay_van_ban($ngay_van_ban)
    {
        $this->ngay_van_ban = $ngay_van_ban;
    }

    /**
     * @return mixed
     */
    public function get_the_loai_van_ban()
    {
        return $this->the_loai_van_ban;
    }

    /**
     * @param mixed $the_loai_van_ban
     */
    public function set_the_loai_van_ban($the_loai_van_ban)
    {
        $this->the_loai_van_ban = $the_loai_van_ban;
    }

    /**
     * @return int
     */
    public function get_loai_ban()
    {
        return $this->loai_ban;
    }

    /**
     * @param int $loai_ban
     */
    public function set_loai_ban($loai_ban)
    {
        $this->loai_ban = $loai_ban;
    }

    /**
     * @return mixed
     */
    public function get_nguoi_ky()
    {
        return $this->nguoi_ky;
    }

    /**
     * @param mixed $nguoi_ky
     */
    public function set_nguoi_ky($nguoi_ky)
    {
        $this->nguoi_ky = $nguoi_ky;
    }

    /**
     * @return int
     */
    public function get_so_trang()
    {
        return $this->so_trang;
    }

    /**
     * @param int $so_trang
     */
    public function set_so_trang($so_trang)
    {
        $this->so_trang = $so_trang;
    }

    /**
     * @return mixed
     */
    public function get_tac_gia_van_ban()
    {
        return $this->tac_gia_van_ban;
    }

    /**
     * @param mixed $tac_gia_van_ban
     */
    public function set_tac_gia_van_ban($tac_gia_van_ban)
    {
        $this->tac_gia_van_ban = $tac_gia_van_ban;
    }

    /**
     * @return int
     */
    public function get_so_ban()
    {
        return $this->so_ban;
    }

    /**
     * @param int $so_ban
     */
    public function set_so_ban($so_ban)
    {
        $this->so_ban = $so_ban;
    }

    /**
     * @return mixed
     */
    public function get_chuyen_de()
    {
        return $this->chuyen_de;
    }

    /**
     * @param mixed $chuyen_de
     */
    public function set_chuyen_de($chuyen_de)
    {
        $this->chuyen_de = $chuyen_de;
    }

    /**
     * @return mixed
     */
    public function get_trich_yeu()
    {
        return $this->trich_yeu;
    }

    /**
     * @param mixed $trich_yeu
     */
    public function set_trich_yeu($trich_yeu)
    {
        $this->trich_yeu = $trich_yeu;
    }

    /**
     * @return mixed
     */
    public function get_toan_van()
    {
        return $this->toan_van;
    }

    /**
     * @param mixed $toan_van
     */
    public function set_toan_van($toan_van)
    {
        $this->toan_van = $toan_van;
    }

    /**
     * @return mixed
     */
    public function get_chu_tri_xu_ly()
    {
        return $this->chu_tri_xu_ly;
    }

    /**
     * @param mixed $chu_tri_xu_ly
     */
    public function set_chu_tri_xu_ly($chu_tri_xu_ly)
    {
        $this->chu_tri_xu_ly = '';
        $users = get_users();
        foreach ($users as $user) {
            if ($user->ID == $chu_tri_xu_ly) {
                $this->chu_tri_xu_ly = $user->display_name;
                break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function get_phoi_hop_xu_ly()
    {
        return $this->phoi_hop_xu_ly;
    }

    /**
     * @param mixed $phoi_hop_xu_ly
     */
    public function set_phoi_hop_xu_ly($phoi_hop_xu_ly)
    {
        $this->phoi_hop_xu_ly = '';
        $users = get_users();
        foreach ($users as $user) {
            if ($user->ID == $phoi_hop_xu_ly) {
                $this->phoi_hop_xu_ly = $user->display_name;
                break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function get_han_giai_quyet_xong()
    {
        return $this->han_giai_quyet_xong;
    }

    /**
     * @param mixed $han_giai_quyet_xong
     */
    public function set_han_giai_quyet_xong($han_giai_quyet_xong)
    {
        $this->han_giai_quyet_xong = $han_giai_quyet_xong;
    }

    /**
     * @return mixed
     */
    public function get_han_thu_hoi()
    {
        return $this->han_thu_hoi;
    }

    /**
     * @param mixed $han_thu_hoi
     */
    public function set_han_thu_hoi($han_thu_hoi)
    {
        $this->han_thu_hoi = $han_thu_hoi;
    }

    /**
     * @return mixed
     */
    public function get_noi_dung_yeu_cau_xu_ly()
    {
        return $this->noi_dung_yeu_cau_xu_ly;
    }

    /**
     * @param mixed $noi_dung_yeu_cau_xu_ly
     */
    public function set_noi_dung_yeu_cau_xu_ly($noi_dung_yeu_cau_xu_ly)
    {
        $this->noi_dung_yeu_cau_xu_ly = $noi_dung_yeu_cau_xu_ly;
    }

    /**
     * @return mixed
     */
    public function get_phong_luu_tru_so()
    {
        return $this->phong_luu_tru_so;
    }

    /**
     * @param mixed $phong_luu_tru_so
     */
    public function set_phong_luu_tru_so($phong_luu_tru_so)
    {
        $this->phong_luu_tru_so = $phong_luu_tru_so;
    }

    /**
     * @return mixed
     */
    public function get_muc_luc_ho_so_so()
    {
        return $this->muc_luc_ho_so_so;
    }

    /**
     * @param mixed $muc_luc_ho_so_so
     */
    public function set_muc_luc_ho_so_so($muc_luc_ho_so_so)
    {
        $this->muc_luc_ho_so_so = $muc_luc_ho_so_so;
    }

    /**
     * @return mixed
     */
    public function get_luu_ho_so_so()
    {
        return $this->luu_ho_so_so;
    }

    /**
     * @param mixed $luu_ho_so_so
     */
    public function set_luu_ho_so_so($luu_ho_so_so)
    {
        $this->luu_ho_so_so = $luu_ho_so_so;
    }

    /**
     * @return int
     */
    public function get_thoi_han_bao_quan()
    {
        return $this->thoi_han_bao_quan;
    }

    /**
     * @param int $thoi_han_bao_quan
     */
    public function set_thoi_han_bao_quan($thoi_han_bao_quan)
    {
        $options_file = dirname(__DIR__).'/settings/thoi_han_bao_quan.csv';
        $keyValuePair = CongVanCustomContent::get_hash_from_file($options_file);
        $this->thoi_han_bao_quan = $keyValuePair[$thoi_han_bao_quan];
    }

    /**
     * @return mixed
     */
    public function get_ngay_luu_ho_so()
    {
        return $this->ngay_luu_ho_so;
    }

    /**
     * @param mixed $ngay_luu_ho_so
     */
    public function set_ngay_luu_ho_so($ngay_luu_ho_so)
    {
        $this->ngay_luu_ho_so = $ngay_luu_ho_so;
    }


}