<!DOCTYPE html>
<html lang="pl">
<head>
{*    {vite_entry file="matherBoard.ts"}*}
    <script type="module" src="http://localhost:5173/matherBoard.ts"></script>
    <meta charset="UTF-8" />
    <title>{block name="title"}Domyślny tytuł{/block} | eskuelMYadmin</title>
    {style_css file="navbar.css"}
</head>
<body>
<header>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <a href="{$prefix}">
                    <img src="{$prefix}/storage/?file=logo.svg" alt="eskuelMYAdmin"/>
                </a>
            </div>
            <ul class="navbar-links">
                <li><a href="{$prefix}/sql">SQL</a></li>
                <li><a href="{$prefix}/eskuel">ESKUEL</a></li>
                <li><a href="{$prefix}/disconnect" class="disconnect">Rozłącz</a></li>
            </ul>
        </div>
    </nav>
</header>

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
