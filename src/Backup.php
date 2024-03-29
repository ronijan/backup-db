<?php

namespace Ronijan\BackupDatabase;

use Ronijan\BackupDatabase\BackupLocationFile\GetFile;


class Backup
{
    protected string $host;
    protected string $username;
    protected string $password;
    protected string $dbName;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbName
     */
    public function __construct(string $host, string $username, string $password, string $dbName)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
    }

    protected function sqlConnect()
    {
        return mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
    }

    public function start($pathToFolder = ''): bool
    {
        // connect to db
        $sql = $this->sqlConnect();

        // Check connection if errors
        $this->checkErrors();

        // set UTF-8
        $this->setUTF($sql);

        // get tables
        $tables = $this->getAllTables($sql);

        // check all tables
        $result = $this->loopThroughAllTables($tables, $sql);

        // save file
        $isSaved = (new GetFile)->saveData($result, $pathToFolder);

        // return true if backup successful
        return ($isSaved === true);
    }

    private function loopThroughAllTables($tables, $link): string
    {
        $content = '';
        //cycle through
        foreach ($tables as $table) {
            $result = mysqli_query($link, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);
            $num_rows = mysqli_num_rows($result);

            $content .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
            $content .= "\n\n" . $row2[1] . ";\n\n";
            $counter = 1;

            //Over tables
            for ($i = 0; $i < $num_fields; $i++) {
                //Over rows
                while ($row = mysqli_fetch_row($result)) {

                    if ($counter === 1) {
                        $content .= 'INSERT INTO ' . $table . ' VALUES(';
                    } else {
                        $content .= '(';
                    }

                    //Over fields
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $content .= '"' . $row[$j] . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $content .= ',';
                        }
                    }

                    if ($num_rows === $counter) {
                        $content .= ");\n";
                    } else {
                        $content .= "),\n";
                    }
                    ++$counter;
                }
            }
            $content .= "\n\n\n";
        }

        return $content;
    }

    private function setUTF($sql): void
    {
        mysqli_query($sql, "SET NAMES 'utf8'");
    }

    private function checkErrors(): void
    {
        if (mysqli_connect_errno()) {
            echo "Connection Failed: " . mysqli_connect_error();
            exit;
        }
    }

    private function getAllTables($link): array
    {
        $tables = [];
        $result = mysqli_query($link, 'SHOW TABLES');

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        return $tables;
    }
}
