<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - HealthData Manager</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="assets/style/style.css">
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <div class="menu-item" onclick="loadContent('relatorio')">💈 Relatório Barbearia</div>
    <div class="menu-item" onclick="loadContent('grafico')">💈 Gráfico Barbearia</div>
    <div class="menu-item" onclick="loadContent('etl')">📤 ETL Upload</div>
  </div>
  <div class="main-content">
    <div class="toolbar">
      <h3>Painel de Controle</h3>
      <button onclick="logout()">Sair</button>
    </div>
    <div class="content-area" id="contentArea">
      <h4>Bem-vindo ao sistema da barbearia</h4>
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

      if (type === 'relatorio') {
        fetch('backend/get_atendimentos.php')
          .then(res => res.json())
          .then(data => {
            const servicos = {};
            let tableHtml = `<h4>📊 Relatório de Atendimentos</h4>
              <table><thead>
              <tr>
                <th>ID</th><th>Cliente</th><th>Data</th><th>Serviço</th><th>Valor</th><th>Profissional</th><th>Horário</th>
              </tr></thead><tbody>`;
            data.forEach(row => {
              tableHtml += `
                <tr>
                  <td>${row.atendimento_id}</td>
                  <td>${row.cliente}</td>
                  <td>${row.dt_atendimento}</td>
                  <td>${row.servico}</td>
                  <td>R$ ${parseFloat(row.valor).toFixed(2).replace('.', ',')}</td>
                  <td>${row.profissional}</td>
                  <td>${row.horario}</td>
                </tr>`;
              servicos[row.servico] = (servicos[row.servico] || 0) + 1;
            });
            tableHtml += '</tbody></table>';

            const labels = Object.keys(servicos);
            const valores = Object.values(servicos);

            content.innerHTML = tableHtml + `
              <h4 style="margin-top:30px;">📈 Gráfico de Serviços</h4>
              <canvas id="graficoServicos" height="120"></canvas>`;

            const ctx = document.getElementById('graficoServicos').getContext('2d');
            new Chart(ctx, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Quantidade de Atendimentos',
                  data: valores,
                  backgroundColor: '#4db8b8'
                }]
              },
              options: {
                responsive: true,
                plugins: { legend: { display: false } }
              }
            });
          })
          .catch(() => {
            content.innerHTML = '<p style="color:red;">Erro ao carregar relatório.</p>';
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
      }else if (type === 'grafico') {
        fetch('backend/get_atendimentos.php')
          .then(res => res.json())
          .then(data => {
            const servicoMap = {};
            data.forEach(item => {
              servicoMap[item.servico] = (servicoMap[item.servico] || 0) + 1;
            });

            const labels = Object.keys(servicoMap);
            const values = Object.values(servicoMap);

            content.innerHTML = `
              <h4>📊 Serviços Mais Utilizados</h4>
              <canvas id="graficoServicos" width="400" height="200"></canvas>
              <br>
              <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #3da6a6; color: white;">
                  <tr>
                    <th style="padding: 10px; border: 1px solid #ccc;">Serviço</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Quantidade</th>
                  </tr>
                </thead>
                <tbody>
                  ${labels.map((label, i) => `
                    <tr>
                      <td style="padding: 10px; border: 1px solid #ccc;">${label}</td>
                      <td style="padding: 10px; border: 1px solid #ccc;">${values[i]}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            `;

            new Chart(document.getElementById('graficoServicos'), {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Atendimentos por Serviço',
                  data: values,
                  backgroundColor: '#4db8b8'
                }]
              },
              options: {
                responsive: true,
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          })
          .catch(() => {
            content.innerHTML = '<p style="color:red;">Erro ao carregar os dados do gráfico.</p>';
          });
      }
    }
  </script>
</body>
</html>
