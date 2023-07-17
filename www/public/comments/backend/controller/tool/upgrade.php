<?php
namespace Commentics;

class ToolUpgradeController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        $this->loadModel('main/dashboard');

        $this->data['start'] = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->data['start'] = true;
            }
        }

        $version = $this->model_main_dashboard->getCurrentVersion();

        $versions = $this->home->getVersions();

        $next_version = $this->model_tool_upgrade->getNext($version, $versions);

        $this->data['next_version'] = $next_version;

        unset($this->session->data['cmtx_upgrade_info']);

        if ($next_version) {
            $upgrade_info = array(
                'current_version' => $version,
                'next_version'    => $next_version
            );

            $this->data['changelog'] = $this->model_tool_upgrade->getChangelog($next_version);

            $this->data['lang_text_version'] = sprintf($this->data['lang_text_version'], $next_version);
        } else {
            $upgrade_info = array();
        }

        $this->session->data['cmtx_upgrade_info'] = $upgrade_info;

        if ($next_version && $this->setting->get('notice_tool_upgrade')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('tool/database_backup'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/upgrade');
    }

    public function download()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $version = $this->session->data['cmtx_upgrade_info']['next_version'];

                    $version = str_replace('.', '-', $version);

                    $json['messages'][] = sprintf($this->data['lang_text_download'], 'https://commentics.com/package/commentics-' . $version . '.zip');

                    // Name of the temp folder to store the uploaded zip file
                    $temp_folder = CMTX_DIR_UPLOAD . 'temp-' . $this->variable->random();

                    // Create the temp folder
                    @mkdir($temp_folder, 0777);

                    // Check if the temp folder exists
                    if (is_dir($temp_folder)) {
                        $error = $this->model_tool_upgrade->download($version, $temp_folder);

                        if ($error) {
                            $json['messages'][] = $error;

                            $json['error'] = $this->error();
                        } else {
                            $this->session->data['cmtx_upgrade_info']['temp_folder'] = $temp_folder;
                        }
                    } else {
                        $json['messages'][] = $this->data['lang_error_no_temp_dir'];

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function unpack()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        sleep(3); // Sleep for 3 seconds to help avoid triggering DoS detection

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_unpack'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->unpack($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function verify()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        sleep(3); // Sleep for 3 seconds to help avoid triggering DoS detection

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_verify'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->verify($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function requirements()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        sleep(3); // Sleep for 3 seconds to help avoid triggering DoS detection

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_requirements'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->requirements($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function install()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        sleep(3); // Sleep for 3 seconds to help avoid triggering DoS detection

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_maintenance_y'];

                    $this->model_tool_upgrade->setMaintenanceMode(true);

                    $json['messages'][] = $this->data['lang_text_install'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->install($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function database()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        sleep(3); // Sleep for 3 seconds to help avoid triggering DoS detection

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_database'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->database($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function clean()
    {
        $this->loadLanguage('tool/upgrade');

        $this->loadModel('tool/upgrade');

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();
            $json['messages'] = array();

            if ($this->validate()) {
                if (isset($this->session->data['cmtx_upgrade_info'])) {
                    $json['messages'][] = $this->data['lang_text_maintenance_n'];

                    $this->model_tool_upgrade->setMaintenanceMode(false);

                    $json['messages'][] = $this->data['lang_text_clean'];

                    $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

                    $error = $this->model_tool_upgrade->clean($temp_folder);

                    if ($error) {
                        $json['messages'][] = $error;

                        $json['error'] = $this->error();
                    } else {
                        $this->success();

                        $json['success'] = $this->data['lang_text_complete'];
                    }
                } else {
                    $json['messages'][] = $this->data['lang_error_missing_info'];

                    $json['error'] = $this->error();
                }
            } else {
                $json['messages'][] = $this->data['error'];

                $json['error'] = $this->error();
            }

            echo json_encode($json);
        }
    }

    public function dismiss()
    {
        $this->loadModel('tool/upgrade');

        $this->model_tool_upgrade->dismiss();
    }

    private function error()
    {
        if (isset($this->session->data['cmtx_upgrade_info']) && isset($this->session->data['cmtx_upgrade_info']['temp_folder'])) {
            $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

            remove_directory($temp_folder);
        }

        unset($this->session->data['cmtx_upgrade_info']);

        $message = $this->data['lang_error_failed'];

        return $message;
    }

    private function success()
    {
        $next_version = $this->session->data['cmtx_upgrade_info']['next_version'];

        $this->model_tool_upgrade->setVersion($next_version);

        $temp_folder = $this->session->data['cmtx_upgrade_info']['temp_folder'];

        remove_directory($temp_folder);

        remove_directory(CMTX_DIR_INSTALL);

        remove_directory(CMTX_DIR_CACHE . 'database/', false, false);
        remove_directory(CMTX_DIR_CACHE . 'modification/', false, false);
        remove_directory(CMTX_DIR_CACHE . 'template/', false, false);

        unset($this->session->data['cmtx_upgrade_info']);
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!extension_loaded('zip')) {
            $this->data['error'] = $this->data['lang_error_unable'];

            return false;
        }

        if (!is_writable(CMTX_DIR_UPLOAD)) {
            $this->data['error'] = $this->data['lang_error_permission'];

            return false;
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
