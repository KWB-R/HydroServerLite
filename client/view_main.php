<?php

require_once "session_handler.php";
require 'internationalize.php';
require_once "_html_parts.php";

HTML_Render_Head();

echo $CSS_Main;

HTML_Render_Body_Start();

echo "<div class='col-md-9' style='height:500px;'>";
require_once "map.php"; 
echo "</div></div>";

HTML_Render_Body_End();
?>