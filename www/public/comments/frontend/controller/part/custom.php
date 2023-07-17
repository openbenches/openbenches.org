<?php
namespace Commentics;

class PartCustomController extends Controller
{
    public function index()
    {
        $this->data['custom_content'] = $this->security->decode($this->setting->get('custom_content'));

        return $this->data;
    }
}
