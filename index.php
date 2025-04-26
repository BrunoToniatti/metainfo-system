<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - HealthData Manager</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #a7d2d9 0%, #53c5c5 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    .login-container {
      background: white;
      padding: 3rem;
      border-radius: 20px;
      box-shadow: 0px 0px 30px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
      animation: popUp 0.8s ease;
    }

    @keyframes popUp {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .login-container h1 {
      margin-bottom: 2rem;
      color: #53c5c5;
    }

    .form-group {
      margin-bottom: 1.5rem;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #333;
    }

    .form-group input {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 1rem;
      transition: 0.3s;
    }

    .form-group input:focus {
      border-color: #53c5c5;
      outline: none;
      transform: scale(1.02);
    }

    .login-btn {
      width: 100%;
      padding: 0.9rem;
      background: linear-gradient(135deg, #4db8b8, #a7d2d9);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s, transform 0.3s;
    }

    .login-btn:hover {
      transform: scale(1.05);
      background: linear-gradient(135deg, #a7d2d9, #4db8b8);
    }

    .error-message {
      color: red;
      font-size: 0.9rem;
      margin-top: 1rem;
      display: none;
      animation: shake 0.5s ease;
    }

    @keyframes shake {
      0% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      75% { transform: translateX(-5px); }
      100% { transform: translateX(0); }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Bem-vindo de volta!</h1>
    <form id="loginForm">
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-btn">Entrar</button>
      <div class="error-message" id="errorMessage">Usuário ou senha inválidos.</div>
    </form>
  </div>

  <script>
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', function(event) {
      event.preventDefault();

      const email = form.email.value.trim();
      const password = form.password.value.trim();

      if (email === "admin@healthdata.com" && password === "123456") {
        form.querySelector('.login-btn').textContent = "Entrando...";
        setTimeout(() => {
          window.location.href = "/dashboard.html";
        }, 1000);
      } else {
        errorMessage.style.display = 'block';
        setTimeout(() => {
          errorMessage.style.display = 'none';
        }, 2500);
      }
    });

    // Animação ao carregar
    window.addEventListener('load', () => {
      document.body.style.opacity = 1;
    });
  </script>
</body>
</html>
