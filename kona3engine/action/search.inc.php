<?php
include_once dirname(dirname(__FILE__)).'/kona3lib.inc.php';

function kona3_action_search() {
  global $kona3conf;
  $page = $kona3conf["page"];
  $action = kona3getPageURL($page, "search");
  
  $am   = kona3param('a_mode', '');
  $key  = kona3param('a_key', '');

  $res= '';
  if ($am == "search") {
    $result = array();
    $path_data = $kona3conf["path.data"];
    kona3search($key, $result, $path_data);
    foreach ($result as $f) {
      $path = str_replace("$path_data/", "", $f);
      $path = preg_replace('/\.(txt|md)$/', '', $path);
      $enc = urlencode($path);
      $res .= "<li><a href='index.php?$enc'>$path</li>";
      
    }
  }
  if ($res != "") $res = "<ul>$res</ul>\n";
  $key_ = kona3text2html($key);

  // show form
  $form = <<<EOS
<div>
  <form method="post" action="$action">
    <input type="hidden" name="a_mode" value="search">
    <input type="text" name="a_key" value="$key_">
    <input type="submit" value="Search">
  </form>
</div>
<div>
{$res}
</div>
EOS;
  // show
  kona3template('message', array(
    "page_title" => kona3text2html($page),
    "page_body"  => $form,
  ));
}

function kona3search($key, &$result, $dir) {
  global $kona3conf;
  if ($key == "") return;
  $flist = glob($dir.'/*');
  foreach ($flist as $f) {
    if ($f == "." || $f == "..") continue;
    if (is_dir($f)) {
      kona3search($key, $result, $f);
      continue;
    }
    if (preg_match('/\.(md|txt)$/', $f)) {
      $txt = @file_get_contents($f);
      if (strpos($txt, $key) !== FALSE) {
        $result[] = $f;
        continue;
      }
    }
  }
}







