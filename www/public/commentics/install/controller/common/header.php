<?php
namespace Commentics;

class CommonHeaderController extends Controller
{
    public function index()
    {
        $this->loadLanguage('common/header');

        $this->data['stylesheet'] = $this->loadStylesheet('stylesheet.css');

        $this->data['jquery'] = $this->loadJavascript('jquery/jquery.min.js');

        $this->data['common'] = $this->loadJavascript('common.js');

        $this->data['logo'] = $this->loadImage('commentics/logo.png');

        return $this->data;
    }
}
