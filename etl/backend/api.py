from flask import Flask
from flask_cors import CORS
from routes.etl import etl_bp


app = Flask(__name__)

CORS(app, resources={r"/*": {"origins": "*"}})

app.register_blueprint(etl_bp)

if __name__ == '__main__':
    app.run(debug=True)    