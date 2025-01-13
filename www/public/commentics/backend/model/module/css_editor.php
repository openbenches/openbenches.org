<?php
namespace Commentics;

class ModuleCssEditorModel extends Model
{
    public function getCss()
    {
        $css = '';

        if (file_exists(CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/stylesheet/css/custom.css')) {
            $css = file_get_contents(CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/stylesheet/css/custom.css');
        }

        return $css;
    }

    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '1' WHERE `title` = 'css_editor_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_general_background_color']) . "' WHERE `title` = 'css_editor_general_background_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_general_foreground_color']) . "' WHERE `title` = 'css_editor_general_foreground_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_general_font_family']) . "' WHERE `title` = 'css_editor_general_font_family'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_general_font_size']) . "' WHERE `title` = 'css_editor_general_font_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_heading_background_color']) . "' WHERE `title` = 'css_editor_heading_background_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_heading_foreground_color']) . "' WHERE `title` = 'css_editor_heading_foreground_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_heading_font_family']) . "' WHERE `title` = 'css_editor_heading_font_family'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_heading_font_size']) . "' WHERE `title` = 'css_editor_heading_font_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_link_background_color']) . "' WHERE `title` = 'css_editor_link_background_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_link_foreground_color']) . "' WHERE `title` = 'css_editor_link_foreground_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_link_font_family']) . "' WHERE `title` = 'css_editor_link_font_family'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_link_font_size']) . "' WHERE `title` = 'css_editor_link_font_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_primary_button_background_color']) . "' WHERE `title` = 'css_editor_primary_button_background_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_primary_button_foreground_color']) . "' WHERE `title` = 'css_editor_primary_button_foreground_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_primary_button_font_family']) . "' WHERE `title` = 'css_editor_primary_button_font_family'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_primary_button_font_size']) . "' WHERE `title` = 'css_editor_primary_button_font_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_secondary_button_background_color']) . "' WHERE `title` = 'css_editor_secondary_button_background_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_secondary_button_foreground_color']) . "' WHERE `title` = 'css_editor_secondary_button_foreground_color'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_secondary_button_font_family']) . "' WHERE `title` = 'css_editor_secondary_button_font_family'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['css_editor_secondary_button_font_size']) . "' WHERE `title` = 'css_editor_secondary_button_font_size'");

        $css_file = CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/stylesheet/css/custom.css';

        if ($data['css']) {
            $directory = dirname($css_file);

            if (!is_dir($directory)) {
                @mkdir($directory, 0777, true);
            }

            $handle = fopen($css_file, 'w');

            fputs($handle, $data['css']);

            fclose($handle);
        } else {
            @unlink($css_file);
        }
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_enabled', `value` = '0'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_general_background_color', `value` = '#FFFFFF'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_general_foreground_color', `value` = '#000000'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_general_font_family', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_general_font_size', `value` = ''");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_heading_background_color', `value` = '#FFFFFF'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_heading_foreground_color', `value` = '#000000'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_heading_font_family', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_heading_font_size', `value` = ''");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_link_background_color', `value` = '#FFFFFF'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_link_foreground_color', `value` = '#0000EE'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_link_font_family', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_link_font_size', `value` = ''");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_primary_button_background_color', `value` = '#3F6F95'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_primary_button_foreground_color', `value` = '#FFFFFF'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_primary_button_font_family', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_primary_button_font_size', `value` = ''");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_secondary_button_background_color', `value` = '#E7E7E7'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_secondary_button_foreground_color', `value` = '#000000'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_secondary_button_font_family', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'css_editor_secondary_button_font_size', `value` = ''");
    }

    public function uninstall()
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_enabled'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_general_background_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_general_foreground_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_general_font_family'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_general_font_size'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_heading_background_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_heading_foreground_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_heading_font_family'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_heading_font_size'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_link_background_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_link_foreground_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_link_font_family'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_link_font_size'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_primary_button_background_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_primary_button_foreground_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_primary_button_font_family'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_primary_button_font_size'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_secondary_button_background_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_secondary_button_foreground_color'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_secondary_button_font_family'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'css_editor_secondary_button_font_size'");

        $css_file = CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/stylesheet/css/custom.css';

        if (file_exists($css_file) && filesize($css_file) === 0) {
            @unlink($css_file);
        }
    }
}
