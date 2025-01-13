<?php
namespace Commentics;

class SettingsCacheController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/cache');

        $this->loadModel('settings/cache');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (!$this->request->post['cache_type']) {
                    $this->cache->flush();
                }

                $this->model_settings_cache->update($this->request->post);
            }
        }

        if (isset($this->request->post['cache_type'])) {
            $this->data['cache_type'] = $this->request->post['cache_type'];
        } else {
            $this->data['cache_type'] = $this->setting->get('cache_type');
        }

        if (isset($this->request->post['cache_time'])) {
            $this->data['cache_time'] = $this->request->post['cache_time'];
        } else {
            $this->data['cache_time'] = $this->setting->get('cache_time');
        }

        if (isset($this->request->post['cache_host'])) {
            $this->data['cache_host'] = $this->request->post['cache_host'];
        } else {
            $this->data['cache_host'] = $this->setting->get('cache_host');
        }

        if (isset($this->request->post['cache_port'])) {
            $this->data['cache_port'] = $this->request->post['cache_port'];
        } else {
            $this->data['cache_port'] = $this->setting->get('cache_port');
        }

        if (isset($this->error['cache_type'])) {
            $this->data['error_cache_type'] = $this->error['cache_type'];
        } else {
            $this->data['error_cache_type'] = '';
        }

        if (isset($this->error['cache_time'])) {
            $this->data['error_cache_time'] = $this->error['cache_time'];
        } else {
            $this->data['error_cache_time'] = '';
        }

        if (isset($this->error['cache_host'])) {
            $this->data['error_cache_host'] = $this->error['cache_host'];
        } else {
            $this->data['error_cache_host'] = '';
        }

        if (isset($this->error['cache_port'])) {
            $this->data['error_cache_port'] = $this->error['cache_port'];
        } else {
            $this->data['error_cache_port'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/cache');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['cache_type']) || !in_array($this->request->post['cache_type'], array('', 'file', 'memcached', 'redis'))) {
            $this->error['cache_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['cache_time']) || !$this->validation->isInt($this->request->post['cache_time']) || $this->request->post['cache_time'] < 60 || $this->request->post['cache_time'] > 31556952) {
            $this->error['cache_time'] = sprintf($this->data['lang_error_range'], 60, 31556952);
        }

        if (isset($this->request->post['cache_type']) && in_array($this->request->post['cache_type'], array('memcached', 'redis'))) {
            if (!isset($this->request->post['cache_host']) || $this->validation->length($this->request->post['cache_host']) < 1 || $this->validation->length($this->request->post['cache_host']) > 250) {
                $this->error['cache_host'] = sprintf($this->data['lang_error_length'], 1, 250);
            }

            if (!isset($this->request->post['cache_port']) || $this->validation->length($this->request->post['cache_port']) > 250) {
                $this->error['cache_port'] = sprintf($this->data['lang_error_length'], 0, 250);
            }
        }

        if (!$this->error && $this->request->post['cache_type']) {
            if ($this->request->post['cache_type'] == 'file') {
                if (!is_writable(CMTX_DIR_CACHE . 'database/')) {
                    $this->error['cache_type'] = $this->data['lang_error_file'];
                }
            } else if ($this->request->post['cache_type'] == 'memcached') {
                if (class_exists('Memcached')) {
                    $memcached = new \Memcached();

                    $connected = @$memcached->addServer($this->request->post['cache_host'], $this->request->post['cache_port']);

                    if (!$connected) {
                        $this->error['cache_type'] = $this->data['lang_error_memcached_connect'];
                    }
                } else {
                    $this->error['cache_type'] = $this->data['lang_error_memcached_class'];
                }
            } else if ($this->request->post['cache_type'] == 'redis') {
                if (class_exists('Redis')) {
                    $redis = new \Redis();

                    try {
                        if ($this->request->post['cache_port']) {
                            @$redis->connect($this->request->post['cache_host'], $this->request->post['cache_port']);
                        } else {
                            @$redis->connect($this->request->post['cache_host']);
                        }
                    } catch (\Exception $e) {
                        $this->error['cache_type'] = $e->getMessage();
                    }
                } else {
                    $this->error['cache_type'] = $this->data['lang_error_redis_class'];
                }
            }
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_success'];

            return true;
        }
    }
}
