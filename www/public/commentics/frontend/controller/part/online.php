<?php
namespace Commentics;

class PartOnlineController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/online');

        $this->loadModel('part/online');

        $this->data['online'] = $this->model_part_online->getNumOnline($this->page->getId());

        /* These are passed to common.js via the template */
        $this->data['cmtx_js_settings_online'] = array(
            'commentics_url'          => $this->url->getCommenticsUrl(),
            'page_id'                 => (int) $this->page->getId(),
            'online_refresh_enabled'  => (bool) $this->setting->get('online_refresh_enabled'),
            'online_refresh_interval' => (int) ($this->setting->get('online_refresh_interval') * 1000)
        );

        $this->data['cmtx_js_settings_online'] = json_encode($this->data['cmtx_js_settings_online']);

        return $this->data;
    }

    public function refresh()
    {
        if ($this->request->isAjax()) {
            if ($this->setting->get('show_online') && $this->setting->get('viewers_enabled')) {
                $this->response->addHeader('Content-Type: application/json');

                $json = array();

                if (isset($this->request->post['cmtx_page_id'])) {
                    $this->loadModel('part/online');

                    $page_id = $this->request->post['cmtx_page_id'];

                    $json['online'] = (int) $this->model_part_online->getNumOnline($page_id);
                }

                echo json_encode($json);
            }
        }
    }
}
