<?php
namespace Commentics;

class Geo
{
    private $cache;
    private $db;
    private $setting;

    public function __construct($registry)
    {
        $this->cache   = $registry->get('cache');
        $this->db      = $registry->get('db');
        $this->setting = $registry->get('setting');
    }

    public function countryExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function countryExistsByCode($code, $exclude_id = 0)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries` WHERE `code` = '" . $this->db->escape($code) . "' AND `id` != '" . (int) $exclude_id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function countryValid($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "' AND `enabled` = '1'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getCountry($id)
    {
        $query = $this->db->query("SELECT `c`.*, `g`.`name`
                                   FROM `" . CMTX_DB_PREFIX . "countries` `c`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "geo` `g` ON `g`.`country_code` = `c`.`code`
                                   WHERE `c`.`id` = '" . (int) $id . "'
                                   AND `g`.`language` = '" . $this->db->escape($this->setting->get('language')) . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getCountries($all = false)
    {
        if (defined('CMTX_FRONTEND')) { // only cache frontend calls
            $countries = $this->cache->get('getcountries_' . $this->setting->get('language'));

            if ($countries !== false) {
                return $countries;
            }
        }

        if ($all) {
            $status = '0,1';
        } else {
            $status = '1';
        }

        $countries = array();

        $query = $this->db->query("SELECT `c`.*, `g`.`name`
                                   FROM `" . CMTX_DB_PREFIX . "countries` `c`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "geo` `g` ON `g`.`country_code` = `c`.`code`
                                   WHERE `c`.`enabled` IN (" . $status . ")
                                   AND `g`.`language` = '" . $this->db->escape($this->setting->get('language')) . "'
                                   ORDER BY `c`.`top` DESC, `g`.`name` ASC");

        if ($this->db->numRows($query)) {
            $is_top = false;

            $results = $this->db->rows($query);

            foreach ($results as $key => $value) {
                if ($key == 0 && $value['top'] == 1) {
                    $is_top = true;

                    $countries[] = array(
                        'id'   => '',
                        'name' => '---',
                        'code' => ''
                    );
                }

                if ($is_top && $value['top'] == 0) {
                    $is_top = false;

                    $countries[] = array(
                        'id'   => '',
                        'name' => '---',
                        'code' => ''
                    );
                }

                $countries[] = array(
                    'id'   => $value['id'],
                    'name' => $value['name'],
                    'code' => $value['code']
                );
            }
        }

        if (defined('CMTX_FRONTEND')) { // only cache frontend calls
            $this->cache->set('getcountries_' . $this->setting->get('language'), $countries);
        }

        return $countries;
    }

    public function deleteCountry($id)
    {
        $query = $this->db->query("SELECT `code` FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        $code = $result['code'];

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($code) . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "states` WHERE `country_code` = '" . $this->db->escape($code) . "'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `country_id` = '0', `state_id` = '0' WHERE `country_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    public function stateExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function stateValid($id, $country_id = 0)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` WHERE `id` = '" . (int) $id . "' AND `enabled` = '1'"))) {
            if ($country_id) {
                /* Make sure the submitted state belongs to the submitted country */
                $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` `s`
                                           RIGHT JOIN `" . CMTX_DB_PREFIX . "countries` `c` ON `s`.`country_code` = `c`.`code`
                                           WHERE `s`.`id` = '" . (int) $id . "'
                                           AND `c`.`id` = '" . (int) $country_id . "'");

                if ($this->db->numRows($query)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getState($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getStates()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` WHERE `enabled` = '1' ORDER BY `name` ASC");

        $results = $this->db->rows($query);

        return $results;
    }

    public function getStatesByCountryId($id)
    {
        if (defined('CMTX_FRONTEND')) { // only cache frontend calls
            $states = $this->cache->get('getstates_countryid' . $id);

            if ($states !== false) {
                return $states;
            }
        }

        $query = $this->db->query("SELECT `code` FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        if ($result) {
            $code = $result['code'];

            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states`
                                       WHERE `country_code` = '" . $this->db->escape($code) . "'
                                       AND `enabled` = '1'
                                       ORDER BY `name` ASC");

            $results = $this->db->rows($query);
        } else {
            $results = array();
        }

        if (defined('CMTX_FRONTEND')) { // only cache frontend calls
            $this->cache->set('getstates_countryid' . $id, $results);
        }

        return $results;
    }

    public function deleteState($id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `state_id` = '0' WHERE `state_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "states` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }
}
