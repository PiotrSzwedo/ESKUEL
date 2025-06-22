<!DOCTYPE html>
<html lang="pl">
<head>
    {vite_entry file="matherBoard.ts"}
    <script type="module" src="http://localhost:5173/matherBoard.ts"></script>
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

        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 2rem;
            font-family: system-ui, sans-serif;
        }

        .navbar-container {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
            color: #007acc;
        }

        .navbar-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            margin: 0;
            padding: 0;
        }

        .navbar-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .navbar-links a:hover {
            color: #007acc;
        }

        .navbar-brand img{
            width: 50%;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <a href="/eskuelmyadmin">
                <img src="/eskuelmyadmin/storage/?file=logo.svg" alt="eskuelMYAdmin"/>
                </a>
            </div>
            <ul class="navbar-links">
                <li><a href="/eskuelmyadmin/sql">SQL</a></li>
                <li><a href="/eskuelmyadmin/eskuel">ESKUEL</a></li>
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
