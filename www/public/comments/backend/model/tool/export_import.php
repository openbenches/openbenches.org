<?php
namespace Commentics;

class ToolExportImportModel extends Model
{
    public function export($type)
    {
        $this->loadModel('common/language');

        if ($type == 'countries') {
            $this->exportCountries();
        }

        if ($type == 'emails') {
            $this->exportEmails();
        }

        if ($type == 'questions') {
            $this->exportQuestions();
        }
    }

    private function exportCountries()
    {
        $this->response->addHeader('Content-Type: text/csv; charset=utf-8');
        $this->response->addHeader('Content-Disposition: attachment; filename=countries.csv');

        $frontend_languages = $this->model_common_language->getFrontendLanguages();
        $backend_languages = $this->model_common_language->getBackendLanguages();

        $languages = array_merge($frontend_languages, $backend_languages);

        $columns = array();

        $columns[] = 'country code';

        foreach ($languages as $key => $value) {
            $columns[] = $value;
        }

        $output = fopen('php://output', 'w');

        fputcsv($output, $columns);

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries`");

        $countries = $this->db->rows($query);

        foreach ($countries as $country) {
            $data = array();

            $data[] = $country['code'];

            foreach ($languages as $key => $value) {
                $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($country['code']) . "' AND `language` = '" . $this->db->escape($value) . "'");

                $geo = $this->db->row($query);

                if ($geo) {
                    $data[] = $geo['name'];
                } else {
                    $data[] = '';
                }
            }

            fputcsv($output, $data);
        }

        fclose($output);

        die;
    }

    private function exportEmails()
    {
        $this->response->addHeader('Content-Type: text/csv; charset=utf-8');
        $this->response->addHeader('Content-Disposition: attachment; filename=emails.csv');

        $columns = array(
            'type',
            'subject',
            'text',
            'html',
            'language'
        );

        $output = fopen('php://output', 'w');

        fputcsv($output, $columns);

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails`");

        $emails = $this->db->rows($query);

        foreach ($emails as $email) {
            $data = array();

            $data[] = $email['type'];
            $data[] = $email['subject'];
            $data[] = $email['text'];
            $data[] = $email['html'];
            $data[] = $email['language'];

            fputcsv($output, $data);
        }

        fclose($output);

        die;
    }

    private function exportQuestions()
    {
        $this->response->addHeader('Content-Type: text/csv; charset=utf-8');
        $this->response->addHeader('Content-Disposition: attachment; filename=questions.csv');

        $columns = array(
            'id',
            'question',
            'answer',
            'language'
        );

        $output = fopen('php://output', 'w');

        fputcsv($output, $columns);

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions`");

        $questions = $this->db->rows($query);

        foreach ($questions as $question) {
            $data = array();

            $data[] = $question['id'];
            $data[] = $question['question'];
            $data[] = $question['answer'];
            $data[] = $question['language'];

            fputcsv($output, $data);
        }

        fclose($output);

        die;
    }

    public function import()
    {
        $csv_file = $this->request->files['file']['tmp_name'];

        $csv_data = $this->request->getCsvData($csv_file);

        if ($csv_data[0][0] == 'country code') {
            $this->importCountries($csv_data);
        }

        if ($csv_data[0][0] == 'type') {
            $this->importEmails($csv_data);
        }

        if ($csv_data[0][0] == 'id') {
            $this->importQuestions($csv_data);
        }
    }

    /* Can be called from extension installer so keep as public */
    public function importCountries($csv_data)
    {
        $headings = array_shift($csv_data);

        $languages = array_splice($headings, 1, count($headings));

        foreach ($csv_data as $row) {
            $country_code = $row[0];

            // create country if it does not exist
            if (!$this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries` WHERE `code` = '" . $this->db->escape($country_code) . "'"))) {
                // we disable the new country to give the opportunity to add states to it
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = '" . $this->db->escape($country_code) . "', `top` = '0', `enabled` = '0', `date_modified` = NOW(), `date_added` = NOW()");
            }

            foreach ($languages as $key => $value) {
                $country_name = $row[$key + 1];

                $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($country_code) . "' AND `language` = '" . $this->db->escape($value) . "'");

                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = '" . $this->db->escape($country_name) . "', `country_code` = '" . $this->db->escape($country_code) . "', `language` = '" . $this->db->escape($value) . "', `date_added` = NOW()");
            }
        }
    }

    /* Can be called from extension installer so keep as public */
    public function importEmails($csv_data)
    {
        $headings = array_shift($csv_data);

        foreach ($csv_data as $row) {
            $type     = $row[0];
            $subject  = $row[1];
            $text     = $row[2];
            $html     = $row[3];
            $language = $row[4];

            if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = '" . $this->db->escape($language) . "'"))) {
                // update email if it exists
                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "emails` SET `subject` = '" . $this->db->escape($subject) . "', `text` = '" . $this->db->escape($text) . "', `html` = '" . $this->db->escape($html) . "', `date_modified` = NOW() WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = '" . $this->db->escape($language) . "'");
            } else {
                // otherwise create email
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = '" . $this->db->escape($type) . "', `subject` = '" . $this->db->escape($subject) . "', `text` = '" . $this->db->escape($text) . "', `html` = '" . $this->db->escape($html) . "', `language` = '" . $this->db->escape($language) . "', `date_modified` = NOW()");
            }
        }
    }

    /* Can be called from extension installer so keep as public */
    public function importQuestions($csv_data)
    {
        $headings = array_shift($csv_data);

        foreach ($csv_data as $row) {
            $id       = $row[0];
            $question = $row[1];
            $answer   = $row[2];
            $language = $row[3];

            if ($id) {
                // update question if there's an ID
                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "questions` SET `question` = '" . $this->db->escape($question) . "', `answer` = '" . $this->db->escape($answer) . "', `language` = '" . $this->db->escape($language) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
            } else {
                // otherwise create question if no ID
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = '" . $this->db->escape($question) . "', `answer` = '" . $this->db->escape($answer) . "', `language` = '" . $this->db->escape($language) . "', `date_modified` = NOW(), `date_added` = NOW()");
            }
        }
    }
}
