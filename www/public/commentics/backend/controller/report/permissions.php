<?php
namespace Commentics;

class ReportPermissionsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/permissions');

        $this->loadModel('report/permissions');

        $this->data['files'] = array(
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/config.php',
                'information' => $this->data['lang_text_config'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_ROOT . 'config.php')) ? false : true,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_ROOT . 'config.php')) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/backups/',
                'information' => $this->data['lang_text_backups'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_BACKUPS)) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_BACKUPS)) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/cache/database/',
                'information' => $this->data['lang_text_cache_database'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'database/')) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'database/')) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/cache/modification/',
                'information' => $this->data['lang_text_cache_modification'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'modification/')) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'modification/')) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/cache/template/',
                'information' => $this->data['lang_text_cache_template'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'template/')) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_CACHE . 'template/')) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/logs/',
                'information' => $this->data['lang_text_logs'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_LOGS)) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_LOGS)) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/system/logs/errors.log',
                'information' => $this->data['lang_text_errors'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_LOGS . 'errors.log')) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_LOGS . 'errors.log')) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            ),
            array(
                'path'        => '/' . $this->setting->get('commentics_folder') . '/upload/',
                'information' => $this->data['lang_text_upload'],
                'positive'    => ($this->model_report_permissions->isWritable(CMTX_DIR_UPLOAD)) ? true : false,
                'text'        => ($this->model_report_permissions->isWritable(CMTX_DIR_UPLOAD)) ? $this->data['lang_text_writable'] : $this->data['lang_text_not_writable']
            )
        );

        if (!$this->setting->get('check_config')) {
            array_shift($this->data['files']);
        }

        $permissions_ok = true;

        foreach ($this->data['files'] as $files) {
            if (!$files['positive']) {
                $permissions_ok = false;
            }
        }

        if ($permissions_ok) {
            $this->data['success'] = $this->data['lang_message_success'];
        } else {
            $this->data['error'] = $this->data['lang_message_error'];
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/permissions');
    }
}
