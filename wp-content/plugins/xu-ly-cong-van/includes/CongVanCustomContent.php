<?php

/**
 * Created by PhpStorm.
 * User: vanpt
 * Date: 9/6/2017
 * Time: 11:01 PM
 */
class CongVanCustomContent
{
    const FIELD_TOAN_VAN = 'toan_van';
    
    public static function update_edit_form() {
        print ' enctype="multipart/form-data"';
    }

    public static function get_values_from_file($file_path) {
        $result = array();
        $handle = fopen($file_path, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $result[$line] = $line;
            }
            fclose($handle);
        } else {
            print 'Không thể mở file: '.$file_path;
        }
        return $result;
    }

    public static function get_hash_from_file($file_path) {
        $result = array();
        $handle = fopen($file_path, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $parts = explode(';', $line);
                $result[$parts[0]] = $parts[1];
            }
            fclose($handle);
        } else {
            print 'Không thể mở file: '.$file_path;
        }
        return $result;
    }

    public static function get_text_element($data) {
        $tpl = '<label for="{{name}}"><strong>{{title}}</strong></label>
				<input type="text" name="{{name}}" id="{{name}}" value="{{value}}" /><br/>';
        return Utils::parse($tpl, $data);
    }

    public static function get_textarea_element($data) {
        $tpl = '<label for="{{name}}"><strong>{{title}}</strong></label>
			<textarea name="{{name}}" id="{{name}}" columns="30" rows="3">{{value}}</textarea>';
        return Utils::parse($tpl, $data);
    }

    public static function get_dropdown_element($data) {
        // Some error messaging.
        if ( !isset($data['options']) || !is_array($data['options']) )
        {
            return '<p><strong>Custom Content Error:</strong> No options supplied for '.$data['name'].'</p>';
        }
        $tpl = '<label for="{{name}}"><strong>{{title}}</strong></label>
			<select name="{{name}}" id="{{name}}">
			{{options}}
			</select>';
        $option_str = '';
        foreach ( $data['options'] as $key => $value )
        {
            $option = htmlspecialchars($value); // Filter the values
            $is_selected = '';
            if ( $data['value'] == $value || $data['value'] == $key)
            {
                $is_selected = 'selected="selected"';
            }
            $option_str .= '<option value="'.$key.'" '.$is_selected.'>'.$option.'</option>';
        }

        unset($data['options']); // the parse function req's a simple hash.
        $data['options'] = $option_str; // prep for parsing
        $content = Timber::compile_string($tpl, $data);
        return $content;
    }

    public static function get_datepicker_element($data) {
        $tpl = '<label for="{{name}}"><strong>{{title}} &#128197;</strong></label>
			<input size="20" class="datepicker" type="text" name="{{name}}" id="{{name}}" value="{{value}}"/>';
        return Utils::parse($tpl, $data);
    }

    public static function get_uploader_element($data) {
        if (!$data['has_file']) {
            $tpl = '<label for="{{name}}"><strong>{{title}}</strong></label>
                <input class="upload_image_button" type="file" name="{{name}}" id="{{name}}"/><br/>';
        } else {
            $tpl = '<a href="{{value}}">{{value}}</a>';
        }
        return Utils::parse($tpl, $data);
    }
}