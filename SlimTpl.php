<?php

namespace App;

class SlimTpl {

  public static $tags = ['{\?', '\?}'];

  public static function compile_file($file, $arr=[], $output=null){

    if( !file_exists($file) )
      die('SlimTpl error: template not exists '.$file);

    if( filemtime($file)<filemtime($output) )
      return;

    $t1 = self::$tags[0];
    $t2 = self::$tags[1];

    $template = file_get_contents($file);

    $patterns = [];
    $replacements = [];
    foreach ($arr as $k => $item) {
      // $patterns []= "/$t1([^$t2])+$t2/"; // works for strtr()
      $k = str_replace('$', '\$', $k);
      $patterns []= "/$t1([\s])+$k([\s])+$t2/";
      $replacements []= $item;
    }

    $compiled = preg_replace($patterns, $replacements, $template);
    $compiled = self::organize($compiled);

    if(!is_null($output))
      return file_put_contents($output, $compiled);
    else
      return $compiled;
    // return strtr($template, $arr);
  }

  public static function organize($html){

    // remove tabs
    $html = trim(preg_replace('/([ ]{2,}|[\t])/', '', $html));

    // array of lines
    $html = explode("\n", $html);

    $html2 = [];
    $cursor = 0;

    // organize
    foreach($html as $k=>$item){
      $open = sizeof(explode('<', $item))-1;
      $close = ( sizeof(explode('</', $item))-1 ) * 2;
      $close2 = sizeof(explode('/>', $item))-1;
      $close3 = sizeof(explode('-->', $item))-1;
      $close4 = sizeof(explode('<![endif]', $item))-1;
      $close5 = sizeof(explode('<!DOCTYPE html>', $item))-1;

      $count = $open - $close - $close2 - $close3 - $close4 - $close5;

      if($cursor + $count < $cursor)
        $cursor = $cursor + $count;

      $space = '';
      for($i=0; $i<$cursor; $i++)
        $space = $space . ' ';

      // $html2 []= ($cursor) . $space . $item; // DEBUG
      $html2 []= $space . $item;

      if($cursor + $count > $cursor)
        $cursor = $cursor + $count;

    }

    return implode("\n", $html2);
  }

}
