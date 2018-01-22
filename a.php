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
var_dump($m);


class Files{


    public function get_filesList()
    {

    }
}
