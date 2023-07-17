<?php
namespace {
    if (!function_exists('remove_directory')) {
        function remove_directory($dir, $inclusive = true, $delete_index = true)
        {
            if (is_dir($dir)) {
                $i = new DirectoryIterator($dir);

                foreach ($i as $f) {
                    if ($f->isFile()) {
                        if ($delete_index || !$delete_index && $f->getFilename() != 'index.html') {
                            @unlink($f->getRealPath());
                        }
                    } else if (!$f->isDot() && $f->isDir()) {
                        remove_directory($f->getRealPath());
                    }
                }

                if ($inclusive && is_dir($dir)) {
                    @rmdir($dir);
                }
            }
        }
    }
}
