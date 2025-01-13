<?php
namespace Commentics;

class SettingsLayoutFormController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/layout_form');

        $this->loadModel('settings/layout_form');

        $this->loadModel('module/extra_fields');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_layout_form->update($this->request->post);
            }
        }

        /* General */

        if (isset($this->request->post['enabled_form'])) {
            $this->data['enabled_form'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_form'])) {
            $this->data['enabled_form'] = false;
        } else {
            $this->data['enabled_form'] = $this->setting->get('enabled_form');
        }

        if (isset($this->request->post['hide_form'])) {
            $this->data['hide_form'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['hide_form'])) {
            $this->data['hide_form'] = false;
        } else {
            $this->data['hide_form'] = $this->setting->get('hide_form');
        }

        if (isset($this->request->post['display_javascript_disabled'])) {
            $this->data['display_javascript_disabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display_javascript_disabled'])) {
            $this->data['display_javascript_disabled'] = false;
        } else {
            $this->data['display_javascript_disabled'] = $this->setting->get('display_javascript_disabled');
        }

        if (isset($this->request->post['display_required_symbol'])) {
            $this->data['display_required_symbol'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display_required_symbol'])) {
            $this->data['display_required_symbol'] = false;
        } else {
            $this->data['display_required_symbol'] = $this->setting->get('display_required_symbol');
        }

        if (isset($this->request->post['display_required_text'])) {
            $this->data['display_required_text'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display_required_text'])) {
            $this->data['display_required_text'] = false;
        } else {
            $this->data['display_required_text'] = $this->setting->get('display_required_text');
        }

        if (isset($this->request->post['order_fields'])) {
            $this->data['order_fields'] = $this->request->post['order_fields'];
        } else {
            $this->data['order_fields'] = $this->setting->get('order_fields');
        }

        $fields = array();

        foreach (explode(',', $this->data['order_fields']) as $field) {
            $field_id = $this->variable->substr($field, 6, strlen($field));

            if ($field_id && $this->validation->isInt($field_id)) {
                $field_info = $this->model_module_extra_fields->getField($field_id);

                if ($field_info) {
                    $fields[$field] = $field_info['name'];
                }
            } else if (isset($this->data['lang_text_' . $field])) {
                $fields[$field] = $this->data['lang_text_' . $field];
            }
        }

        $this->data['fields'] = $fields;

        if (isset($this->error['order_fields'])) {
            $this->data['error_order_fields'] = $this->error['order_fields'];
        } else {
            $this->data['error_order_fields'] = '';
        }

        /* BB Code */

        if (isset($this->request->post['enabled_bb_code'])) {
            $this->data['enabled_bb_code'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code'])) {
            $this->data['enabled_bb_code'] = false;
        } else {
            $this->data['enabled_bb_code'] = $this->setting->get('enabled_bb_code');
        }

        if (isset($this->request->post['enabled_bb_code_bold'])) {
            $this->data['enabled_bb_code_bold'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_bold'])) {
            $this->data['enabled_bb_code_bold'] = false;
        } else {
            $this->data['enabled_bb_code_bold'] = $this->setting->get('enabled_bb_code_bold');
        }

        if (isset($this->request->post['enabled_bb_code_italic'])) {
            $this->data['enabled_bb_code_italic'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_italic'])) {
            $this->data['enabled_bb_code_italic'] = false;
        } else {
            $this->data['enabled_bb_code_italic'] = $this->setting->get('enabled_bb_code_italic');
        }

        if (isset($this->request->post['enabled_bb_code_underline'])) {
            $this->data['enabled_bb_code_underline'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_underline'])) {
            $this->data['enabled_bb_code_underline'] = false;
        } else {
            $this->data['enabled_bb_code_underline'] = $this->setting->get('enabled_bb_code_underline');
        }

        if (isset($this->request->post['enabled_bb_code_strike'])) {
            $this->data['enabled_bb_code_strike'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_strike'])) {
            $this->data['enabled_bb_code_strike'] = false;
        } else {
            $this->data['enabled_bb_code_strike'] = $this->setting->get('enabled_bb_code_strike');
        }

        if (isset($this->request->post['enabled_bb_code_superscript'])) {
            $this->data['enabled_bb_code_superscript'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_superscript'])) {
            $this->data['enabled_bb_code_superscript'] = false;
        } else {
            $this->data['enabled_bb_code_superscript'] = $this->setting->get('enabled_bb_code_superscript');
        }

        if (isset($this->request->post['enabled_bb_code_subscript'])) {
            $this->data['enabled_bb_code_subscript'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_subscript'])) {
            $this->data['enabled_bb_code_subscript'] = false;
        } else {
            $this->data['enabled_bb_code_subscript'] = $this->setting->get('enabled_bb_code_subscript');
        }

        if (isset($this->request->post['enabled_bb_code_code'])) {
            $this->data['enabled_bb_code_code'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_code'])) {
            $this->data['enabled_bb_code_code'] = false;
        } else {
            $this->data['enabled_bb_code_code'] = $this->setting->get('enabled_bb_code_code');
        }

        if (isset($this->request->post['enabled_bb_code_php'])) {
            $this->data['enabled_bb_code_php'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_php'])) {
            $this->data['enabled_bb_code_php'] = false;
        } else {
            $this->data['enabled_bb_code_php'] = $this->setting->get('enabled_bb_code_php');
        }

        if (isset($this->request->post['enabled_bb_code_quote'])) {
            $this->data['enabled_bb_code_quote'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_quote'])) {
            $this->data['enabled_bb_code_quote'] = false;
        } else {
            $this->data['enabled_bb_code_quote'] = $this->setting->get('enabled_bb_code_quote');
        }

        if (isset($this->request->post['enabled_bb_code_line'])) {
            $this->data['enabled_bb_code_line'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_line'])) {
            $this->data['enabled_bb_code_line'] = false;
        } else {
            $this->data['enabled_bb_code_line'] = $this->setting->get('enabled_bb_code_line');
        }

        if (isset($this->request->post['enabled_bb_code_bullet'])) {
            $this->data['enabled_bb_code_bullet'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_bullet'])) {
            $this->data['enabled_bb_code_bullet'] = false;
        } else {
            $this->data['enabled_bb_code_bullet'] = $this->setting->get('enabled_bb_code_bullet');
        }

        if (isset($this->request->post['enabled_bb_code_numeric'])) {
            $this->data['enabled_bb_code_numeric'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_numeric'])) {
            $this->data['enabled_bb_code_numeric'] = false;
        } else {
            $this->data['enabled_bb_code_numeric'] = $this->setting->get('enabled_bb_code_numeric');
        }

        if (isset($this->request->post['enabled_bb_code_link'])) {
            $this->data['enabled_bb_code_link'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_link'])) {
            $this->data['enabled_bb_code_link'] = false;
        } else {
            $this->data['enabled_bb_code_link'] = $this->setting->get('enabled_bb_code_link');
        }

        if (isset($this->request->post['enabled_bb_code_email'])) {
            $this->data['enabled_bb_code_email'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_email'])) {
            $this->data['enabled_bb_code_email'] = false;
        } else {
            $this->data['enabled_bb_code_email'] = $this->setting->get('enabled_bb_code_email');
        }

        if (isset($this->request->post['enabled_bb_code_image'])) {
            $this->data['enabled_bb_code_image'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_image'])) {
            $this->data['enabled_bb_code_image'] = false;
        } else {
            $this->data['enabled_bb_code_image'] = $this->setting->get('enabled_bb_code_image');
        }

        if (isset($this->request->post['enabled_bb_code_youtube'])) {
            $this->data['enabled_bb_code_youtube'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_bb_code_youtube'])) {
            $this->data['enabled_bb_code_youtube'] = false;
        } else {
            $this->data['enabled_bb_code_youtube'] = $this->setting->get('enabled_bb_code_youtube');
        }

        $this->data['bb_code'] = $this->model_settings_layout_form->getBbCode();

        /* Smilies */

        if (isset($this->request->post['enabled_smilies'])) {
            $this->data['enabled_smilies'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies'])) {
            $this->data['enabled_smilies'] = false;
        } else {
            $this->data['enabled_smilies'] = $this->setting->get('enabled_smilies');
        }

        if (isset($this->request->post['enabled_smilies_smile'])) {
            $this->data['enabled_smilies_smile'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_smile'])) {
            $this->data['enabled_smilies_smile'] = false;
        } else {
            $this->data['enabled_smilies_smile'] = $this->setting->get('enabled_smilies_smile');
        }

        if (isset($this->request->post['enabled_smilies_sad'])) {
            $this->data['enabled_smilies_sad'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_sad'])) {
            $this->data['enabled_smilies_sad'] = false;
        } else {
            $this->data['enabled_smilies_sad'] = $this->setting->get('enabled_smilies_sad');
        }

        if (isset($this->request->post['enabled_smilies_huh'])) {
            $this->data['enabled_smilies_huh'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_huh'])) {
            $this->data['enabled_smilies_huh'] = false;
        } else {
            $this->data['enabled_smilies_huh'] = $this->setting->get('enabled_smilies_huh');
        }

        if (isset($this->request->post['enabled_smilies_laugh'])) {
            $this->data['enabled_smilies_laugh'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_laugh'])) {
            $this->data['enabled_smilies_laugh'] = false;
        } else {
            $this->data['enabled_smilies_laugh'] = $this->setting->get('enabled_smilies_laugh');
        }

        if (isset($this->request->post['enabled_smilies_mad'])) {
            $this->data['enabled_smilies_mad'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_mad'])) {
            $this->data['enabled_smilies_mad'] = false;
        } else {
            $this->data['enabled_smilies_mad'] = $this->setting->get('enabled_smilies_mad');
        }

        if (isset($this->request->post['enabled_smilies_tongue'])) {
            $this->data['enabled_smilies_tongue'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_tongue'])) {
            $this->data['enabled_smilies_tongue'] = false;
        } else {
            $this->data['enabled_smilies_tongue'] = $this->setting->get('enabled_smilies_tongue');
        }

        if (isset($this->request->post['enabled_smilies_cry'])) {
            $this->data['enabled_smilies_cry'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_cry'])) {
            $this->data['enabled_smilies_cry'] = false;
        } else {
            $this->data['enabled_smilies_cry'] = $this->setting->get('enabled_smilies_cry');
        }

        if (isset($this->request->post['enabled_smilies_grin'])) {
            $this->data['enabled_smilies_grin'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_grin'])) {
            $this->data['enabled_smilies_grin'] = false;
        } else {
            $this->data['enabled_smilies_grin'] = $this->setting->get('enabled_smilies_grin');
        }

        if (isset($this->request->post['enabled_smilies_wink'])) {
            $this->data['enabled_smilies_wink'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_wink'])) {
            $this->data['enabled_smilies_wink'] = false;
        } else {
            $this->data['enabled_smilies_wink'] = $this->setting->get('enabled_smilies_wink');
        }

        if (isset($this->request->post['enabled_smilies_scared'])) {
            $this->data['enabled_smilies_scared'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_scared'])) {
            $this->data['enabled_smilies_scared'] = false;
        } else {
            $this->data['enabled_smilies_scared'] = $this->setting->get('enabled_smilies_scared');
        }

        if (isset($this->request->post['enabled_smilies_cool'])) {
            $this->data['enabled_smilies_cool'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_cool'])) {
            $this->data['enabled_smilies_cool'] = false;
        } else {
            $this->data['enabled_smilies_cool'] = $this->setting->get('enabled_smilies_cool');
        }

        if (isset($this->request->post['enabled_smilies_sleep'])) {
            $this->data['enabled_smilies_sleep'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_sleep'])) {
            $this->data['enabled_smilies_sleep'] = false;
        } else {
            $this->data['enabled_smilies_sleep'] = $this->setting->get('enabled_smilies_sleep');
        }

        if (isset($this->request->post['enabled_smilies_blush'])) {
            $this->data['enabled_smilies_blush'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_blush'])) {
            $this->data['enabled_smilies_blush'] = false;
        } else {
            $this->data['enabled_smilies_blush'] = $this->setting->get('enabled_smilies_blush');
        }

        if (isset($this->request->post['enabled_smilies_confused'])) {
            $this->data['enabled_smilies_confused'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_confused'])) {
            $this->data['enabled_smilies_confused'] = false;
        } else {
            $this->data['enabled_smilies_confused'] = $this->setting->get('enabled_smilies_confused');
        }

        if (isset($this->request->post['enabled_smilies_shocked'])) {
            $this->data['enabled_smilies_shocked'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_smilies_shocked'])) {
            $this->data['enabled_smilies_shocked'] = false;
        } else {
            $this->data['enabled_smilies_shocked'] = $this->setting->get('enabled_smilies_shocked');
        }

        $this->data['smilies'] = $this->model_settings_layout_form->getSmilies();

        /* Comment */

        if (isset($this->request->post['default_comment'])) {
            $this->data['default_comment'] = $this->request->post['default_comment'];
        } else {
            $this->data['default_comment'] = $this->setting->get('default_comment');
        }

        if (isset($this->request->post['comment_maximum_characters'])) {
            $this->data['comment_maximum_characters'] = $this->request->post['comment_maximum_characters'];
        } else {
            $this->data['comment_maximum_characters'] = $this->setting->get('comment_maximum_characters');
        }

        if (isset($this->request->post['enabled_counter'])) {
            $this->data['enabled_counter'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_counter'])) {
            $this->data['enabled_counter'] = false;
        } else {
            $this->data['enabled_counter'] = $this->setting->get('enabled_counter');
        }

        if (isset($this->error['default_comment'])) {
            $this->data['error_default_comment'] = $this->error['default_comment'];
        } else {
            $this->data['error_default_comment'] = '';
        }

        if (isset($this->error['comment_maximum_characters'])) {
            $this->data['error_comment_maximum_characters'] = $this->error['comment_maximum_characters'];
        } else {
            $this->data['error_comment_maximum_characters'] = '';
        }

        /* Headline */

        if (isset($this->request->post['enabled_headline'])) {
            $this->data['enabled_headline'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_headline'])) {
            $this->data['enabled_headline'] = false;
        } else {
            $this->data['enabled_headline'] = $this->setting->get('enabled_headline');
        }

        if (isset($this->request->post['required_headline'])) {
            $this->data['required_headline'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_headline'])) {
            $this->data['required_headline'] = false;
        } else {
            $this->data['required_headline'] = $this->setting->get('required_headline');
        }

        if (isset($this->request->post['default_headline'])) {
            $this->data['default_headline'] = $this->request->post['default_headline'];
        } else {
            $this->data['default_headline'] = $this->setting->get('default_headline');
        }

        if (isset($this->request->post['headline_maximum_characters'])) {
            $this->data['headline_maximum_characters'] = $this->request->post['headline_maximum_characters'];
        } else {
            $this->data['headline_maximum_characters'] = $this->setting->get('headline_maximum_characters');
        }

        if (isset($this->error['default_headline'])) {
            $this->data['error_default_headline'] = $this->error['default_headline'];
        } else {
            $this->data['error_default_headline'] = '';
        }

        if (isset($this->error['headline_maximum_characters'])) {
            $this->data['error_headline_maximum_characters'] = $this->error['headline_maximum_characters'];
        } else {
            $this->data['error_headline_maximum_characters'] = '';
        }

        /* Upload */

        if (isset($this->request->post['enabled_upload'])) {
            $this->data['enabled_upload'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_upload'])) {
            $this->data['enabled_upload'] = false;
        } else {
            $this->data['enabled_upload'] = $this->setting->get('enabled_upload');
        }

        if (isset($this->request->post['maximum_upload_size'])) {
            $this->data['maximum_upload_size'] = $this->request->post['maximum_upload_size'];
        } else {
            $this->data['maximum_upload_size'] = $this->setting->get('maximum_upload_size');
        }

        if (isset($this->request->post['maximum_upload_amount'])) {
            $this->data['maximum_upload_amount'] = $this->request->post['maximum_upload_amount'];
        } else {
            $this->data['maximum_upload_amount'] = $this->setting->get('maximum_upload_amount');
        }

        if (isset($this->request->post['maximum_upload_total'])) {
            $this->data['maximum_upload_total'] = $this->request->post['maximum_upload_total'];
        } else {
            $this->data['maximum_upload_total'] = $this->setting->get('maximum_upload_total');
        }

        if (isset($this->error['maximum_upload_size'])) {
            $this->data['error_maximum_upload_size'] = $this->error['maximum_upload_size'];
        } else {
            $this->data['error_maximum_upload_size'] = '';
        }

        if (isset($this->error['maximum_upload_amount'])) {
            $this->data['error_maximum_upload_amount'] = $this->error['maximum_upload_amount'];
        } else {
            $this->data['error_maximum_upload_amount'] = '';
        }

        if (isset($this->error['maximum_upload_total'])) {
            $this->data['error_maximum_upload_total'] = $this->error['maximum_upload_total'];
        } else {
            $this->data['error_maximum_upload_total'] = '';
        }

        /* Rating */

        if (isset($this->request->post['enabled_rating'])) {
            $this->data['enabled_rating'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_rating'])) {
            $this->data['enabled_rating'] = false;
        } else {
            $this->data['enabled_rating'] = $this->setting->get('enabled_rating');
        }

        if (isset($this->request->post['required_rating'])) {
            $this->data['required_rating'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_rating'])) {
            $this->data['required_rating'] = false;
        } else {
            $this->data['required_rating'] = $this->setting->get('required_rating');
        }

        if (isset($this->request->post['default_rating'])) {
            $this->data['default_rating'] = $this->request->post['default_rating'];
        } else {
            $this->data['default_rating'] = $this->setting->get('default_rating');
        }

        if (isset($this->request->post['repeat_rating'])) {
            $this->data['repeat_rating'] = $this->request->post['repeat_rating'];
        } else {
            $this->data['repeat_rating'] = $this->setting->get('repeat_rating');
        }

        if (isset($this->error['default_rating'])) {
            $this->data['error_default_rating'] = $this->error['default_rating'];
        } else {
            $this->data['error_default_rating'] = '';
        }

        if (isset($this->error['repeat_rating'])) {
            $this->data['error_repeat_rating'] = $this->error['repeat_rating'];
        } else {
            $this->data['error_repeat_rating'] = '';
        }

        /* Name */

        if (isset($this->request->post['default_name'])) {
            $this->data['default_name'] = $this->request->post['default_name'];
        } else {
            $this->data['default_name'] = $this->setting->get('default_name');
        }

        if (isset($this->request->post['maximum_name'])) {
            $this->data['maximum_name'] = $this->request->post['maximum_name'];
        } else {
            $this->data['maximum_name'] = $this->setting->get('maximum_name');
        }

        if (isset($this->request->post['filled_name_cookie_action'])) {
            $this->data['filled_name_cookie_action'] = $this->request->post['filled_name_cookie_action'];
        } else {
            $this->data['filled_name_cookie_action'] = $this->setting->get('filled_name_cookie_action');
        }

        if (isset($this->request->post['filled_name_login_action'])) {
            $this->data['filled_name_login_action'] = $this->request->post['filled_name_login_action'];
        } else {
            $this->data['filled_name_login_action'] = $this->setting->get('filled_name_login_action');
        }

        if (isset($this->error['default_name'])) {
            $this->data['error_default_name'] = $this->error['default_name'];
        } else {
            $this->data['error_default_name'] = '';
        }

        if (isset($this->error['maximum_name'])) {
            $this->data['error_maximum_name'] = $this->error['maximum_name'];
        } else {
            $this->data['error_maximum_name'] = '';
        }

        if (isset($this->error['filled_name_cookie_action'])) {
            $this->data['error_filled_name_cookie_action'] = $this->error['filled_name_cookie_action'];
        } else {
            $this->data['error_filled_name_cookie_action'] = '';
        }

        if (isset($this->error['filled_name_login_action'])) {
            $this->data['error_filled_name_login_action'] = $this->error['filled_name_login_action'];
        } else {
            $this->data['error_filled_name_login_action'] = '';
        }

        /* Email */

        if (isset($this->request->post['enabled_email'])) {
            $this->data['enabled_email'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_email'])) {
            $this->data['enabled_email'] = false;
        } else {
            $this->data['enabled_email'] = $this->setting->get('enabled_email');
        }

        if (isset($this->request->post['required_email'])) {
            $this->data['required_email'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_email'])) {
            $this->data['required_email'] = false;
        } else {
            $this->data['required_email'] = $this->setting->get('required_email');
        }

        if (isset($this->request->post['default_email'])) {
            $this->data['default_email'] = $this->request->post['default_email'];
        } else {
            $this->data['default_email'] = $this->setting->get('default_email');
        }

        if (isset($this->request->post['maximum_email'])) {
            $this->data['maximum_email'] = $this->request->post['maximum_email'];
        } else {
            $this->data['maximum_email'] = $this->setting->get('maximum_email');
        }

        if (isset($this->request->post['filled_email_cookie_action'])) {
            $this->data['filled_email_cookie_action'] = $this->request->post['filled_email_cookie_action'];
        } else {
            $this->data['filled_email_cookie_action'] = $this->setting->get('filled_email_cookie_action');
        }

        if (isset($this->request->post['filled_email_login_action'])) {
            $this->data['filled_email_login_action'] = $this->request->post['filled_email_login_action'];
        } else {
            $this->data['filled_email_login_action'] = $this->setting->get('filled_email_login_action');
        }

        if (isset($this->error['default_email'])) {
            $this->data['error_default_email'] = $this->error['default_email'];
        } else {
            $this->data['error_default_email'] = '';
        }

        if (isset($this->error['maximum_email'])) {
            $this->data['error_maximum_email'] = $this->error['maximum_email'];
        } else {
            $this->data['error_maximum_email'] = '';
        }

        if (isset($this->error['filled_email_cookie_action'])) {
            $this->data['error_filled_email_cookie_action'] = $this->error['filled_email_cookie_action'];
        } else {
            $this->data['error_filled_email_cookie_action'] = '';
        }

        if (isset($this->error['filled_email_login_action'])) {
            $this->data['error_filled_email_login_action'] = $this->error['filled_email_login_action'];
        } else {
            $this->data['error_filled_email_login_action'] = '';
        }

        /* Website */

        if (isset($this->request->post['enabled_website'])) {
            $this->data['enabled_website'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_website'])) {
            $this->data['enabled_website'] = false;
        } else {
            $this->data['enabled_website'] = $this->setting->get('enabled_website');
        }

        if (isset($this->request->post['required_website'])) {
            $this->data['required_website'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_website'])) {
            $this->data['required_website'] = false;
        } else {
            $this->data['required_website'] = $this->setting->get('required_website');
        }

        if (isset($this->request->post['default_website'])) {
            $this->data['default_website'] = $this->request->post['default_website'];
        } else {
            $this->data['default_website'] = $this->setting->get('default_website');
        }

        if (isset($this->request->post['maximum_website'])) {
            $this->data['maximum_website'] = $this->request->post['maximum_website'];
        } else {
            $this->data['maximum_website'] = $this->setting->get('maximum_website');
        }

        if (isset($this->request->post['filled_website_cookie_action'])) {
            $this->data['filled_website_cookie_action'] = $this->request->post['filled_website_cookie_action'];
        } else {
            $this->data['filled_website_cookie_action'] = $this->setting->get('filled_website_cookie_action');
        }

        if (isset($this->request->post['filled_website_login_action'])) {
            $this->data['filled_website_login_action'] = $this->request->post['filled_website_login_action'];
        } else {
            $this->data['filled_website_login_action'] = $this->setting->get('filled_website_login_action');
        }

        if (isset($this->error['default_website'])) {
            $this->data['error_default_website'] = $this->error['default_website'];
        } else {
            $this->data['error_default_website'] = '';
        }

        if (isset($this->error['maximum_website'])) {
            $this->data['error_maximum_website'] = $this->error['maximum_website'];
        } else {
            $this->data['error_maximum_website'] = '';
        }

        if (isset($this->error['filled_website_cookie_action'])) {
            $this->data['error_filled_website_cookie_action'] = $this->error['filled_website_cookie_action'];
        } else {
            $this->data['error_filled_website_cookie_action'] = '';
        }

        if (isset($this->error['filled_website_login_action'])) {
            $this->data['error_filled_website_login_action'] = $this->error['filled_website_login_action'];
        } else {
            $this->data['error_filled_website_login_action'] = '';
        }

        /* Town */

        if (isset($this->request->post['enabled_town'])) {
            $this->data['enabled_town'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_town'])) {
            $this->data['enabled_town'] = false;
        } else {
            $this->data['enabled_town'] = $this->setting->get('enabled_town');
        }

        if (isset($this->request->post['required_town'])) {
            $this->data['required_town'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_town'])) {
            $this->data['required_town'] = false;
        } else {
            $this->data['required_town'] = $this->setting->get('required_town');
        }

        if (isset($this->request->post['default_town'])) {
            $this->data['default_town'] = $this->request->post['default_town'];
        } else {
            $this->data['default_town'] = $this->setting->get('default_town');
        }

        if (isset($this->request->post['maximum_town'])) {
            $this->data['maximum_town'] = $this->request->post['maximum_town'];
        } else {
            $this->data['maximum_town'] = $this->setting->get('maximum_town');
        }

        if (isset($this->request->post['filled_town_cookie_action'])) {
            $this->data['filled_town_cookie_action'] = $this->request->post['filled_town_cookie_action'];
        } else {
            $this->data['filled_town_cookie_action'] = $this->setting->get('filled_town_cookie_action');
        }

        if (isset($this->request->post['filled_town_login_action'])) {
            $this->data['filled_town_login_action'] = $this->request->post['filled_town_login_action'];
        } else {
            $this->data['filled_town_login_action'] = $this->setting->get('filled_town_login_action');
        }

        if (isset($this->error['default_town'])) {
            $this->data['error_default_town'] = $this->error['default_town'];
        } else {
            $this->data['error_default_town'] = '';
        }

        if (isset($this->error['maximum_town'])) {
            $this->data['error_maximum_town'] = $this->error['maximum_town'];
        } else {
            $this->data['error_maximum_town'] = '';
        }

        if (isset($this->error['filled_town_cookie_action'])) {
            $this->data['error_filled_town_cookie_action'] = $this->error['filled_town_cookie_action'];
        } else {
            $this->data['error_filled_town_cookie_action'] = '';
        }

        if (isset($this->error['filled_town_login_action'])) {
            $this->data['error_filled_town_login_action'] = $this->error['filled_town_login_action'];
        } else {
            $this->data['error_filled_town_login_action'] = '';
        }

        /* State */

        if (isset($this->request->post['enabled_state'])) {
            $this->data['enabled_state'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_state'])) {
            $this->data['enabled_state'] = false;
        } else {
            $this->data['enabled_state'] = $this->setting->get('enabled_state');
        }

        if (isset($this->request->post['required_state'])) {
            $this->data['required_state'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_state'])) {
            $this->data['required_state'] = false;
        } else {
            $this->data['required_state'] = $this->setting->get('required_state');
        }

        if (isset($this->request->post['default_state'])) {
            $this->data['default_state'] = $this->request->post['default_state'];
        } else {
            $this->data['default_state'] = $this->setting->get('default_state');
        }

        if (isset($this->request->post['filled_state_cookie_action'])) {
            $this->data['filled_state_cookie_action'] = $this->request->post['filled_state_cookie_action'];
        } else {
            $this->data['filled_state_cookie_action'] = $this->setting->get('filled_state_cookie_action');
        }

        if (isset($this->request->post['filled_state_login_action'])) {
            $this->data['filled_state_login_action'] = $this->request->post['filled_state_login_action'];
        } else {
            $this->data['filled_state_login_action'] = $this->setting->get('filled_state_login_action');
        }

        if (isset($this->error['default_state'])) {
            $this->data['error_default_state'] = $this->error['default_state'];
        } else {
            $this->data['error_default_state'] = '';
        }

        if (isset($this->error['filled_state_cookie_action'])) {
            $this->data['error_filled_state_cookie_action'] = $this->error['filled_state_cookie_action'];
        } else {
            $this->data['error_filled_state_cookie_action'] = '';
        }

        if (isset($this->error['filled_state_login_action'])) {
            $this->data['error_filled_state_login_action'] = $this->error['filled_state_login_action'];
        } else {
            $this->data['error_filled_state_login_action'] = '';
        }

        $this->data['states'] = $this->geo->getStatesByCountryId($this->setting->get('default_country'));

        $this->data['link_states'] = 'index.php?route=manage/states';

        /* Country */

        if (isset($this->request->post['enabled_country'])) {
            $this->data['enabled_country'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_country'])) {
            $this->data['enabled_country'] = false;
        } else {
            $this->data['enabled_country'] = $this->setting->get('enabled_country');
        }

        if (isset($this->request->post['required_country'])) {
            $this->data['required_country'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['required_country'])) {
            $this->data['required_country'] = false;
        } else {
            $this->data['required_country'] = $this->setting->get('required_country');
        }

        if (isset($this->request->post['default_country'])) {
            $this->data['default_country'] = $this->request->post['default_country'];
        } else {
            $this->data['default_country'] = $this->setting->get('default_country');
        }

        if (isset($this->request->post['filled_country_cookie_action'])) {
            $this->data['filled_country_cookie_action'] = $this->request->post['filled_country_cookie_action'];
        } else {
            $this->data['filled_country_cookie_action'] = $this->setting->get('filled_country_cookie_action');
        }

        if (isset($this->request->post['filled_country_login_action'])) {
            $this->data['filled_country_login_action'] = $this->request->post['filled_country_login_action'];
        } else {
            $this->data['filled_country_login_action'] = $this->setting->get('filled_country_login_action');
        }

        if (isset($this->error['default_country'])) {
            $this->data['error_default_country'] = $this->error['default_country'];
        } else {
            $this->data['error_default_country'] = '';
        }

        if (isset($this->error['filled_country_cookie_action'])) {
            $this->data['error_filled_country_cookie_action'] = $this->error['filled_country_cookie_action'];
        } else {
            $this->data['error_filled_country_cookie_action'] = '';
        }

        if (isset($this->error['filled_country_login_action'])) {
            $this->data['error_filled_country_login_action'] = $this->error['filled_country_login_action'];
        } else {
            $this->data['error_filled_country_login_action'] = '';
        }

        $this->data['countries'] = $this->geo->getCountries();

        $this->data['link_countries'] = 'index.php?route=manage/countries';

        /* Question */

        if (isset($this->request->post['enabled_question'])) {
            $this->data['enabled_question'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_question'])) {
            $this->data['enabled_question'] = false;
        } else {
            $this->data['enabled_question'] = $this->setting->get('enabled_question');
        }

        $this->data['link_questions'] = 'index.php?route=manage/questions';

        /* Captcha */

        if (isset($this->request->post['enabled_captcha'])) {
            $this->data['enabled_captcha'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_captcha'])) {
            $this->data['enabled_captcha'] = false;
        } else {
            $this->data['enabled_captcha'] = $this->setting->get('enabled_captcha');
        }

        if (isset($this->request->post['captcha_type'])) {
            $this->data['captcha_type'] = $this->request->post['captcha_type'];
        } else {
            $this->data['captcha_type'] = $this->setting->get('captcha_type');
        }

        if (isset($this->request->post['recaptcha_public_key'])) {
            $this->data['recaptcha_public_key'] = $this->request->post['recaptcha_public_key'];
        } else {
            $this->data['recaptcha_public_key'] = $this->setting->get('recaptcha_public_key');
        }

        if (isset($this->request->post['recaptcha_private_key'])) {
            $this->data['recaptcha_private_key'] = $this->request->post['recaptcha_private_key'];
        } else {
            $this->data['recaptcha_private_key'] = $this->setting->get('recaptcha_private_key');
        }

        if (isset($this->request->post['recaptcha_theme'])) {
            $this->data['recaptcha_theme'] = $this->request->post['recaptcha_theme'];
        } else {
            $this->data['recaptcha_theme'] = $this->setting->get('recaptcha_theme');
        }

        if (isset($this->request->post['recaptcha_size'])) {
            $this->data['recaptcha_size'] = $this->request->post['recaptcha_size'];
        } else {
            $this->data['recaptcha_size'] = $this->setting->get('recaptcha_size');
        }

        if (isset($this->request->post['captcha_width'])) {
            $this->data['captcha_width'] = $this->request->post['captcha_width'];
        } else {
            $this->data['captcha_width'] = $this->setting->get('captcha_width');
        }

        if (isset($this->request->post['captcha_height'])) {
            $this->data['captcha_height'] = $this->request->post['captcha_height'];
        } else {
            $this->data['captcha_height'] = $this->setting->get('captcha_height');
        }

        if (isset($this->request->post['captcha_length'])) {
            $this->data['captcha_length'] = $this->request->post['captcha_length'];
        } else {
            $this->data['captcha_length'] = $this->setting->get('captcha_length');
        }

        if (isset($this->request->post['captcha_lines'])) {
            $this->data['captcha_lines'] = $this->request->post['captcha_lines'];
        } else {
            $this->data['captcha_lines'] = $this->setting->get('captcha_lines');
        }

        if (isset($this->request->post['captcha_circles'])) {
            $this->data['captcha_circles'] = $this->request->post['captcha_circles'];
        } else {
            $this->data['captcha_circles'] = $this->setting->get('captcha_circles');
        }

        if (isset($this->request->post['captcha_squares'])) {
            $this->data['captcha_squares'] = $this->request->post['captcha_squares'];
        } else {
            $this->data['captcha_squares'] = $this->setting->get('captcha_squares');
        }

        if (isset($this->request->post['captcha_dots'])) {
            $this->data['captcha_dots'] = $this->request->post['captcha_dots'];
        } else {
            $this->data['captcha_dots'] = $this->setting->get('captcha_dots');
        }

        if (isset($this->request->post['captcha_text_color'])) {
            $this->data['captcha_text_color'] = $this->request->post['captcha_text_color'];
        } else {
            $this->data['captcha_text_color'] = $this->setting->get('captcha_text_color');
        }

        if (isset($this->request->post['captcha_back_color'])) {
            $this->data['captcha_back_color'] = $this->request->post['captcha_back_color'];
        } else {
            $this->data['captcha_back_color'] = $this->setting->get('captcha_back_color');
        }

        if (isset($this->request->post['captcha_line_color'])) {
            $this->data['captcha_line_color'] = $this->request->post['captcha_line_color'];
        } else {
            $this->data['captcha_line_color'] = $this->setting->get('captcha_line_color');
        }

        if (isset($this->request->post['captcha_circle_color'])) {
            $this->data['captcha_circle_color'] = $this->request->post['captcha_circle_color'];
        } else {
            $this->data['captcha_circle_color'] = $this->setting->get('captcha_circle_color');
        }

        if (isset($this->request->post['captcha_square_color'])) {
            $this->data['captcha_square_color'] = $this->request->post['captcha_square_color'];
        } else {
            $this->data['captcha_square_color'] = $this->setting->get('captcha_square_color');
        }

        if (isset($this->request->post['captcha_dots_color'])) {
            $this->data['captcha_dots_color'] = $this->request->post['captcha_dots_color'];
        } else {
            $this->data['captcha_dots_color'] = $this->setting->get('captcha_dots_color');
        }

        if (isset($this->error['captcha_type'])) {
            $this->data['error_captcha_type'] = $this->error['captcha_type'];
        } else {
            $this->data['error_captcha_type'] = '';
        }

        if (isset($this->error['recaptcha_public_key'])) {
            $this->data['error_recaptcha_public_key'] = $this->error['recaptcha_public_key'];
        } else {
            $this->data['error_recaptcha_public_key'] = '';
        }

        if (isset($this->error['recaptcha_private_key'])) {
            $this->data['error_recaptcha_private_key'] = $this->error['recaptcha_private_key'];
        } else {
            $this->data['error_recaptcha_private_key'] = '';
        }

        if (isset($this->error['recaptcha_theme'])) {
            $this->data['error_recaptcha_theme'] = $this->error['recaptcha_theme'];
        } else {
            $this->data['error_recaptcha_theme'] = '';
        }

        if (isset($this->error['recaptcha_size'])) {
            $this->data['error_recaptcha_size'] = $this->error['recaptcha_size'];
        } else {
            $this->data['error_recaptcha_size'] = '';
        }

        if (isset($this->error['captcha_width'])) {
            $this->data['error_captcha_width'] = $this->error['captcha_width'];
        } else {
            $this->data['error_captcha_width'] = '';
        }

        if (isset($this->error['captcha_height'])) {
            $this->data['error_captcha_height'] = $this->error['captcha_height'];
        } else {
            $this->data['error_captcha_height'] = '';
        }

        if (isset($this->error['captcha_length'])) {
            $this->data['error_captcha_length'] = $this->error['captcha_length'];
        } else {
            $this->data['error_captcha_length'] = '';
        }

        if (isset($this->error['captcha_lines'])) {
            $this->data['error_captcha_lines'] = $this->error['captcha_lines'];
        } else {
            $this->data['error_captcha_lines'] = '';
        }

        if (isset($this->error['captcha_circles'])) {
            $this->data['error_captcha_circles'] = $this->error['captcha_circles'];
        } else {
            $this->data['error_captcha_circles'] = '';
        }

        if (isset($this->error['captcha_squares'])) {
            $this->data['error_captcha_squares'] = $this->error['captcha_squares'];
        } else {
            $this->data['error_captcha_squares'] = '';
        }

        if (isset($this->error['captcha_dots'])) {
            $this->data['error_captcha_dots'] = $this->error['captcha_dots'];
        } else {
            $this->data['error_captcha_dots'] = '';
        }

        if (isset($this->error['captcha_text_color'])) {
            $this->data['error_captcha_text_color'] = $this->error['captcha_text_color'];
        } else {
            $this->data['error_captcha_text_color'] = '';
        }

        if (isset($this->error['captcha_back_color'])) {
            $this->data['error_captcha_back_color'] = $this->error['captcha_back_color'];
        } else {
            $this->data['error_captcha_back_color'] = '';
        }

        if (isset($this->error['captcha_line_color'])) {
            $this->data['error_captcha_line_color'] = $this->error['captcha_line_color'];
        } else {
            $this->data['error_captcha_line_color'] = '';
        }

        if (isset($this->error['captcha_circle_color'])) {
            $this->data['error_captcha_circle_color'] = $this->error['captcha_circle_color'];
        } else {
            $this->data['error_captcha_circle_color'] = '';
        }

        if (isset($this->error['captcha_square_color'])) {
            $this->data['error_captcha_square_color'] = $this->error['captcha_square_color'];
        } else {
            $this->data['error_captcha_square_color'] = '';
        }

        if (isset($this->error['captcha_dots_color'])) {
            $this->data['error_captcha_dots_color'] = $this->error['captcha_dots_color'];
        } else {
            $this->data['error_captcha_dots_color'] = '';
        }

        /* Notify */

        if (isset($this->request->post['enabled_notify'])) {
            $this->data['enabled_notify'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_notify'])) {
            $this->data['enabled_notify'] = false;
        } else {
            $this->data['enabled_notify'] = $this->setting->get('enabled_notify');
        }

        if (isset($this->request->post['default_notify'])) {
            $this->data['default_notify'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['default_notify'])) {
            $this->data['default_notify'] = false;
        } else {
            $this->data['default_notify'] = $this->setting->get('default_notify');
        }

        /* Cookie */

        if (isset($this->request->post['enabled_cookie'])) {
            $this->data['enabled_cookie'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_cookie'])) {
            $this->data['enabled_cookie'] = false;
        } else {
            $this->data['enabled_cookie'] = $this->setting->get('enabled_cookie');
        }

        if (isset($this->request->post['default_cookie'])) {
            $this->data['default_cookie'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['default_cookie'])) {
            $this->data['default_cookie'] = false;
        } else {
            $this->data['default_cookie'] = $this->setting->get('default_cookie');
        }

        /* Privacy */

        if (isset($this->request->post['enabled_privacy'])) {
            $this->data['enabled_privacy'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_privacy'])) {
            $this->data['enabled_privacy'] = false;
        } else {
            $this->data['enabled_privacy'] = $this->setting->get('enabled_privacy');
        }

        /* Terms */

        if (isset($this->request->post['enabled_terms'])) {
            $this->data['enabled_terms'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_terms'])) {
            $this->data['enabled_terms'] = false;
        } else {
            $this->data['enabled_terms'] = $this->setting->get('enabled_terms');
        }

        /* Preview */

        if (isset($this->request->post['enabled_preview'])) {
            $this->data['enabled_preview'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_preview'])) {
            $this->data['enabled_preview'] = false;
        } else {
            $this->data['enabled_preview'] = $this->setting->get('enabled_preview');
        }

        if (isset($this->request->post['agree_to_preview'])) {
            $this->data['agree_to_preview'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['agree_to_preview'])) {
            $this->data['agree_to_preview'] = false;
        } else {
            $this->data['agree_to_preview'] = $this->setting->get('agree_to_preview');
        }

        /* Powered By */

        if (isset($this->request->post['enabled_powered_by'])) {
            $this->data['enabled_powered_by'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['enabled_powered_by'])) {
            $this->data['enabled_powered_by'] = false;
        } else {
            $this->data['enabled_powered_by'] = $this->setting->get('enabled_powered_by');
        }

        if (isset($this->request->post['powered_by_type'])) {
            $this->data['powered_by_type'] = $this->request->post['powered_by_type'];
        } else {
            $this->data['powered_by_type'] = $this->setting->get('powered_by_type');
        }

        if (isset($this->request->post['powered_by_new_window'])) {
            $this->data['powered_by_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['powered_by_new_window'])) {
            $this->data['powered_by_new_window'] = false;
        } else {
            $this->data['powered_by_new_window'] = $this->setting->get('powered_by_new_window');
        }

        if (isset($this->error['enabled_powered_by'])) {
            $this->data['error_enabled_powered_by'] = $this->error['enabled_powered_by'];
        } else {
            $this->data['error_enabled_powered_by'] = '';
        }

        if (isset($this->error['powered_by_type'])) {
            $this->data['error_powered_by_type'] = $this->error['powered_by_type'];
        } else {
            $this->data['error_powered_by_type'] = '';
        }

        $this->data['layout_detect'] = $this->setting->get('layout_detect');

        if ($this->data['layout_detect']) {
            $layout_settings = $this->model_settings_layout_form->checkLayoutSettings();

            if ($layout_settings['enabled']) {
                $this->data['layout_settings'] = $layout_settings['enabled'];

                $this->data['lang_dialog_content'] = sprintf($this->data['lang_dialog_content_enabled'], $this->url->link('settings/layout_comments'));
            } else if ($layout_settings['disabled']) {
                $this->data['layout_settings'] = $layout_settings['disabled'];

                $this->data['lang_dialog_content'] = sprintf($this->data['lang_dialog_content_disabled'], $this->url->link('settings/layout_comments'));
            } else {
                $this->data['layout_settings'] = false;
            }
        }

        if (!$this->setting->get('licence')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/pricing');
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/layout_form');
    }

    public function stopLayoutDetect()
    {
        $this->loadModel('settings/layout_form');

        $this->model_settings_layout_form->stopLayoutDetect();
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

        if (isset($this->request->post['order_fields'])) {
            $order_fields = explode(',', $this->request->post['order_fields']);

            $fields = explode(',', $this->setting->get('order_fields'));

            if (count($order_fields) == count($fields) && count(array_unique($order_fields)) == count($fields)) {
                foreach ($order_fields as $order_field) {
                    if (!in_array($order_field, $fields)) {
                        $this->error['order_fields'] = $this->data['lang_error_selection'];
                    }
                }
            } else {
                $this->error['order_fields'] = $this->data['lang_error_selection'];
            }
        } else {
            $this->error['order_fields'] = $this->data['lang_error_selection'];
        }

        /* Comment */

        if (!isset($this->request->post['default_comment']) || $this->validation->length($this->request->post['default_comment']) > 250) {
            $this->error['default_comment'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['comment_maximum_characters']) || !$this->validation->isInt($this->request->post['comment_maximum_characters']) || $this->request->post['comment_maximum_characters'] < 1 || $this->request->post['comment_maximum_characters'] > 99999) {
            $this->error['comment_maximum_characters'] = sprintf($this->data['lang_error_range'], 1, 99999);
        }

        /* Headline */

        if (!isset($this->request->post['default_headline']) || $this->validation->length($this->request->post['default_headline']) > 250) {
            $this->error['default_headline'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['headline_maximum_characters']) || !$this->validation->isInt($this->request->post['headline_maximum_characters']) || $this->request->post['headline_maximum_characters'] < 1 || $this->request->post['headline_maximum_characters'] > 250) {
            $this->error['headline_maximum_characters'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        /* Upload */

        if (!isset($this->request->post['maximum_upload_size']) || !$this->validation->isFloat($this->request->post['maximum_upload_size']) || $this->request->post['maximum_upload_size'] < 0.1 || $this->request->post['maximum_upload_size'] > 99.9) {
            $this->error['maximum_upload_size'] = $this->data['lang_error_max_size'];
        }

        if (!isset($this->request->post['maximum_upload_amount']) || !$this->validation->isInt($this->request->post['maximum_upload_amount']) || $this->request->post['maximum_upload_amount'] < 1 || $this->request->post['maximum_upload_amount'] > 10) {
            $this->error['maximum_upload_amount'] = sprintf($this->data['lang_error_range'], 1, 10);
        }

        if (!isset($this->request->post['maximum_upload_total']) || !$this->validation->isFloat($this->request->post['maximum_upload_total']) || $this->request->post['maximum_upload_total'] < 0.1 || $this->request->post['maximum_upload_total'] > 99.9) {
            $this->error['maximum_upload_total'] = $this->data['lang_error_max_total'];
        }

        /* Rating */

        if (!isset($this->request->post['default_rating']) || !in_array($this->request->post['default_rating'], array('', '1', '2', '3', '4', '5'))) {
            $this->error['default_rating'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['repeat_rating']) || !in_array($this->request->post['repeat_rating'], array('normal', 'hide'))) {
            $this->error['repeat_rating'] = $this->data['lang_error_selection'];
        }

        /* Name */

        if (!isset($this->request->post['default_name']) || $this->validation->length($this->request->post['default_name']) > 250) {
            $this->error['default_name'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['maximum_name']) || !$this->validation->isInt($this->request->post['maximum_name']) || $this->request->post['maximum_name'] < 1 || $this->request->post['maximum_name'] > 250) {
            $this->error['maximum_name'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['filled_name_cookie_action']) || !in_array($this->request->post['filled_name_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_name_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_name_login_action']) || !in_array($this->request->post['filled_name_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_name_login_action'] = $this->data['lang_error_selection'];
        }

        /* Email */

        if (!isset($this->request->post['default_email']) || $this->validation->length($this->request->post['default_email']) > 250) {
            $this->error['default_email'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['maximum_email']) || !$this->validation->isInt($this->request->post['maximum_email']) || $this->request->post['maximum_email'] < 1 || $this->request->post['maximum_email'] > 250) {
            $this->error['maximum_email'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['filled_email_cookie_action']) || !in_array($this->request->post['filled_email_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_email_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_email_login_action']) || !in_array($this->request->post['filled_email_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_email_login_action'] = $this->data['lang_error_selection'];
        }

        /* Website */

        if (!isset($this->request->post['default_website']) || $this->validation->length($this->request->post['default_website']) > 250) {
            $this->error['default_website'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['maximum_website']) || !$this->validation->isInt($this->request->post['maximum_website']) || $this->request->post['maximum_website'] < 1 || $this->request->post['maximum_website'] > 250) {
            $this->error['maximum_website'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['filled_website_cookie_action']) || !in_array($this->request->post['filled_website_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_website_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_website_login_action']) || !in_array($this->request->post['filled_website_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_website_login_action'] = $this->data['lang_error_selection'];
        }

        /* Town */

        if (!isset($this->request->post['default_town']) || $this->validation->length($this->request->post['default_town']) > 250) {
            $this->error['default_town'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['maximum_town']) || !$this->validation->isInt($this->request->post['maximum_town']) || $this->request->post['maximum_town'] < 1 || $this->request->post['maximum_town'] > 250) {
            $this->error['maximum_town'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['filled_town_cookie_action']) || !in_array($this->request->post['filled_town_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_town_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_town_login_action']) || !in_array($this->request->post['filled_town_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_town_login_action'] = $this->data['lang_error_selection'];
        }

        /* State */

        if (!isset($this->request->post['default_state']) || ($this->request->post['default_state'] && !$this->geo->stateExists($this->request->post['default_state']))) {
            $this->error['default_state'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_state_cookie_action']) || !in_array($this->request->post['filled_state_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_state_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_state_login_action']) || !in_array($this->request->post['filled_state_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_state_login_action'] = $this->data['lang_error_selection'];
        }

        /* Country */

        if (!isset($this->request->post['default_country']) || ($this->request->post['default_country'] && !$this->geo->countryExists($this->request->post['default_country']))) {
            $this->error['default_country'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_country_cookie_action']) || !in_array($this->request->post['filled_country_cookie_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_country_cookie_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['filled_country_login_action']) || !in_array($this->request->post['filled_country_login_action'], array('normal', 'disable', 'hide'))) {
            $this->error['filled_country_login_action'] = $this->data['lang_error_selection'];
        }

        /* Captcha */

        if (isset($this->request->post['enabled_captcha']) && isset($this->request->post['captcha_type']) && $this->request->post['captcha_type'] == 'recaptcha' && !(bool) ini_get('allow_url_fopen')) {
            $this->error['captcha_type'] = $this->data['lang_error_fopen'];
        }

        if (!isset($this->request->post['captcha_type']) || !in_array($this->request->post['captcha_type'], array('recaptcha', 'image'))) {
            $this->error['captcha_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['recaptcha_public_key']) || $this->validation->length($this->request->post['recaptcha_public_key']) > 250) {
            $this->error['recaptcha_public_key'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (isset($this->request->post['enabled_captcha']) && isset($this->request->post['captcha_type']) && $this->request->post['captcha_type'] == 'recaptcha' && isset($this->request->post['recaptcha_public_key']) && $this->validation->length($this->request->post['recaptcha_public_key']) < 1) {
            $this->error['recaptcha_public_key'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['recaptcha_private_key']) || $this->validation->length($this->request->post['recaptcha_private_key']) > 250) {
            $this->error['recaptcha_private_key'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (isset($this->request->post['enabled_captcha']) && isset($this->request->post['captcha_type']) && $this->request->post['captcha_type'] == 'recaptcha' && isset($this->request->post['recaptcha_private_key']) && $this->validation->length($this->request->post['recaptcha_private_key']) < 1) {
            $this->error['recaptcha_private_key'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['recaptcha_theme']) || !in_array($this->request->post['recaptcha_theme'], array('dark', 'light'))) {
            $this->error['recaptcha_theme'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['recaptcha_size']) || !in_array($this->request->post['recaptcha_size'], array('compact', 'normal'))) {
            $this->error['recaptcha_size'] = $this->data['lang_error_selection'];
        }

        if (isset($this->request->post['enabled_captcha']) && isset($this->request->post['captcha_type']) && $this->request->post['captcha_type'] == 'image' && (!function_exists('imagettftext') || !is_callable('imagettftext'))) {
            $this->error['captcha_type'] = $this->data['lang_error_freetype'];
        }

        if (isset($this->request->post['enabled_captcha']) && isset($this->request->post['captcha_type']) && $this->request->post['captcha_type'] == 'image' && !extension_loaded('gd')) {
            $this->error['captcha_type'] = $this->data['lang_error_gd'];
        }

        if (!isset($this->request->post['captcha_width']) || !$this->validation->isInt($this->request->post['captcha_width']) || $this->request->post['captcha_width'] < 1 || $this->request->post['captcha_width'] > 500) {
            $this->error['captcha_width'] = sprintf($this->data['lang_error_range'], 1, 500);
        }

        if (!isset($this->request->post['captcha_height']) || !$this->validation->isInt($this->request->post['captcha_height']) || $this->request->post['captcha_height'] < 1 || $this->request->post['captcha_height'] > 500) {
            $this->error['captcha_height'] = sprintf($this->data['lang_error_range'], 1, 500);
        }

        if (!isset($this->request->post['captcha_length']) || !$this->validation->isInt($this->request->post['captcha_length']) || $this->request->post['captcha_length'] < 1 || $this->request->post['captcha_length'] > 10) {
            $this->error['captcha_length'] = sprintf($this->data['lang_error_range'], 1, 10);
        }

        if (!isset($this->request->post['captcha_lines']) || !$this->validation->isInt($this->request->post['captcha_lines']) || $this->request->post['captcha_lines'] < 0 || $this->request->post['captcha_lines'] > 10) {
            $this->error['captcha_lines'] = sprintf($this->data['lang_error_range'], 0, 10);
        }

        if (!isset($this->request->post['captcha_circles']) || !$this->validation->isInt($this->request->post['captcha_circles']) || $this->request->post['captcha_circles'] < 0 || $this->request->post['captcha_circles'] > 10) {
            $this->error['captcha_circles'] = sprintf($this->data['lang_error_range'], 0, 10);
        }

        if (!isset($this->request->post['captcha_squares']) || !$this->validation->isInt($this->request->post['captcha_squares']) || $this->request->post['captcha_squares'] < 0 || $this->request->post['captcha_squares'] > 10) {
            $this->error['captcha_squares'] = sprintf($this->data['lang_error_range'], 0, 10);
        }

        if (!isset($this->request->post['captcha_dots']) || !$this->validation->isInt($this->request->post['captcha_dots']) || $this->request->post['captcha_dots'] < 0 || $this->request->post['captcha_dots'] > 99) {
            $this->error['captcha_dots'] = sprintf($this->data['lang_error_range'], 0, 99);
        }

        if (!isset($this->request->post['captcha_text_color']) || substr($this->request->post['captcha_text_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_text_color'], '#'))) {
            $this->error['captcha_text_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_text_color']) || $this->validation->length($this->request->post['captcha_text_color']) != 7) {
            $this->error['captcha_text_color'] = $this->data['lang_error_hex_length'];
        }

        if (!isset($this->request->post['captcha_back_color']) || substr($this->request->post['captcha_back_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_back_color'], '#'))) {
            $this->error['captcha_back_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_back_color']) || $this->validation->length($this->request->post['captcha_back_color']) != 7) {
            $this->error['captcha_back_color'] = $this->data['lang_error_hex_length'];
        }

        if (!isset($this->request->post['captcha_line_color']) || substr($this->request->post['captcha_line_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_line_color'], '#'))) {
            $this->error['captcha_line_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_line_color']) || $this->validation->length($this->request->post['captcha_line_color']) != 7) {
            $this->error['captcha_line_color'] = $this->data['lang_error_hex_length'];
        }

        if (!isset($this->request->post['captcha_circle_color']) || substr($this->request->post['captcha_circle_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_circle_color'], '#'))) {
            $this->error['captcha_circle_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_circle_color']) || $this->validation->length($this->request->post['captcha_circle_color']) != 7) {
            $this->error['captcha_circle_color'] = $this->data['lang_error_hex_length'];
        }

        if (!isset($this->request->post['captcha_square_color']) || substr($this->request->post['captcha_square_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_square_color'], '#'))) {
            $this->error['captcha_square_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_square_color']) || $this->validation->length($this->request->post['captcha_square_color']) != 7) {
            $this->error['captcha_square_color'] = $this->data['lang_error_hex_length'];
        }

        if (!isset($this->request->post['captcha_dots_color']) || substr($this->request->post['captcha_dots_color'], 0, 1) != '#' || !$this->validation->isHex(ltrim($this->request->post['captcha_dots_color'], '#'))) {
            $this->error['captcha_dots_color'] = $this->data['lang_error_hex_format'];
        }

        if (!isset($this->request->post['captcha_dots_color']) || $this->validation->length($this->request->post['captcha_dots_color']) != 7) {
            $this->error['captcha_dots_color'] = $this->data['lang_error_hex_length'];
        }

        /* Powered By */

        if (!isset($this->request->post['enabled_powered_by']) && !$this->setting->get('licence')) {
            $this->error['enabled_powered_by'] = $this->data['lang_error_licence'];
        }

        if (!isset($this->request->post['powered_by_type']) || !in_array($this->request->post['powered_by_type'], array('text', 'image'))) {
            $this->error['powered_by_type'] = $this->data['lang_error_selection'];
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
