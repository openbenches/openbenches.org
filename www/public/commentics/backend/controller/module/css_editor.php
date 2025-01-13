<?php
namespace Commentics;

class ModuleCssEditorController extends Controller
{
    private $font_families = array(
        'Arial',
        'Brush Script MT',
        'Comic Sans MS',
        'Courier',
        'Courier New',
        'Georgia',
        'Garamond',
        'Helvetica',
        'Impact',
        'Palatino',
        'Tahoma',
        'Times',
        'Times New Roman',
        'Trebuchet MS',
        'Verdana'
    );
    private $font_sizes = array(
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25
    );

    public function index()
    {
        if (!$this->setting->has('css_editor_enabled')) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('module/css_editor');

        $this->loadModel('module/css_editor');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_css_editor->update($this->request->post);
            }
        }

        if (isset($this->request->post['css_editor_general_background_color'])) {
            $this->data['css_editor_general_background_color'] = $this->request->post['css_editor_general_background_color'];
        } else {
            $this->data['css_editor_general_background_color'] = $this->setting->get('css_editor_general_background_color');
        }

        if (isset($this->request->post['css_editor_general_foreground_color'])) {
            $this->data['css_editor_general_foreground_color'] = $this->request->post['css_editor_general_foreground_color'];
        } else {
            $this->data['css_editor_general_foreground_color'] = $this->setting->get('css_editor_general_foreground_color');
        }

        if (isset($this->request->post['css_editor_general_font_family'])) {
            $this->data['css_editor_general_font_family'] = $this->request->post['css_editor_general_font_family'];
        } else {
            $this->data['css_editor_general_font_family'] = $this->setting->get('css_editor_general_font_family');
        }

        if (isset($this->request->post['css_editor_general_font_size'])) {
            $this->data['css_editor_general_font_size'] = $this->request->post['css_editor_general_font_size'];
        } else {
            $this->data['css_editor_general_font_size'] = $this->setting->get('css_editor_general_font_size');
        }

        if (isset($this->request->post['css_editor_heading_background_color'])) {
            $this->data['css_editor_heading_background_color'] = $this->request->post['css_editor_heading_background_color'];
        } else {
            $this->data['css_editor_heading_background_color'] = $this->setting->get('css_editor_heading_background_color');
        }

        if (isset($this->request->post['css_editor_heading_foreground_color'])) {
            $this->data['css_editor_heading_foreground_color'] = $this->request->post['css_editor_heading_foreground_color'];
        } else {
            $this->data['css_editor_heading_foreground_color'] = $this->setting->get('css_editor_heading_foreground_color');
        }

        if (isset($this->request->post['css_editor_heading_font_family'])) {
            $this->data['css_editor_heading_font_family'] = $this->request->post['css_editor_heading_font_family'];
        } else {
            $this->data['css_editor_heading_font_family'] = $this->setting->get('css_editor_heading_font_family');
        }

        if (isset($this->request->post['css_editor_heading_font_size'])) {
            $this->data['css_editor_heading_font_size'] = $this->request->post['css_editor_heading_font_size'];
        } else {
            $this->data['css_editor_heading_font_size'] = $this->setting->get('css_editor_heading_font_size');
        }

        if (isset($this->request->post['css_editor_link_background_color'])) {
            $this->data['css_editor_link_background_color'] = $this->request->post['css_editor_link_background_color'];
        } else {
            $this->data['css_editor_link_background_color'] = $this->setting->get('css_editor_link_background_color');
        }

        if (isset($this->request->post['css_editor_link_foreground_color'])) {
            $this->data['css_editor_link_foreground_color'] = $this->request->post['css_editor_link_foreground_color'];
        } else {
            $this->data['css_editor_link_foreground_color'] = $this->setting->get('css_editor_link_foreground_color');
        }

        if (isset($this->request->post['css_editor_link_font_family'])) {
            $this->data['css_editor_link_font_family'] = $this->request->post['css_editor_link_font_family'];
        } else {
            $this->data['css_editor_link_font_family'] = $this->setting->get('css_editor_link_font_family');
        }

        if (isset($this->request->post['css_editor_link_font_size'])) {
            $this->data['css_editor_link_font_size'] = $this->request->post['css_editor_link_font_size'];
        } else {
            $this->data['css_editor_link_font_size'] = $this->setting->get('css_editor_link_font_size');
        }

        if (isset($this->request->post['css_editor_primary_button_background_color'])) {
            $this->data['css_editor_primary_button_background_color'] = $this->request->post['css_editor_primary_button_background_color'];
        } else {
            $this->data['css_editor_primary_button_background_color'] = $this->setting->get('css_editor_primary_button_background_color');
        }

        if (isset($this->request->post['css_editor_primary_button_foreground_color'])) {
            $this->data['css_editor_primary_button_foreground_color'] = $this->request->post['css_editor_primary_button_foreground_color'];
        } else {
            $this->data['css_editor_primary_button_foreground_color'] = $this->setting->get('css_editor_primary_button_foreground_color');
        }

        if (isset($this->request->post['css_editor_primary_button_font_family'])) {
            $this->data['css_editor_primary_button_font_family'] = $this->request->post['css_editor_primary_button_font_family'];
        } else {
            $this->data['css_editor_primary_button_font_family'] = $this->setting->get('css_editor_primary_button_font_family');
        }

        if (isset($this->request->post['css_editor_primary_button_font_size'])) {
            $this->data['css_editor_primary_button_font_size'] = $this->request->post['css_editor_primary_button_font_size'];
        } else {
            $this->data['css_editor_primary_button_font_size'] = $this->setting->get('css_editor_primary_button_font_size');
        }

        if (isset($this->request->post['css_editor_secondary_button_background_color'])) {
            $this->data['css_editor_secondary_button_background_color'] = $this->request->post['css_editor_secondary_button_background_color'];
        } else {
            $this->data['css_editor_secondary_button_background_color'] = $this->setting->get('css_editor_secondary_button_background_color');
        }

        if (isset($this->request->post['css_editor_secondary_button_foreground_color'])) {
            $this->data['css_editor_secondary_button_foreground_color'] = $this->request->post['css_editor_secondary_button_foreground_color'];
        } else {
            $this->data['css_editor_secondary_button_foreground_color'] = $this->setting->get('css_editor_secondary_button_foreground_color');
        }

        if (isset($this->request->post['css_editor_secondary_button_font_family'])) {
            $this->data['css_editor_secondary_button_font_family'] = $this->request->post['css_editor_secondary_button_font_family'];
        } else {
            $this->data['css_editor_secondary_button_font_family'] = $this->setting->get('css_editor_secondary_button_font_family');
        }

        if (isset($this->request->post['css_editor_secondary_button_font_size'])) {
            $this->data['css_editor_secondary_button_font_size'] = $this->request->post['css_editor_secondary_button_font_size'];
        } else {
            $this->data['css_editor_secondary_button_font_size'] = $this->setting->get('css_editor_secondary_button_font_size');
        }

        if (isset($this->request->post['css'])) {
            $this->data['css'] = $this->request->post['css'];
        } else {
            $this->data['css'] = $this->model_module_css_editor->getCss();
        }

        if (isset($this->error['css_editor_general_background_color'])) {
            $this->data['error_css_editor_general_background_color'] = $this->error['css_editor_general_background_color'];
        } else {
            $this->data['error_css_editor_general_background_color'] = '';
        }

        if (isset($this->error['css_editor_general_foreground_color'])) {
            $this->data['error_css_editor_general_foreground_color'] = $this->error['css_editor_general_foreground_color'];
        } else {
            $this->data['error_css_editor_general_foreground_color'] = '';
        }

        if (isset($this->error['css_editor_general_font_family'])) {
            $this->data['error_css_editor_general_font_family'] = $this->error['css_editor_general_font_family'];
        } else {
            $this->data['error_css_editor_general_font_family'] = '';
        }

        if (isset($this->error['css_editor_general_font_size'])) {
            $this->data['error_css_editor_general_font_size'] = $this->error['css_editor_general_font_size'];
        } else {
            $this->data['error_css_editor_general_font_size'] = '';
        }

        if (isset($this->error['css_editor_heading_background_color'])) {
            $this->data['error_css_editor_heading_background_color'] = $this->error['css_editor_heading_background_color'];
        } else {
            $this->data['error_css_editor_heading_background_color'] = '';
        }

        if (isset($this->error['css_editor_heading_foreground_color'])) {
            $this->data['error_css_editor_heading_foreground_color'] = $this->error['css_editor_heading_foreground_color'];
        } else {
            $this->data['error_css_editor_heading_foreground_color'] = '';
        }

        if (isset($this->error['css_editor_heading_font_family'])) {
            $this->data['error_css_editor_heading_font_family'] = $this->error['css_editor_heading_font_family'];
        } else {
            $this->data['error_css_editor_heading_font_family'] = '';
        }

        if (isset($this->error['css_editor_heading_font_size'])) {
            $this->data['error_css_editor_heading_font_size'] = $this->error['css_editor_heading_font_size'];
        } else {
            $this->data['error_css_editor_heading_font_size'] = '';
        }

        if (isset($this->error['css_editor_link_background_color'])) {
            $this->data['error_css_editor_link_background_color'] = $this->error['css_editor_link_background_color'];
        } else {
            $this->data['error_css_editor_link_background_color'] = '';
        }

        if (isset($this->error['css_editor_link_foreground_color'])) {
            $this->data['error_css_editor_link_foreground_color'] = $this->error['css_editor_link_foreground_color'];
        } else {
            $this->data['error_css_editor_link_foreground_color'] = '';
        }

        if (isset($this->error['css_editor_link_font_family'])) {
            $this->data['error_css_editor_link_font_family'] = $this->error['css_editor_link_font_family'];
        } else {
            $this->data['error_css_editor_link_font_family'] = '';
        }

        if (isset($this->error['css_editor_link_font_size'])) {
            $this->data['error_css_editor_link_font_size'] = $this->error['css_editor_link_font_size'];
        } else {
            $this->data['error_css_editor_link_font_size'] = '';
        }

        if (isset($this->error['css_editor_primary_button_background_color'])) {
            $this->data['error_css_editor_primary_button_background_color'] = $this->error['css_editor_primary_button_background_color'];
        } else {
            $this->data['error_css_editor_primary_button_background_color'] = '';
        }

        if (isset($this->error['css_editor_primary_button_foreground_color'])) {
            $this->data['error_css_editor_primary_button_foreground_color'] = $this->error['css_editor_primary_button_foreground_color'];
        } else {
            $this->data['error_css_editor_primary_button_foreground_color'] = '';
        }

        if (isset($this->error['css_editor_primary_button_font_family'])) {
            $this->data['error_css_editor_primary_button_font_family'] = $this->error['css_editor_primary_button_font_family'];
        } else {
            $this->data['error_css_editor_primary_button_font_family'] = '';
        }

        if (isset($this->error['css_editor_primary_button_font_size'])) {
            $this->data['error_css_editor_primary_button_font_size'] = $this->error['css_editor_primary_button_font_size'];
        } else {
            $this->data['error_css_editor_primary_button_font_size'] = '';
        }

        if (isset($this->error['css_editor_secondary_button_background_color'])) {
            $this->data['error_css_editor_secondary_button_background_color'] = $this->error['css_editor_secondary_button_background_color'];
        } else {
            $this->data['error_css_editor_secondary_button_background_color'] = '';
        }

        if (isset($this->error['css_editor_secondary_button_foreground_color'])) {
            $this->data['error_css_editor_secondary_button_foreground_color'] = $this->error['css_editor_secondary_button_foreground_color'];
        } else {
            $this->data['error_css_editor_secondary_button_foreground_color'] = '';
        }

        if (isset($this->error['css_editor_secondary_button_font_family'])) {
            $this->data['error_css_editor_secondary_button_font_family'] = $this->error['css_editor_secondary_button_font_family'];
        } else {
            $this->data['error_css_editor_secondary_button_font_family'] = '';
        }

        if (isset($this->error['css_editor_secondary_button_font_size'])) {
            $this->data['error_css_editor_secondary_button_font_size'] = $this->error['css_editor_secondary_button_font_size'];
        } else {
            $this->data['error_css_editor_secondary_button_font_size'] = '';
        }

        $this->data['font_families'] = $this->font_families;

        $this->data['font_sizes'] = $this->font_sizes;

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/css_editor');
    }

    public function install()
    {
        $this->loadModel('module/css_editor');

        $this->model_module_css_editor->install();
    }

    public function uninstall()
    {
        $this->loadModel('module/css_editor');

        $this->model_module_css_editor->uninstall();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        /* General */

        if (isset($this->request->post['css_editor_general_background_color'])) {
            if ($this->request->post['css_editor_general_background_color']) {
                if (substr($this->request->post['css_editor_general_background_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_general_background_color'], '#'))) {
                    $this->error['css_editor_general_background_color'] = $this->data['lang_error_hex_format'];
                }

                if (!isset($this->request->post['css_editor_general_background_color']) || $this->validation->length($this->request->post['css_editor_general_background_color']) != 7) {
                    $this->error['css_editor_general_background_color'] = $this->data['lang_error_hex_length'];
                }
            }
        } else {
            $this->error['css_editor_general_background_color'] = $this->data['lang_error_hex_format'];
        }

        if (isset($this->request->post['css_editor_general_foreground_color'])) {
            if (substr($this->request->post['css_editor_general_foreground_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_general_foreground_color'], '#'))) {
                $this->error['css_editor_general_foreground_color'] = $this->data['lang_error_hex_format'];
            }

            if (!isset($this->request->post['css_editor_general_foreground_color']) || $this->validation->length($this->request->post['css_editor_general_foreground_color']) != 7) {
                $this->error['css_editor_general_foreground_color'] = $this->data['lang_error_hex_length'];
            }
        } else {
            $this->error['css_editor_general_foreground_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['css_editor_general_font_family']) || ($this->request->post['css_editor_general_font_family'] && !in_array($this->request->post['css_editor_general_font_family'], $this->font_families))) {
            $this->error['css_editor_general_font_family'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['css_editor_general_font_size']) || ($this->request->post['css_editor_general_font_size'] && !in_array($this->request->post['css_editor_general_font_size'], $this->font_sizes))) {
            $this->error['css_editor_general_font_size'] = $this->data['lang_error_selection'];
        }

        /* Heading */

        if (isset($this->request->post['css_editor_heading_background_color'])) {
            if ($this->request->post['css_editor_heading_background_color']) {
                if (substr($this->request->post['css_editor_heading_background_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_heading_background_color'], '#'))) {
                    $this->error['css_editor_heading_background_color'] = $this->data['lang_error_hex_format'];
                }

                if (!isset($this->request->post['css_editor_heading_background_color']) || $this->validation->length($this->request->post['css_editor_heading_background_color']) != 7) {
                    $this->error['css_editor_heading_background_color'] = $this->data['lang_error_hex_length'];
                }
            }
        } else {
            $this->error['css_editor_heading_background_color'] = $this->data['lang_error_hex_format'];
        }

        if (isset($this->request->post['css_editor_heading_foreground_color'])) {
            if (substr($this->request->post['css_editor_heading_foreground_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_heading_foreground_color'], '#'))) {
                $this->error['css_editor_heading_foreground_color'] = $this->data['lang_error_hex_format'];
            }

            if (!isset($this->request->post['css_editor_heading_foreground_color']) || $this->validation->length($this->request->post['css_editor_heading_foreground_color']) != 7) {
                $this->error['css_editor_heading_foreground_color'] = $this->data['lang_error_hex_length'];
            }
        } else {
            $this->error['css_editor_heading_foreground_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['css_editor_heading_font_family']) || ($this->request->post['css_editor_heading_font_family'] && !in_array($this->request->post['css_editor_heading_font_family'], $this->font_families))) {
            $this->error['css_editor_heading_font_family'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['css_editor_heading_font_size']) || ($this->request->post['css_editor_heading_font_size'] && !in_array($this->request->post['css_editor_heading_font_size'], $this->font_sizes))) {
            $this->error['css_editor_heading_font_size'] = $this->data['lang_error_selection'];
        }

        /* Link */

        if (isset($this->request->post['css_editor_link_background_color'])) {
            if ($this->request->post['css_editor_link_background_color']) {
                if (substr($this->request->post['css_editor_link_background_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_link_background_color'], '#'))) {
                    $this->error['css_editor_link_background_color'] = $this->data['lang_error_hex_format'];
                }

                if (!isset($this->request->post['css_editor_link_background_color']) || $this->validation->length($this->request->post['css_editor_link_background_color']) != 7) {
                    $this->error['css_editor_link_background_color'] = $this->data['lang_error_hex_length'];
                }
            }
        } else {
            $this->error['css_editor_link_background_color'] = $this->data['lang_error_hex_format'];
        }

        if (isset($this->request->post['css_editor_link_foreground_color'])) {
            if (substr($this->request->post['css_editor_link_foreground_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_link_foreground_color'], '#'))) {
                $this->error['css_editor_link_foreground_color'] = $this->data['lang_error_hex_format'];
            }

            if (!isset($this->request->post['css_editor_link_foreground_color']) || $this->validation->length($this->request->post['css_editor_link_foreground_color']) != 7) {
                $this->error['css_editor_link_foreground_color'] = $this->data['lang_error_hex_length'];
            }
        } else {
            $this->error['css_editor_link_foreground_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['css_editor_link_font_family']) || ($this->request->post['css_editor_link_font_family'] && !in_array($this->request->post['css_editor_link_font_family'], $this->font_families))) {
            $this->error['css_editor_link_font_family'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['css_editor_link_font_size']) || ($this->request->post['css_editor_link_font_size'] && !in_array($this->request->post['css_editor_link_font_size'], $this->font_sizes))) {
            $this->error['css_editor_link_font_size'] = $this->data['lang_error_selection'];
        }

        /* Primary Button */

        if (isset($this->request->post['css_editor_primary_button_background_color'])) {
            if ($this->request->post['css_editor_primary_button_background_color']) {
                if (substr($this->request->post['css_editor_primary_button_background_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_primary_button_background_color'], '#'))) {
                    $this->error['css_editor_primary_button_background_color'] = $this->data['lang_error_hex_format'];
                }

                if (!isset($this->request->post['css_editor_primary_button_background_color']) || $this->validation->length($this->request->post['css_editor_primary_button_background_color']) != 7) {
                    $this->error['css_editor_primary_button_background_color'] = $this->data['lang_error_hex_length'];
                }
            }
        } else {
            $this->error['css_editor_primary_button_background_color'] = $this->data['lang_error_hex_format'];
        }

        if (isset($this->request->post['css_editor_primary_button_foreground_color'])) {
            if (substr($this->request->post['css_editor_primary_button_foreground_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_primary_button_foreground_color'], '#'))) {
                $this->error['css_editor_primary_button_foreground_color'] = $this->data['lang_error_hex_format'];
            }

            if (!isset($this->request->post['css_editor_primary_button_foreground_color']) || $this->validation->length($this->request->post['css_editor_primary_button_foreground_color']) != 7) {
                $this->error['css_editor_primary_button_foreground_color'] = $this->data['lang_error_hex_length'];
            }
        } else {
            $this->error['css_editor_primary_button_foreground_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['css_editor_primary_button_font_family']) || ($this->request->post['css_editor_primary_button_font_family'] && !in_array($this->request->post['css_editor_primary_button_font_family'], $this->font_families))) {
            $this->error['css_editor_primary_button_font_family'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['css_editor_primary_button_font_size']) || ($this->request->post['css_editor_primary_button_font_size'] && !in_array($this->request->post['css_editor_primary_button_font_size'], $this->font_sizes))) {
            $this->error['css_editor_primary_button_font_size'] = $this->data['lang_error_selection'];
        }

        /* Secondary Button */

        if (isset($this->request->post['css_editor_secondary_button_background_color'])) {
            if ($this->request->post['css_editor_secondary_button_background_color']) {
                if (substr($this->request->post['css_editor_secondary_button_background_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_secondary_button_background_color'], '#'))) {
                    $this->error['css_editor_secondary_button_background_color'] = $this->data['lang_error_hex_format'];
                }

                if (!isset($this->request->post['css_editor_secondary_button_background_color']) || $this->validation->length($this->request->post['css_editor_secondary_button_background_color']) != 7) {
                    $this->error['css_editor_secondary_button_background_color'] = $this->data['lang_error_hex_length'];
                }
            }
        } else {
            $this->error['css_editor_secondary_button_background_color'] = $this->data['lang_error_hex_format'];
        }

        if (isset($this->request->post['css_editor_secondary_button_foreground_color'])) {
            if (substr($this->request->post['css_editor_secondary_button_foreground_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['css_editor_secondary_button_foreground_color'], '#'))) {
                $this->error['css_editor_secondary_button_foreground_color'] = $this->data['lang_error_hex_format'];
            }

            if (!isset($this->request->post['css_editor_secondary_button_foreground_color']) || $this->validation->length($this->request->post['css_editor_secondary_button_foreground_color']) != 7) {
                $this->error['css_editor_secondary_button_foreground_color'] = $this->data['lang_error_hex_length'];
            }
        } else {
            $this->error['css_editor_secondary_button_foreground_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['css_editor_secondary_button_font_family']) || ($this->request->post['css_editor_secondary_button_font_family'] && !in_array($this->request->post['css_editor_secondary_button_font_family'], $this->font_families))) {
            $this->error['css_editor_secondary_button_font_family'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['css_editor_secondary_button_font_size']) || ($this->request->post['css_editor_secondary_button_font_size'] && !in_array($this->request->post['css_editor_secondary_button_font_size'], $this->font_sizes))) {
            $this->error['css_editor_secondary_button_font_size'] = $this->data['lang_error_selection'];
        }

        $css_file = CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/stylesheet/css/custom.css';

        if (file_exists($css_file) && !is_writable($css_file)) {
            $this->data['error'] = $this->data['lang_message_write'];

            return false;
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_success'];

            return true;
        }
    }
}
