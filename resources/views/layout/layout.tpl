<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>{block name="title"}Domyślny tytuł{/block}</title>
    <style>
        .footer {
            background-color: #f9f9f9;
            color: #333;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e0e0e0;
            font-family: system-ui, sans-serif;
            font-size: 0.95rem;
        }

        .footer-content {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links {
            display: flex;
            gap: 1rem;
        }

        .footer-links a {
            color: #007acc;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #005fa3;
        }

        .h-90vh-main{
            min-height: 90vh;
        }
    </style>
</head>
<body>
<header>

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
