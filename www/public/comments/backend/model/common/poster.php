<?php
namespace Commentics;

class CommonPosterModel extends Model
{
    public function unpostable($data)
    {
        $error = '';

        $restrict_pages = $this->restrict_pages();

        $modifiable_pages = $this->modifiable_pages();

        if (!$this->page_modifiable($restrict_pages, $modifiable_pages)) {
            $error = $data['lang_error_page_modifiable'];
        }

        if ($this->setting->get('check_referrer')) {
            if (isset($this->request->server['HTTP_REFERER'])) {
                $referrer = $this->url->decode($this->request->server['HTTP_REFERER']);

                $domain = $this->url->decode(preg_replace('/^www\./i', '', $this->request->server['SERVER_NAME']));

                if (!stristr($referrer, $domain)) {
                    $error = $data['lang_error_referrer_external'];
                }
            }
        }

        if (!isset($this->request->post['csrf_key']) || !isset($this->session->data['cmtx_csrf_key']) || $this->request->post['csrf_key'] != $this->session->data['cmtx_csrf_key']) {
            $error = $data['lang_error_csrf_key'];
        }

        if ($error) {
            $this->request->server['REQUEST_METHOD'] = '';

            $this->request->post = array();
        }

        return $error;
    }

    private function restrict_pages()
    {
        $query = $this->db->query("SELECT `restrict_pages` FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $this->session->data['cmtx_admin_id'] . "'");

        $result = $this->db->row($query);

        return $result['restrict_pages'];
    }

    private function modifiable_pages()
    {
        $query = $this->db->query("SELECT `modifiable_pages` FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $this->session->data['cmtx_admin_id'] . "'");

        $result = $this->db->row($query);

        $result = explode(',', $result['modifiable_pages']);

        return $result;
    }

    private function page_modifiable($restrict_pages, $modifiable_pages)
    {
        if (!$restrict_pages || ($restrict_pages && in_array($this->request->get['route'], $modifiable_pages)) || ($this->variable->stristr($this->request->get['route'], 'module/') && in_array('module/', $modifiable_pages))) {
            return true;
        } else {
            return false;
        }
    }
}
