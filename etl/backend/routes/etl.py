from flask import Blueprint, jsonify, request
import csv
from database.database import pegar_conexao

etl_bp = Blueprint('etl', __name__)

@etl_bp.route('/api/etl', methods=['POST'])
def subir_arquivo():
    if 'file' not in request.files:
        return jsonify({"erro": "Nenhum arquivo enviado"}), 400
    
    file = request.files['file']

    if file.filename == '':
        return jsonify({"error": "Arquivo vazio"}), 400
    
    try:
        file_content = file.stream.read().decode("ISO-8859-1")
        dados = list(csv.reader(file_content.splitlines(), delimiter='\t'))

        conn = pegar_conexao()
        cursor = conn.cursor()

        for cabecalho, row in enumerate(dados):
            if cabecalho == 0 or not row or all(not col.strip() for col in row):
                continue
            
            if "atendimentos" in file.filename:
                row += [None] * (12 - len(row))  # padding

                try:
                    cursor.execute('''
                        INSERT INTO atendimento (dt_atendimento, cliente, servico, valor, profissional, horario)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ''', (row[0], row[1], row[2], row[3], row[4], row[5]))
                except Exception as e:
                    print(f"Erro na linha {cabecalho + 1}: {e}")

        
        conn.commit()
        cursor.close()
        conn.close()

        return jsonify({"message": "Arquivo carregado com sucesso"}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500
                