<?php
namespace Commentics;

class Page
{
    private $db;
    private $request;
    private $security;
    private $session;
    private $url;
    private $id = 0;
    private $identifier = '';
    private $reference = '';
    private $page_url = '';
    private $form_enabled = true;
    private $iframe = false;
    private $site_id = 0;

    public function __construct($registry)
    {
        $this->db       = $registry->get('db');
        $this->request  = $registry->get('request');
        $this->security = $registry->get('security');
        $this->session  = $registry->get('session');
        $this->url      = $registry->get('url');

        if (defined('CMTX_IDENTIFIER')) {
            $this->identifier = $this->security->encode(CMTX_IDENTIFIER);
        }

        if (defined('CMTX_REFERENCE')) {
            $this->reference = $this->security->encode(CMTX_REFERENCE);
        }

        if (defined('CMTX_URL')) {
            $this->page_url = $this->security->encode(CMTX_URL);
        } else {
            $this->page_url = $this->url->getPageUrl();
        }

        if (defined('CMTX_IS_IFRAME') || $this->session->getName() == 'commentics-iframe-session' || !empty($this->request->post['cmtx_iframe'])) {
            $this->iframe = true;
        }

        if ($this->identifier) {
            $domain = str_ireplace('www.', '', parse_url($this->url->decode($this->page_url), PHP_URL_HOST));

            if ($this->iframe && isset($this->request->server['HTTP_REFERER']) && $this->request->server['HTTP_REFERER']) {
                $referrer = str_ireplace('www.', '', parse_url($this->url->decode($this->request->server['HTTP_REFERER']), PHP_URL_HOST));

                if ($referrer) {
                    if ($domain != $referrer) {
                        die('<b>Error:</b> Could not be loaded from the domain \'' . $this->security->encode($referrer) . '\'');
                    }
                }
            }

            if ($domain) {
                $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites` WHERE `domain` = '" . $this->db->escape($domain) . "'");

                $site = $this->db->row($query);

                if ($site) {
                    if ($this->iframe && !$site['iframe_enabled']) {
                        die('<b>Error:</b> iFrame usage is disabled for this site');
                    } else {
                        $this->site_id = $site['id'];
                    }
                } else {
                    die('<b>Error:</b> No site found with the domain \'' . $this->security->encode($domain) . '\' (<a href="https://commentics.com/faq/iframe-integration/no-site-found" target="_blank">Learn more</a>)');
                }
            } else {
                die('<b>Error:</b> No domain provided');
            }

            $page = $this->getPageByIdentifier($this->identifier, $this->site_id);

            if ($page) {
                $this->id = $page['id'];

                $this->page_url = $page['url'];

                $this->form_enabled = $page['is_form_enabled'];
            } else {
                if ($site['new_pages']) {
                    if (isset($this->session->data['cmtx_block'])) {
                        die('<b>Error:</b> Commentics could not be loaded');
                    } else {
                        $this->id = $this->createPage();
                    }
                } else {
                    die('<b>Error:</b> New page creation is disabled for this site');
                }
            }
        } else if ($this->request->isAjax() && isset($this->request->post['cmtx_page_id']) && $this->pageExists($this->request->post['cmtx_page_id'])) {
            $this->id = $this->request->post['cmtx_page_id'];

            $page = $this->getPage($this->id);

            $this->reference = $page['reference'];

            $this->page_url = $page['url'];

            $this->site_id = $page['site_id'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getUrl()
    {
        return $this->page_url;
    }

    public function isFormEnabled()
    {
        return $this->form_enabled;
    }

    public function isIFrame()
    {
        return $this->iframe;
    }

    public function getSiteId()
    {
        return $this->site_id;
    }

    public function setSiteId($site_id)
    {
        $this->site_id = $site_id;
    }

    public function pageExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "pages` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getPageByIdentifier($identifier, $site_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "pages` WHERE `identifier` = '" . $this->db->escape($identifier) . "' AND `site_id` = '" . (int) $site_id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function createPage()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "pages` SET `site_id` = '" . (int) $this->site_id . "', `identifier` = '" . $this->db->escape($this->identifier) . "', `reference` = '" . $this->db->escape($this->reference) . "', `url` = '" . $this->db->escape($this->page_url) . "', `moderate` = 'default', `is_form_enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        return $this->db->insertId();
    }

    public function getPage($id)
    {
        $query = $this->db->query("SELECT `p`.*,
                                   (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "subscriptions` `s` WHERE `s`.`page_id` = `p`.`id`) AS `subscriptions`,
                                   (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "comments` `c` WHERE `c`.`page_id` = `p`.`id`) AS `comments`
                                   FROM `" . CMTX_DB_PREFIX . "pages` `p`
                                   WHERE `p`.`id` = '" . (int) $id . "'");

        if ($this->db->numRows($query)) {
            $page = $this->db->row($query);

            return array(
                'id'              => $page['id'],
                'site_id'         => $page['site_id'],
                'identifier'      => $page['identifier'],
                'reference'       => $page['reference'],
                'url'             => $page['url'],
                'comments'        => $page['comments'],
                'subscriptions'   => $page['subscriptions'],
                'moderate'        => $page['moderate'],
                'is_form_enabled' => $page['is_form_enabled'],
                'date_modified'   => $page['date_modified'],
                'date_added'      => $page['date_added']
            );
        } else {
            return false;
        }
    }

    public function getPages($sort = 'id', $order = 'ASC')
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "pages` ORDER BY `" . $sort . "` " . $order);

        $results = $this->db->rows($query);

        $pages = array();

        foreach ($results as $result) {
            $pages[$result['id']] = $this->getPage($result['id']);
        }

        return $pages;
    }

    public function deletePage($id)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "comments` WHERE `page_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "ratings` WHERE `page_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `page_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "pages` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }
}
