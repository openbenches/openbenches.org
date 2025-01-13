<?php
namespace Commentics;

class ToolClearCacheModel extends Model
{
    public function clear($data)
    {
        if (isset($data['database'])) {
            remove_directory(CMTX_DIR_CACHE . 'database/', false, false);
        }

        if (isset($data['modification'])) {
            remove_directory(CMTX_DIR_CACHE . 'modification/', false, false);
        }

        if (isset($data['template'])) {
            remove_directory(CMTX_DIR_CACHE . 'template/', false, false);
        }
    }
}
