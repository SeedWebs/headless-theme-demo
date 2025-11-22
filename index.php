<?php

$url = get_bloginfo('url');
echo "<p style='font-family:monospace;padding:5%;text-align:center'>";
echo "Use the Rest API to access the posts: ";
echo "<a href='{$url}/wp-json/wp/v2/posts'><strong>/wp-json/wp-v2/posts</strong></a>";
echo "</p>";
