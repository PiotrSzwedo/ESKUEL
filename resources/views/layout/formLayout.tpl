<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    {vite_entry file="form.ts"}
    <script type="module" src="http://localhost:5173/form.ts"></script>
    <title>{block name="title"}{/block} | eskuelMYadmin </title>
    {style_css file="navbar.css"}
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="{$prefix}">
                <img src="{$prefix}/storage/?file=logo.svg" alt="eskuelMYAdmin"/>
            </a>
        </div>
        <ul class="navbar-links">
            <li><a href="{$prefix}/form">Dodaj bazę</a></li>
            <li><a href="{$prefix}/databases">Moje bazy</a></li>
        </ul>
    </div>
</nav>

<main id="app" class="h-90vh-main">
    {block name="content"}{/block}
</main>

<footer class="footer">
    <div class="footer-content">
        <p>© 2025 Piotr Szwedo</p>
        <div class="footer-links">
            <a href="https://github.com/PiotrSzwedo" target="_blank" rel="noopener noreferrer">GitHub</a>
            <a href="https://linkedin.com/in/piotrszwedo" target="_blank" rel="noopener noreferrer">LinkedIn</a>
        </div>
    </div>
</footer>
</body>
</html>
