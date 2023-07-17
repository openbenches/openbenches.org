<?php
namespace Commentics;

class ExtensionLanguagesModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['language_frontend']) . "' WHERE `title` = 'language_frontend'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['language_backend']) . "' WHERE `title` = 'language_backend'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['rtl']) ? 1 : 0) . "' WHERE `title` = 'rtl'");

        if ($data['language_frontend'] != $this->setting->get('language_frontend')) {
            $this->translateCountries($data['language_frontend']);
        }

        if ($data['language_backend'] != $this->setting->get('language_backend') && $data['language_backend'] != $data['language_frontend']) {
            $this->translateCountries($data['language_backend']);
        }
    }

    /* Make sure all countries are translated into the chosen language, otherwise no countries will show */
    private function translateCountries($language)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "geo` WHERE `language` = 'english'");

        $results = $this->db->rows($query);

        foreach ($results as $geo) {
            if (!$this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($geo['country_code']) . "' AND `language` = '" . $this->db->escape($language) . "'"))) {
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = '" . $this->db->escape($geo['name']) . "', `country_code` = '" . $this->db->escape($geo['country_code']) . "', `language` = '" . $this->db->escape($language) . "', `date_added` = NOW()");
            }
        }
    }
}
