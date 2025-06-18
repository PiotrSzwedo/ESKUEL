<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Błąd 403 - Dostęp zabroniony</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans&display=swap');

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Open Sans', sans-serif;
      background: #ededed;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
      text-align: center;
      padding: 20px;
    }

    .container {
      background: rgba(255 255 255 / 0.85);
      padding: 40px 30px;
      border-radius: 12px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      transition: transform 0.3s ease;
    }
    .container:hover {
      transform: translateY(-6px);
    }

    h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 6rem;
      margin: 0 0 10px;
      color: #d90a0a;
      user-select: none;
    }

    h2 {
      font-weight: 700;
      margin: 0 0 15px;
      font-size: 1.75rem;
      color: #333;
    }

    p {
      font-size: 1rem;
      margin: 0 0 30px;
      color: #555;
      line-height: 1.5;
    }

    a.button {
      display: inline-block;
      text-decoration: none;
      background: #d90a0a;
      color: #fff;
      padding: 12px 28px;
      font-weight: 600;
      border-radius: 30px;
      box-shadow: 0 6px 12px rgba(255, 74, 104, 0.4);
      transition: background-color 0.3s ease;
    }

    a.button:hover,
    a.button:focus {
      background: #e03e59;
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 4.5rem;
      }
      .container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
<div class="container" role="alert" aria-labelledby="error-title" aria-describedby="error-desc">
  <h1 id="error-title">403</h1>
  <h2>Dostęp zabroniony</h2>
  <p id="error-desc">Niestety, nie masz uprawnień, aby zobaczyć tę stronę.</p>
</div>
</body>
</html>
