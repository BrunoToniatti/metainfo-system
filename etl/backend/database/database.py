import pyodbc

def pegar_conexao(database='barbearia'):
    conn = pyodbc.connect('DRIVER={ODBC Driver 17 for SQL Server};'
                          'SERVER=LAPTOP-ELQUK5G2;'
                          f'DATABASE={database};'
                          'Trusted_Connection=yes;')
    return conn