<?php

namespace Ronijan\BackupDatabase\BackupLocationFile;

class GetFile
{
    public function saveData($content)
    {
        $file = '/db-backup-' . date('Y-m-d-H:i:s') . '.sql';
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . $file, "wb");

        if (fwrite($fp, $content)) {
            echo "done! check path:: " . $file;
        }

        fclose($fp);
    }
}
