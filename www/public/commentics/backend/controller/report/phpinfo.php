<?php
namespace Commentics;

class ReportPhpinfoController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/phpinfo');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/phpinfo');
    }
}
