<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>{$title}</title>
</head>
<body>
<h1>{$title}</h1>
<ul>
    {foreach $items as $item}
        <li>{$item}</li>
    {/foreach}
</ul>
</body>
</html>