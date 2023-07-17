<?php
namespace Commentics;

class ExtensionThemesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('extension/themes');

        $this->loadModel('extension/themes');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_extension_themes->update($this->request->post);
            }
        }

        if (isset($this->request->post['theme_frontend'])) {
            $this->data['theme_frontend'] = $this->request->post['theme_frontend'];
        } else {
            $this->data['theme_frontend'] = $this->setting->get('theme_frontend');
        }

        if (isset($this->request->post['theme_backend'])) {
            $this->data['theme_backend'] = $this->request->post['theme_backend'];
        } else {
            $this->data['theme_backend'] = $this->setting->get('theme_backend');
        }

        if (isset($this->request->post['auto_detect'])) {
            $this->data['auto_detect'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['auto_detect'])) {
            $this->data['auto_detect'] = false;
        } else {
            $this->data['auto_detect'] = $this->setting->get('auto_detect');
        }

        if (isset($this->request->post['optimize'])) {
            $this->data['optimize'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['optimize'])) {
            $this->data['optimize'] = false;
        } else {
            $this->data['optimize'] = $this->setting->get('optimize');
        }

        if (isset($this->request->post['jquery_source'])) {
            $this->data['jquery_source'] = $this->request->post['jquery_source'];
        } else {
            $this->data['jquery_source'] = $this->setting->get('jquery_source');
        }

        if (isset($this->request->post['order_parts'])) {
            $this->data['order_parts'] = $this->request->post['order_parts'];
        } else {
            $this->data['order_parts'] = $this->setting->get('order_parts');
        }

        if (isset($this->error['theme_frontend'])) {
            $this->data['error_theme_frontend'] = $this->error['theme_frontend'];
        } else {
            $this->data['error_theme_frontend'] = '';
        }

        if (isset($this->error['theme_backend'])) {
            $this->data['error_theme_backend'] = $this->error['theme_backend'];
        } else {
            $this->data['error_theme_backend'] = '';
        }

        if (isset($this->error['jquery_source'])) {
            $this->data['error_jquery_source'] = $this->error['jquery_source'];
        } else {
            $this->data['error_jquery_source'] = '';
        }

        if (isset($this->error['font_awesome_source'])) {
            $this->data['error_font_awesome_source'] = $this->error['font_awesome_source'];
        } else {
            $this->data['error_font_awesome_source'] = '';
        }

        if (isset($this->error['order_parts'])) {
            $this->data['error_order_parts'] = $this->error['order_parts'];
        } else {
            $this->data['error_order_parts'] = '';
        }

        $this->data['frontend_themes'] = $this->model_extension_themes->getFrontendThemes();

        $this->data['backend_themes'] = $this->model_extension_themes->getBackendThemes();

        $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/getthemes');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('extension/themes');
    }

    public function previewFrontend()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['theme'])) {
                $theme = basename($this->request->post['theme']);

                if (file_exists(CMTX_DIR_FRONTEND . 'view/' . $theme . '/image/preview.png')) {
                    $json['preview'] = $this->setting->get('commentics_url') . 'frontend/view/' . $theme . '/image/preview.png';
                } else {
                    $json['preview'] = $this->setting->get('commentics_url') . 'frontend/view/default/image/preview.png';
                }
            } else {
                $json['preview'] = $this->setting->get('commentics_url') . 'frontend/view/default/image/preview.png';
            }

            echo json_encode($json);
        }
    }

    public function previewBackend()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['theme'])) {
                $theme = basename($this->request->post['theme']);

                if (file_exists(CMTX_DIR_VIEW . $theme . '/image/preview.png')) {
                    $json['preview'] = CMTX_HTTP_VIEW . $theme . '/image/preview.png';
                } else {
                    $json['preview'] = CMTX_HTTP_VIEW . 'default/image/preview.png';
                }
            } else {
                $json['preview'] = CMTX_HTTP_VIEW . 'default/image/preview.png';
            }

            echo json_encode($json);
        }
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('extension/themes');

        if (!isset($this->request->post['theme_frontend']) || !in_array($this->request->post['theme_frontend'], $this->model_extension_themes->getFrontendThemes())) {
            $this->error['theme_frontend'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['theme_backend']) || !in_array($this->request->post['theme_backend'], $this->model_extension_themes->getBackendThemes())) {
            $this->error['theme_backend'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['jquery_source']) || !in_array($this->request->post['jquery_source'], array('', 'local', 'google', 'jquery'))) {
            $this->error['jquery_source'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['order_parts']) || !in_array($this->request->post['order_parts'], array('form,comments', 'comments,form'))) {
            $this->error['order_parts'] = $this->data['lang_error_selection'];
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
