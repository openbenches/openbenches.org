<?php
namespace Commentics;

class PartAverageRatingController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/average_rating');

        $this->loadModel('part/average_rating');

        $average_rating = $this->model_part_average_rating->getAverageRating($this->page->getId());

        $this->data['average_rating'] = $average_rating['average'];

        $this->data['num_of_ratings'] = $average_rating['total'];

        $this->data['commentics_url'] = $this->url->getCommenticsUrl();

        $this->data['page_id'] = $this->page->getId();

        if ($this->setting->get('average_rating_guest')) {
            $this->data['average_rating_guest'] = 'cmtx_average_rating_can_rate';
        } else {
            $this->data['average_rating_guest'] = 'cmtx_average_rating_cannot_rate';
        }

        if ($this->setting->has('rich_snippets_enabled') && $this->setting->get('rich_snippets_enabled')) {
            $this->data['rich_snippets_enabled'] = true;

            $this->loadModel('module/rich_snippets');

            $this->data['rich_snippets_properties'] = $this->model_module_rich_snippets->getRichSnippetsProperties();
        } else {
            $this->data['rich_snippets_enabled'] = false;
        }

        if ($this->data['average_rating'] == 5) {
            $this->data['rating_5_checked'] = 'checked';
        } else {
            $this->data['rating_5_checked'] = '';
        }

        if ($this->data['average_rating'] == 4) {
            $this->data['rating_4_checked'] = 'checked';
        } else {
            $this->data['rating_4_checked'] = '';
        }

        if ($this->data['average_rating'] == 3) {
            $this->data['rating_3_checked'] = 'checked';
        } else {
            $this->data['rating_3_checked'] = '';
        }

        if ($this->data['average_rating'] == 2) {
            $this->data['rating_2_checked'] = 'checked';
        } else {
            $this->data['rating_2_checked'] = '';
        }

        if ($this->data['average_rating'] == 1) {
            $this->data['rating_1_checked'] = 'checked';
        } else {
            $this->data['rating_1_checked'] = '';
        }

        return $this->data;
    }

    public function rate()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['cmtx_page_id']) && isset($this->request->post['cmtx_rating']) && preg_match('/[1-5]/', $this->request->post['cmtx_rating'])) {
                $this->loadLanguage('part/average_rating');

                $this->loadModel('part/average_rating');

                $page_id = $this->request->post['cmtx_page_id'];

                $rating = $this->request->post['cmtx_rating'];

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if (!$this->setting->get('show_average_rating')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if (!$this->setting->get('average_rating_guest')) { // check if guest rating enabled
                    $json['error'] = $this->data['lang_error_guest'];
                } else if (!$this->page->pageExists($page_id)) { // check if page exists
                    $json['error'] = $this->data['lang_error_no_page'];
                } else if ($this->model_part_average_rating->hasAlreadyRatedPage($page_id, $ip_address)) { // check if user has already rated this page
                    $json['error'] = $this->data['lang_error_rate_already'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    $this->model_part_average_rating->addRating($page_id, $rating, $ip_address);

                    $this->cache->delete('getaveragerating_pageid' . $page_id);

                    $json['success'] = $this->data['lang_error_rated'];

                    $average_rating = $this->model_part_average_rating->getAverageRating($page_id);

                    $json['average_rating'] = $average_rating['average'];

                    $json['num_of_ratings'] = $average_rating['total'];
                }
            }

            echo json_encode($json);
        }
    }
}
