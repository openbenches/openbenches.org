<?php
namespace Commentics;

class PartTopicController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/topic');

        $this->data['topic'] = $this->page->getReference();

        if ($this->setting->has('rich_snippets_enabled') && $this->setting->get('rich_snippets_enabled')) {
            $this->data['rich_snippets_enabled'] = true;
        } else {
            $this->data['rich_snippets_enabled'] = false;
        }

        return $this->data;
    }
}
