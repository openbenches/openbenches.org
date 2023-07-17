<?php
namespace Commentics;

class ExtensionModulesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('extension/modules');

        $this->loadModel('extension/modules');

        $installed = $this->model_extension_modules->getInstalled();

        $files = $this->model_extension_modules->getFiles();

        $this->data['modules'] = array();

        foreach ($files as $file) {
            $module = basename($file, '.php');

            $name = $this->loadWord('module/' . $module, 'lang_heading');

            $this->data['modules'][] = array(
                'module' => $module,
                'name' => $name,
                'installed' => (in_array($module, $installed)) ? true : false,
                'url' => 'index.php?route=module/' . $module
            );
        }

        $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/getmodules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('extension/modules');
    }

    public function install()
    {
        if (!isset($this->request->post['module'])) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('extension/modules');

        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;
        } else {
            if (isset($this->request->post['module']) && preg_match('/^[a-z0-9_]+$/i', $this->request->post['module']) && file_exists(CMTX_DIR_CONTROLLER . 'module/' . $this->request->post['module'] . '.php')) {
                $this->loadModel('extension/modules');

                $installed = $this->model_extension_modules->getInstalled();

                if (in_array($this->request->post['module'], $installed)) {
                    $this->response->redirect('extension/modules');
                }

                $this->model_extension_modules->install($this->request->post);

                require_once CMTX_DIR_CONTROLLER . 'module/' . $this->request->post['module'] . '.php';

                $class = '\Commentics\\' . 'Module' . str_replace('_', '', $this->request->post['module']) . 'Controller';

                $controller = new $class($this->registry);

                if (method_exists($controller, 'install') && is_callable(array($controller, 'install'))) {
                    $controller->install();
                }

                $module = $this->request->post['module'];

                $name = $this->loadWord('module/' . $module, 'lang_heading');

                $this->data['success'] = sprintf($this->data['lang_message_installed'], $name);
            } else {
                $this->response->redirect('extension/modules');
            }
        }

        $this->index();
    }

    public function uninstall()
    {
        if (!isset($this->request->post['module'])) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('extension/modules');

        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;
        } else {
            if (isset($this->request->post['module']) && preg_match('/^[a-z0-9_]+$/i', $this->request->post['module']) && file_exists(CMTX_DIR_CONTROLLER . 'module/' . $this->request->post['module'] . '.php')) {
                $this->loadModel('extension/modules');

                $installed = $this->model_extension_modules->getInstalled();

                if (!in_array($this->request->post['module'], $installed)) {
                    $this->response->redirect('extension/modules');
                }

                $this->model_extension_modules->uninstall($this->request->post);

                require_once CMTX_DIR_CONTROLLER . 'module/' . $this->request->post['module'] . '.php';

                $class = '\Commentics\\' . 'Module' . str_replace('_', '', $this->request->post['module']) . 'Controller';

                $controller = new $class($this->registry);

                if (method_exists($controller, 'uninstall') && is_callable(array($controller, 'uninstall'))) {
                    $controller->uninstall();
                }

                $module = $this->request->post['module'];

                $name = $this->loadWord('module/' . $module, 'lang_heading');

                $this->data['success'] = sprintf($this->data['lang_message_uninstalled'], $name);
            } else {
                $this->response->redirect('extension/modules');
            }
        }

        $this->index();
    }
}
