<?php
namespace Commentics;

class CommonHeaderController extends Controller
{
    public function index()
    {
        $this->loadLanguage('common/header');

        $this->loadModel('common/header');

        $this->data['jquery'] = $this->loadJavascript('jquery/jquery.min.js');

        $this->data['jquery_ui'] = $this->loadJavascript('jquery/jquery-ui.min.js');

        $this->data['jquery_theme'] = $this->loadJavascript('jquery/jquery-ui.min.css');

        $this->data['stylesheet'] = $this->loadStylesheet('stylesheet.css');

        if (isset($this->session->data['cmtx_admin_id'])) {
            $this->data['full_header'] = true;

            $this->data['logo'] = $this->loadImage('commentics/logo.png');

            $this->data['common'] = $this->loadJavascript('common.js');

            $this->data['has_restriction'] = $this->model_common_header->hasRestriction();

            $this->data['viewable_pages'] = $this->model_common_header->getViewablePages();

            $this->data['csrf_key'] = $this->session->data['cmtx_csrf_key'];

            $this->data['page_help_link'] = '<a href="https://commentics.com/help/' . str_replace('/', '_', $this->request->get['route']) . '" target="_blank">' . $this->data['lang_text_help'] . '</a>';

            $this->data['error_header'] = false;

            $this->data['error_view'] = false;

            if ($this->model_common_header->backendFolderExists()) {
                $this->data['error_header'] = $this->data['lang_error_backend_folder_exists'];
            }

            if ($this->model_common_header->installFolderExists()) {
                $this->data['error_header'] = $this->data['lang_error_install_folder_exists'];
            }

            if ($this->setting->get('check_config') && $this->model_common_header->isConfigWritable()) {
                $this->data['error_header'] = $this->data['lang_error_config_writable'];
            }

            if (!$this->model_common_header->isPageViewable($this->data['has_restriction'], $this->data['viewable_pages'])) {
                $this->data['error_view'] = $this->data['lang_error_page_viewable'];
            }

            $this->data['route'] = $this->request->get['route'];

            if ($this->setting->get('use_wysiwyg')) {
                $this->data['wysiwyg_enabled'] = true;
            } else {
                $this->data['wysiwyg_enabled'] = false;
            }

            if ($this->setting->has('chart_enabled') && $this->setting->get('chart_enabled')) {
                $this->data['show_chart'] = true;
            } else {
                $this->data['show_chart'] = false;
            }

            $this->model_common_header->addView();
        } else {
            $this->data['full_header'] = false;
        }

        return $this->data;
    }
}
