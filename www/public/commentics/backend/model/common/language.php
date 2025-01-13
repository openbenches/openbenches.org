<?php
namespace Commentics;

class CommonLanguageModel extends Model
{
    public function getFrontendLanguages()
    {
        $languages = array();

        foreach (glob(CMTX_DIR_ROOT . 'frontend/view/*/language/*', GLOB_ONLYDIR) as $directory) {
            $language = basename($directory);

            $language_name = $this->getFriendlyLanguageName($language);

            $languages[$language_name] = $this->variable->strtolower($language);
        }

        return $languages;
    }

    public function getBackendLanguages()
    {
        $languages = array();

        foreach (glob(CMTX_DIR_VIEW . '*/language/*', GLOB_ONLYDIR) as $directory) {
            $language = basename($directory);

            $language_name = $this->getFriendlyLanguageName($language);

            $languages[$language_name] = $this->variable->strtolower($language);
        }

        return $languages;
    }

    public function getFriendlyLanguageName($language)
    {
        $language = str_replace('_', ' ', $language);

        $language = $this->variable->fixCase($language);

        return $language;
    }
}
