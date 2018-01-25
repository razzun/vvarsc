<?php
function strip_tags_and_attrs($html_str, $allowed_tags, $allowed_attrs){
  $xml = new DOMDocument();
//Suppress warnings: proper error handling is beyond scope of example
  libxml_use_internal_errors(true);
  if (!strlen($html_str)){return false;}
  if ($xml->loadHTML($html_str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
    foreach ($xml->getElementsByTagName("*") as $tag){
      if (!in_array($tag->tagName, $allowed_tags)){
        $tag->parentNode->removeChild($tag);
      }else{
        foreach ($tag->attributes as $attr){
          if (!in_array($attr->nodeName, $allowed_attrs)){
            $tag->removeAttribute($attr->nodeName);
          }
        }
      }
    }
  }
  return $xml->saveHTML();
}
?>

<!--
//List the tags you want to allow here, NOTE you MUST allow html and body otherwise entire string will be cleared
$allowed_tags = array("html", "body", "b", "br", "em", "hr", "i", "li", "ol", "p", "s", "span", "table", "tr", "td", "u", "ul");

//List the attributes you want to allow here
$allowed_attrs = array ("class", "id", "style");
-->