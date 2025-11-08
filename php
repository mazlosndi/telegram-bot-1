from flask import Flask, request
import requests
import os

app = Flask(__name__)

# Replace with your bot token and chat ID
BOT_TOKEN = '7789208063:AAGJnbnn6qKkqhifWpQ_slUlrXahwAvxkx0'
CHAT_ID = '1660407337'

@app.route('/send', methods=['POST'])
def send():
    if 'image' not in request.files:
        return 'No image', 400

    file = request.files['image']
    url = f'https://api.telegram.org/bot{BOT_TOKEN}/sendPhoto'
    response = requests.post(url, data={'chat_id': CHAT_ID}, files={'photo': file})

    if response.ok:
        return 'OK', 200
    else:
        return 'Failed', 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=int(os.environ.get('PORT', 5000)))
