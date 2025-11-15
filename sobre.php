<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sobre a MyCups 🍰</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f9f5ff;
      color: #333;
    }

    header {
      background-color: #6a0dad; /* Roxo principal */
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    header h1 {
      font-size: 1.5rem;
      letter-spacing: 1px;
    }

    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .hero {
      background-color: #7e3ff2;
      color: white;
      text-align: center;
      padding: 80px 20px;
      border-bottom-left-radius: 30px;
      border-bottom-right-radius: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .hero h2 {
      font-size: 2.4rem;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.1rem;
    }

    .container {
      max-width: 1000px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .sobre {
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .sobre h3 {
      color: #6a0dad;
      margin-bottom: 15px;
      font-size: 1.8rem;
    }

    .sobre p {
      line-height: 1.6;
      font-size: 1rem;
      margin-bottom: 15px;
    }

    .valores {
      background-color: #efe4ff;
      border-radius: 20px;
      padding: 40px 20px;
      text-align: center;
      margin-top: 50px;
    }

    .valores h3 {
      color: #6a0dad;
      margin-bottom: 20px;
      font-size: 1.6rem;
    }

    .valores p {
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }

    footer {
      background-color: #6a0dad;
      color: white;
      text-align: center;
      padding: 15px 10px;
      margin-top: 60px;
    }

    @media (max-width: 768px) {
      .hero h2 {
        font-size: 2rem;
      }
      header {
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>MyCups 🍰</h1>
    <nav>
      <a href="index.php">Início</a>
      <a href="sobre.php">Sobre</a>
      <a href="meus_pedidos.php">Meus Pedidos</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Sobre a MyCups</h2>
    <p>Deliciosos cupcakes com entrega rápida e sabor inesquecível 💜</p>
  </section>

  <section class="container">
    <div class="sobre">
      <h3>Nossa História</h3>
      <p>A <strong>MyCups</strong> nasceu com um sonho simples: levar doçura, praticidade e alegria para o dia a dia das pessoas. Cada cupcake é preparado com ingredientes de alta qualidade e muito amor.</p>
      <p>Com foco em <strong>entrega rápida</strong>, garantimos que seus pedidos cheguem sempre fresquinhos — perfeitos para celebrar, presentear ou adoçar um momento especial.</p>
      <p>Somos uma equipe apaixonada por confeitaria e acreditamos que um bom cupcake pode transformar qualquer dia em algo melhor.</p>
    </div>

    <div class="valores">
      <h3>Nossos Valores</h3>
      <p>💜 Qualidade em cada detalhe  
      🚀 Agilidade nas entregas  
      🍰 Amor em cada receita  
      😊 Alegria em cada mordida</p>
    </div>
  </section>

  <footer>
    <p>© 2025 MyCups — Feito com 💜 e cobertura extra</p>
  </footer>
</body>
</html>
