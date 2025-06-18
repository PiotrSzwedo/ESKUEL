<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <script src="https://unpkg.com/vue@3"></script>
    {vite_entry file="form.ts"}
    <title>{block name="title"}Domyślny tytuł{/block}</title>
</head>
<body>

<nav>

</nav>

<main id="app">
    {block name="content"}{/block}
</main>

<footer>
    &copy; 2025 Moja Firma
</footer>
</body>
</html>
