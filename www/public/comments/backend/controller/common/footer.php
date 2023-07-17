<?php
namespace Commentics;

class CommonFooterController extends Controller
{
    public function index()
    {
        /* These are passed to common.js via the template */
        $this->data['js_settings'] = array();

        $this->data['js_settings']['lang_entry_property'] = $this->loadWord('module/rich_snippets', 'lang_entry_property');
        $this->data['js_settings']['lang_placeholder_name'] = $this->loadWord('module/rich_snippets', 'lang_placeholder_name');
        $this->data['js_settings']['lang_placeholder_value'] = $this->loadWord('module/rich_snippets', 'lang_placeholder_value');

        $this->data['js_settings']['lang_link_more'] = $this->loadWord('data/list', 'lang_link_more');
        $this->data['js_settings']['lang_link_less'] = $this->loadWord('data/list', 'lang_link_less');

        $this->data['js_settings']['lang_text_comments'] = $this->loadWord('main/dashboard', 'lang_text_comments');
        $this->data['js_settings']['lang_text_subscriptions'] = $this->loadWord('main/dashboard', 'lang_text_subscriptions');

        $this->data['js_settings']['lang_text_nobody'] = $this->loadWord('edit/comment', 'lang_text_nobody');

        $this->loadLanguage('general');

        $this->data['js_settings']['lang_text_yes'] = $this->data['lang_text_yes'];
        $this->data['js_settings']['lang_text_no'] = $this->data['lang_text_no'];
        $this->data['js_settings']['lang_link_remove'] = $this->data['lang_link_remove'];
        $this->data['js_settings']['lang_dialog_stop'] = $this->data['lang_dialog_stop'];
        $this->data['js_settings']['lang_dialog_close'] = $this->data['lang_dialog_close'];
        $this->data['js_settings']['lang_select_select'] = $this->data['lang_select_select'];
        $this->data['js_settings']['lang_january'] = $this->data['lang_january'];
        $this->data['js_settings']['lang_february'] = $this->data['lang_february'];
        $this->data['js_settings']['lang_march'] = $this->data['lang_march'];
        $this->data['js_settings']['lang_april'] = $this->data['lang_april'];
        $this->data['js_settings']['lang_may'] = $this->data['lang_may'];
        $this->data['js_settings']['lang_june'] = $this->data['lang_june'];
        $this->data['js_settings']['lang_july'] = $this->data['lang_july'];
        $this->data['js_settings']['lang_august'] = $this->data['lang_august'];
        $this->data['js_settings']['lang_september'] = $this->data['lang_september'];
        $this->data['js_settings']['lang_october'] = $this->data['lang_october'];
        $this->data['js_settings']['lang_november'] = $this->data['lang_november'];
        $this->data['js_settings']['lang_december'] = $this->data['lang_december'];

        $this->data['js_settings']['loading'] = $this->loadImage('misc/loading.gif');

        $this->data['js_settings'] = json_encode($this->data['js_settings']);

        return $this->data;
    }
}
