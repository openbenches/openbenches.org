<?php
namespace Commentics;

class ReportPermissionsModel extends Model
{
    public function isWritable($file)
    {
        if (is_writable($file)) {
            return true;
        } else {
            return false;
        }
    }
}
