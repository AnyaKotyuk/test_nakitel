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
$files_finder->getFiles();

class Files{

    protected $file_dir = 'main_dir'; // directory name with files
    private $site_dir; // site dir
    private $dir_files; // file list in directory
    protected $file_list_names = 'main_list_name.txt'; // file with files' names
    private $file_file_list; // file names from $file_list_names
    public $uniq_files;


    public function __construct()
    {
        $this->site_dir = $_SERVER['DOCUMENT_ROOT'];
        $this->dir_files = $this->getDirFiles();
        $this->file_file_list = $this->getFileNames();
//        $this->checkFileName($this->file_file_list[0]);
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
            $dir_files = $this->scanDir($sub_dir);
            if (is_array($dir_files)) $files = array_merge($files, $dir_files);
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
        if (!is_dir($dir)) { return false;}
        $sub_dirs = scandir($dir);
        $dirs = array();
        foreach ($sub_dirs as $k => $sub) {
            if ($sub == '.' || $sub == '..') continue;
            $dirs[] = $dir.'/'.$sub;
        }
        return $dirs;
    }

    /**
     * Get file names from file
     *
     * @return array
     */
    protected function getFileNames()
    {
        $file = $this->site_dir.'/'.$this->file_dir.'/'.$this->file_list_names;
        $data = '';
        if (file_exists($file)) {
            $f = fopen($file, "r");
            while ($line = fgets($f)) {
                $data .= $line;
            }
            fclose($f);
        }

        $file_names = explode(',', $data);
        return $file_names;
    }

    protected function checkFileName($file)
    {
        $file_names = $this->file_file_list;
        $file_name = basename($file);
        $find = '';
        // transforn file name to regular expretion
        for ($i = 0; $i<strlen($file_name); $i++) {
            $find[] = $file_name[$i];
        }
        $find = implode(')?(', $find);
        $find = '/('.$find.')/U';
        $find = str_replace('.', '\.',  $find);
        $file_name_length = strlen($file_name);
        foreach ($file_names as $k=>$f) {
            preg_match_all($find, $f, $m, PREG_SET_ORDER);
            $mach = 0;
            foreach ($m as $k=>$v) {
                if ($k == 0) continue;
                if ($v != "" && $file_name[$k] != "") {
                    $mach++;
                }
            }
            $diff = ($file_name_length - $mach)/$file_name_length;
            if ($diff < 0.1) {
                $this->uniq_files[] = $file;
            }
        }

    }


    /**
     * Get files which respond task conditions
     */
    public function getFiles()
    {
        $dir_files = $this->dir_files;
        $files = array();

        foreach ($dir_files as $k=>$dir_file) {
            if ($this->checkFileName($dir_file)) {
                $files[] = $dir_file;
            }
        }


        var_dump($this->uniq_files);
    }

}
