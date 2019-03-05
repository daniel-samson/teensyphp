<?php
/**
 * Parse PHP template file
 * @param string $path of the template
 * @param array $variables an assiciative array of variables which are available
 *                         in the PHP template
 * @return string the parsed template
 */
function template(string $path, array $variables): string
{
    ob_start();
        extract($variables);
        require $path;
        $output = ob_get_contents();
    ob_get_clean();
    return $output;
}
