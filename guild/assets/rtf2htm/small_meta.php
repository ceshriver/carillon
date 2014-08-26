<?
$pattern=array("(&AMP;)","(&GT;)","(&LT;)","(&NBSP;)","(&#09;)");
$replace=array("&amp;","&gt;","&lt;","&nbsp;","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
$output=preg_replace($pattern,$replace,$input);
?>
