<?php

namespace Ronijan\BackupDatabase\BackupLocationFile;

class GetFile
{
    public function saveData($content, $folderPath = '')
    {
      $isWritable = false;
      $file = $folderPath . 'db-backup-' . date('Y-m-d-H:i:s') . '.sql';
      $fp = fopen($file, "wb");

      if (fwrite($fp, $content)) {
          $isWritable = true;
          echo "done! check path:: " . $file;
      }

      fclose($fp);

      return ($isWritable === true);
    }
}
