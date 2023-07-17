<?php
namespace Commentics;

class PartSearchController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/search');

        if (isset($this->request->post['cmtx_search']) && $this->request->post['cmtx_search']) {
            $this->data['search'] = $this->request->post['cmtx_search'];
        } else {
            $this->data['search'] = '';
        }

        $this->data['commentics_url'] = $this->url->getCommenticsUrl();

        $this->data['page_id'] = $this->page->getId();

        return $this->data;
    }
}
