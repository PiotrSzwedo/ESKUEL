<?php

return [
    // by default the prefix is
    // the directory where escuela my admin is installed,
    // the prefix will not be there if eskuelmyadmin is installed in the root directory of the web server
    "prefix" => getPrefix(),

    // defines how links are created
    // by default by redirecting to index
    "path_with_redirection_to_index" => true
];

function getPrefix(): string
{
    $path = realpath(__DIR__);
    $parentPath = dirname($path);
    $relative = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', $parentPath);

    return $relative;
}