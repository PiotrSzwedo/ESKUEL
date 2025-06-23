<?php

return [
    // by default the prefix is
    // the directory where escuelamyadmin is installed,
    // the prefix will not be there if eskuelmyadmin is installed in the root directory of the web server
    "prefix" => getPrefix(),

    // link to access the built frontend files
    "link_serving_the_built_frontend" => "/vite",
];

function getPrefix(): string
{
    $path = realpath(__DIR__);
    $parentPath = dirname($path);
    $relative = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', $parentPath);

    return $relative;
}