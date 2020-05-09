<?php

namespace XoopsModules\Modulebuilder\Files;

/* The 'nl' function */
/**
 * @param int $tabs
 *
 * @return string
 */
function nl($tabs = 0)
{
    $r = "\n";
    for ($i = 0; $i < $tabs; ++$i) {
        $r .= '   ';
    }

    return $r;
}

/* Usage example */
$show_table = true;

echo '<!doctype html>';
echo nl() . '<html>' . nl(1) . '<head>' . nl(2) . '<title>This is a title</title>' . nl(1) . '</head>' . nl(1) . '<body>' . nl(2) . '<div id="some_container">';
if ($show_table) {
    echo nl(3) . '<table>' . nl(4) . '<tr>' . nl(5) . '<td>This is a cell in a table</td>' . nl(4) . '</tr>' . nl(3) . '</table>';
}
echo nl(2) . '</div><!--some_container-->' . nl(2) . '<p>This is some text in a paragraph</p>' . nl(1) . '</body>' . nl() . '</html>';
?>
<!doctype html>
<html>
<head>
    <title>This is a title</title>
</head>
<body>
<div id="some_container">
    <table>
        <tr>
            <td>This is a cell in a table</td>
        </tr>
    </table>
</div><!--some_container-->
<p>This is some text in a paragraph</p>
</body>
</html>
