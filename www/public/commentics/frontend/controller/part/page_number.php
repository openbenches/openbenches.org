<?php
namespace Commentics;

class PartPageNumberController extends Controller
{
    public function index($component_data)
    {
        $this->loadLanguage('part/page_number');

        $current_page = $component_data['current_page'];

        $total_pages = $component_data['total_pages'];

        if ($this->setting->get('page_number_format') == 'Page X') {
            $this->data['page_number'] = sprintf($this->data['lang_text_page_x'], $current_page);
        } else { // Page X of Y
            $this->data['page_number'] = sprintf($this->data['lang_text_page_x_of_y'], $current_page, $total_pages);
        }

        return $this->data;
    }
}
