<?php
namespace Commentics;

class PartSortByController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/sort_by');

        if ($this->setting->get('show_sort_by_1') && $this->setting->get('show_date')) {
            $this->data['show_sort_by_1'] = true;
        } else {
            $this->data['show_sort_by_1'] = false;
        }

        if ($this->setting->get('show_sort_by_2') && $this->setting->get('show_date')) {
            $this->data['show_sort_by_2'] = true;
        } else {
            $this->data['show_sort_by_2'] = false;
        }

        if ($this->setting->get('show_sort_by_3') && $this->setting->get('show_like')) {
            $this->data['show_sort_by_3'] = true;
        } else {
            $this->data['show_sort_by_3'] = false;
        }

        if ($this->setting->get('show_sort_by_4') && $this->setting->get('show_dislike')) {
            $this->data['show_sort_by_4'] = true;
        } else {
            $this->data['show_sort_by_4'] = false;
        }

        if ($this->setting->get('show_sort_by_5') && $this->setting->get('show_rating')) {
            $this->data['show_sort_by_5'] = true;
        } else {
            $this->data['show_sort_by_5'] = false;
        }

        if ($this->setting->get('show_sort_by_6') && $this->setting->get('show_rating')) {
            $this->data['show_sort_by_6'] = true;
        } else {
            $this->data['show_sort_by_6'] = false;
        }

        if (isset($this->request->post['cmtx_sort_by']) && $this->request->post['cmtx_sort_by']) {
            $comments_order = $this->request->post['cmtx_sort_by'];
        } else {
            $comments_order = $this->setting->get('comments_order');
        }

        if ($comments_order == '1') {
            $this->data['option_1_selected'] = 'selected';
        } else {
            $this->data['option_1_selected'] = '';
        }

        if ($comments_order == '2') {
            $this->data['option_2_selected'] = 'selected';
        } else {
            $this->data['option_2_selected'] = '';
        }

        if ($comments_order == '3') {
            $this->data['option_3_selected'] = 'selected';
        } else {
            $this->data['option_3_selected'] = '';
        }

        if ($comments_order == '4') {
            $this->data['option_4_selected'] = 'selected';
        } else {
            $this->data['option_4_selected'] = '';
        }

        if ($comments_order == '5') {
            $this->data['option_5_selected'] = 'selected';
        } else {
            $this->data['option_5_selected'] = '';
        }

        if ($comments_order == '6') {
            $this->data['option_6_selected'] = 'selected';
        } else {
            $this->data['option_6_selected'] = '';
        }

        $this->data['commentics_url'] = $this->url->getCommenticsUrl();

        $this->data['page_id'] = $this->page->getId();

        return $this->data;
    }
}
