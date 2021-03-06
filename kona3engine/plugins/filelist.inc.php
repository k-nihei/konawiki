<?php
/** #filelist enum files
 * - [args] #filelist(filter, option)
 * - [option] thumb ... show thumbnail(64px)
 * - [option] thumb128 ... show thumbnail(128px)
 */
function kona3plugins_filelist_execute($args) {
  global $kona3conf;
  
  // check args
  $pat = array_shift($args);
  $opt = [];
  foreach ($args as $arg) {
    $arg = trim($arg);
    $opt[$arg] = true;
  }
  $page = $kona3conf['page'];
  $fname = kona3getWikiFile($page);
  $dir = dirname($fname);
  $files = glob($dir.'/*');
  sort($files);

  # filter
  if ($pat != null) {
    $res = array();
    foreach ($files as $f) {
      if (fnmatch($pat, basename($f))) $res[] = $f;
    }
    $files = $res;
  }
  $code = "<ul>";
  foreach ($files as $f) {
    if (is_dir($f)) continue;
    $file = "file:$f";
    $url = kona3getWikiUrl($file, false);
    $name = htmlentities(basename($f));
    $thumb = "";
    if (isset($opt['thumb']) || isset($opt['thumb128'])) {
      $width = 64;
      if (isset($opt['thumb128'])) $width = 128;
      if (preg_match('/\.(png|gif|jpg|jpeg)$/i', $f)) {
        $thumb = "<img src='$url' width='$width'>";
      }
      else { $thumb = '(no thumb)'; }  
    }
    $code .= "<li><a href='$url'>$thumb $name</a></li>\n";
  }
  $code .= "</ul>\n";
  return $code;
}



