<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - HealthData Manager</title>
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
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    .sidebar {
      width: 250px;
      background: white;
      padding: 2rem 1rem;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      border-top-right-radius: 20px;
      border-bottom-right-radius: 20px;
    }

    .sidebar h2 {
      color: #4db8b8;
      margin-bottom: 1rem;
      text-align: center;
    }

    .menu-item {
      background-color: #f0fefe;
      padding: 1rem;
      border-radius: 10px;
      text-align: center;
      font-weight: bold;
      color: #257575;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .menu-item:hover {
      background-color: #53c5c5;
      color: white;
      transform: scale(1.03);
    }

    .main-content {
      flex: 1;
      padding: 2rem;
      display: flex;
      flex-direction: column;
    }

    .toolbar {
      background: white;
      padding: 1rem 2rem;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      animation: slideDown 0.8s ease;
    }

    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .toolbar h3 {
      color: #257575;
    }

    .toolbar button {
      padding: 0.6rem 1.2rem;
      background-color: #4db8b8;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .toolbar button:hover {
      background-color: #3da6a6;
    }

    .content-area {
      background: white;
      flex: 1;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      padding: 2rem;
      animation: fadeIn 1s ease-in-out;
    }

    .content-area h4 {
      margin-bottom: 1rem;
      color: #333;
    }

    .logout-animation{
      position: fixed;
      top: 50%;
      left: 50%;
      width: 100px;
      height: 100px;
      background-color: #4db8b8;
      color: white;
      font-weight: bold;
      font-size: 1rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      animation: rollOut 1s ease forwards;
    }

    @keyframes rollOut {
  0% {
    transform: translate(-50%, -50%) rotate(0deg) scale(1);
    opacity: 1;
  }
  100% {
    transform: translate(-50%, -200%) rotate(720deg) scale(0.3);
    opacity: 0;
  }
}
#progressBar {
      margin-top: 1rem;
      height: 20px;
      width: 100%;
      background: #eee;
      border-radius: 10px;
      overflow: hidden;
      display: none;
    }
    #progressBar div {
      height: 100%;
      width: 0%;
      background: #4db8b8;
      transition: width 0.4s ease;
    }

  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <div class="menu-item" onclick="loadContent('relatorio')">📊 Relatório de Atendimentos</div>
    <div class="menu-item" onclick="loadContent('etl')">📤 ETL Upload</div>
  </div>

  <div class="main-content">
    <div class="toolbar">
      <h3>Painel de Controle</h3>
      <button onclick="logout()">Sair</button>
    </div>
    <div class="content-area" id="contentArea">
      <h4>Bem-vindo ao sistema de gestão HealthData</h4>
      <p>Selecione uma das opções no menu lateral para visualizar os dados.</p>
    </div>
  </div>

  <script>
    function logout() {
      const ball = document.createElement('div');
      ball.className = 'logout-animation';
      ball.textContent = 'Saindo...';
      document.body.appendChild(ball);

      setTimeout(() => {
        window.location.href = "index.php";
      }, 1200);
      
    }
    function loadContent(type) {
      const content = document.getElementById('contentArea');

      if (type === 'logs') {
        fetch('backend/get_logs.php')
          .then(res => res.json())
          .then(data => {
            let html = `
              <h4>📄 Lista de logins</h4>
              <table style="width:100%; border-collapse: collapse;">
                <thead>
                  <tr style="background:#f2f2f2;">
                    <th style="padding:8px; border:1px solid #ccc;">ID</th>
                    <th style="padding:8px; border:1px solid #ccc;">Usuário</th>
                    <th style="padding:8px; border:1px solid #ccc;">Data/Hora</th>
                    <th style="padding:8px; border:1px solid #ccc;">IP</th>
                  </tr>
                </thead>
                <tbody>
            `;
            data.forEach(log => {
              html += `
                <tr>
                  <td style="padding:8px; border:1px solid #ccc;">${log.login_id}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${log.user_name}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${log.dt_access}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${log.user_id}</td>
                </tr>
              `;
            });
            html += '</tbody></table>';
            content.innerHTML = html;
          })
          .catch(() => {
            content.innerHTML = `<p style="color:red;">Erro ao carregar logs.</p>`;
          });

      } else if (type === 'login') {
        fetch('backend/get_atv.php')
        .then(res => res.json())
        .then(data => {
            let html = `
            <h4>🔐 Atividade no Sistema</h4>
              <table style="width:100%; border-collapse: collapse;">
                <thead>
                  <tr style="background:#f2f2f2;">
                    <th style="padding:8px; border:1px solid #ccc;">ID</th>
                    <th style="padding:8px; border:1px solid #ccc;">Usuário</th>
                    <th style="padding:8px; border:1px solid #ccc;">Data/Hora</th>
                    <th style="padding:8px; border:1px solid #ccc;">Atividade</th>
                  </tr>
                </thead>
                <tbody>
            `;
            data.forEach(atvs => {
                html += `
                <tr>
                  <td style="padding:8px; border:1px solid #ccc;">${atvs.atv_id}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${atvs.user_name}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${atvs.dt_atv}</td>
                  <td style="padding:8px; border:1px solid #ccc;">${atvs.atv}</td>
                </tr>
                `;
            });
            html += '</tbody></table>';
            content.innerHTML = html;
        })
        .catch(() => {
            content.innerHTML = `<p style="color:red;">Erro ao carregar logs.</p>`;
        });
      }else if (type === 'etl') {
        content.innerHTML = `
          <h4>📤 Envio de Arquivo TXT</h4>
          <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" id="fileInput" name="file" accept=".txt" required style="margin-top: 10px;"/>
            <button type="submit" style="margin-left: 10px; background-color: #4db8b8; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Enviar</button>
          </form>
          <div id="progressBar"><div></div></div>
          <div id="message" style="margin-top: 15px;"></div>
        `;

        document.getElementById('uploadForm').addEventListener('submit', async function(event) {
          event.preventDefault();
          const fileInput = document.getElementById('fileInput');
          const formData = new FormData();
          formData.append('file', fileInput.files[0]);

          const progressBar = document.getElementById('progressBar');
          const progressFill = progressBar.querySelector('div');
          const message = document.getElementById('message');

          progressBar.style.display = 'block';
          let percent = 0;
          const interval = setInterval(() => {
            percent += 5;
            if (percent <= 95) {
              progressFill.style.width = percent + '%';
            }
          }, 300);

          try {
            const response = await fetch('http://127.0.0.1:5000/api/etl', {
              method: 'POST',
              body: formData
            });

            clearInterval(interval);
            progressFill.style.width = '100%';

            const result = await response.json();
            if (response.ok) {
              message.innerHTML = '<span style="color: green; font-weight: bold;">Arquivo enviado com sucesso!</span>';
            } else {
              message.innerHTML = `<span style="color: red; font-weight: bold;">Erro: ${result.error}</span>`;
            }
          } catch (error) {
            clearInterval(interval);
            message.innerHTML = `<span style="color: red; font-weight: bold;">Erro: ${error.message}</span>`;
          }
        });
      }
    }
  </script>
</body>
</html>
