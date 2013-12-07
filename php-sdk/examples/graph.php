<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$urn=explode("/",$_SERVER['REQUEST_URI']);
$currenturn=$urn[count($urn)-1];

?>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# luxotticaphotobooth: http://ogp.me/ns/fb/luxotticaphotobooth#">
        <title>OG Sample Object - Gino</title>

        
        <meta property="og:title" content="Gino" />
        <meta property="og:image" content="http://placekitten.com/600/600" />
        <meta property="og:description" content="This is the description of the object" />
        <meta property="og:determiner" content="a" />
        <meta property="fb:app_id" content="157192541140769" />
        <meta property="og:url" content="http://sunglasshut.wassermanexperience.com/php-sdk/examples/graph.php/<?php echo $currenturn; ?>" />
        <meta property="og:type" content="luxotticaphotobooth:photo" />


        <link type="text/css" rel="stylesheet" href="/stylesheets/app.css" />
    </head>
    <body>
        <div id="wrapper">
            <h1>OG Sample Object - Gino</h1>
            <table border="0" cellspacing="0">
                                            <tr>
              <th class="key">og:title</th>
              <td class="value">Gino</td>
              </tr>
                                                            <tr>
              <th class="key">og:image</th>
              <td class="value">http://placekitten.com/600/600</td>
              </tr>
                                                            <tr>
              <th class="key">og:description</th>
              <td class="value">This is the description of the object</td>
              </tr>
                                                            <tr>
              <th class="key">og:determiner</th>
              <td class="value">a</td>
              </tr>
                                                            <tr>
              <th class="key">fb:app_id</th>
              <td class="value">157192541140769</td>
              </tr>
                                                            <tr>
              <th class="key">og:url</th>
              <td class="value">http://samples.ogp.me/606947376005013</td>
              </tr>
                                                            <tr>
              <th class="key">og:type</th>
              <td class="value">luxotticaphotobooth:photo</td>
              </tr>
                                          </table>
        </div>
    </body>
</html>
