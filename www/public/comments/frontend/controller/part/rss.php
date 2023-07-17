<?php
namespace Commentics;

class PartRssController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/rss');

        if ($this->setting->get('rss_new_window')) {
            $this->data['new_window'] = 'target="_blank"';
        } else {
            $this->data['new_window'] = '';
        }

        $this->data['url'] = $this->url->getCommenticsUrl() . 'frontend/index.php?route=part/rss/rss&amp;id=' . $this->page->getId();

        return $this->data;
    }

    public function rss()
    {
        if ($this->setting->get('show_rss') && !$this->setting->get('maintenance_mode')) {
            if (isset($this->request->get['id']) && $this->validation->isInt($this->request->get['id']) && $this->page->pageExists($this->request->get['id'])) {
                $this->response->addHeader('Content-Type:text/xml; charset=utf-8');

                $this->loadLanguage('part/rss');

                $this->loadModel('part/rss');

                $page = $this->page->getPage($this->request->get['id']);

                $site = $this->site->getSite($page['site_id']);

                if ($this->setting->get('rss_limit_enabled')) {
                    $limit = $this->setting->get('rss_limit_amount');
                } else {
                    $limit = false;
                }

                $comments = $this->model_part_rss->getComments($this->request->get['id'], $limit);

                $output = '';

                $output .= '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;

                $output .= '<rss version="2.0">' . PHP_EOL;

                $output .= '<channel>' . PHP_EOL;
                $output .= '<title><![CDATA[' . $site['name'] . ']]></title>' . PHP_EOL;
                $output .= '<link><![CDATA[' . $site['url'] . ']]></link>' . PHP_EOL;
                $output .= '<description><![CDATA[' . $this->data['lang_text_description'] . ']]></description>' . PHP_EOL;
                $output .= '<generator>Commentics</generator>' . PHP_EOL;

                foreach ($comments as $comment) {
                    foreach ($comment['uploads'] as $upload) {
                        if (file_exists(CMTX_DIR_UPLOAD . $upload['folder'] . '/' . $upload['filename'] . '.' . $upload['extension'])) {
                            $comment['comment'] .= '<img src="' . $this->url->getCommenticsUrl() . 'upload/' . $upload['folder'] . '/' . $upload['filename'] . '.' . $upload['extension'] . '" width="200"> ';
                        }
                    }

                    $output .= '<item>' . PHP_EOL;
                    $output .= '<title>' . sprintf($this->data['lang_text_poster'], $comment['name']) . '</title>' . PHP_EOL;
                    $output .= '<link><![CDATA[' . $this->comment->buildCommentUrl($comment['id'], $comment['page_url']) . ']]></link>' . PHP_EOL;
                    $output .= '<description><![CDATA[' . $comment['comment'] . ']]></description>' . PHP_EOL;
                    $output .= '<pubDate>' . date('r', strtotime($comment['date_added'])) . '</pubDate>' . PHP_EOL;
                    $output .= '<guid isPermaLink="false">' . 'item_' . $comment['id'] . '</guid>' . PHP_EOL;
                    $output .= '</item>' . PHP_EOL;
                }
                $output .= '</channel>' . PHP_EOL;

                $output .= '</rss>';

                echo $output;
            }
        }
    }
}
