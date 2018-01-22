<?php

$a = 'string';

$find = '';

for ($i = 0; $i<strlen($a); $i++) {
    $find[] = $a[$i];
}

$find = implode(')?(', $find);



$find = '/('.$find.')/U';
//var_dump($find);
preg_match_all($find, 'stgr', $m);
//var_dump($m);

$files_finder = new Files;
$files_finder->getDirFiles();

class Files{

    protected $file_dir = 'main_dir';
    private $site_dir;
    private $dir_files;


    public function __construct()
    {
        $this->site_dir = $_SERVER['DOCUMENT_ROOT'];
        $this->dir_files = $this->getDirFiles();
    }

    /**
     * Get full files list
     *
     * @return array|bool
     */
    public function getDirFiles()
    {
        $dir = $this->site_dir.'/'.$this->file_dir;
        $sub_dirs = $this->scanDir($dir);
        if (!$sub_dirs) return false;
        $files = array();
        foreach ($sub_dirs as $k=>$sub_dir) {
            $dir_full_path = $dir.'/'.$sub_dir;
            $files[] = $this->scanDir($dir_full_path);
        }
        return $files;
    }


    /**
     * Get directory content
     *
     * @param $dir
     * @return array|bool
     */
    private function scanDir($dir)
    {
        if (!is_dir($dir)) return false;
        $sub_dirs = scandir($dir);
        return $sub_dirs;
    }

}
