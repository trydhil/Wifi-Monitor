import json
import datetime
import requests  # <-- tambahkan ini
from network import get_wifi_info
from speedtest_lib import run_speedtest
def calculate_score(download, upload, ping, signal):
    """
    Hitung skor 0-100 berdasarkan parameter:
    - download: 0-100 Mbps (skor maks di 100 Mbps)
    - upload: 0-100 Mbps (skor maks di 50 Mbps)
    - ping: 0-100 ms (skor maks di 5 ms)
    - signal: -100 s/d -30 dBm (semakin mendekati 0 semakin baik)
    """
    score_download = min(download / 100 * 100, 100) if download else 0
    score_upload = min(upload / 50 * 100, 100) if upload else 0
    score_ping = max(0, 100 - (ping * 2)) if ping else 0
    score_signal = max(0, 100 - (abs(signal + 100) * 1.5)) if signal is not None else 0
    
    weights = {
        'download': 0.35,
        'upload': 0.25,
        'ping': 0.20,
        'signal': 0.20
    }
    
    total = (score_download * weights['download'] +
             score_upload * weights['upload'] +
             score_ping * weights['ping'] +
             score_signal * weights['signal'])
    
    return round(total)

def get_category(score):
    if score >= 90:
        return "Sangat Baik"
    elif score >= 75:
        return "Baik"
    elif score >= 60:
        return "Cukup"
    else:
        return "Buruk"

def main():
    print("[AGENT] Mengambil data WiFi...")
    
    ssid, signal = get_wifi_info()
    if not ssid:
        print("[ERROR] Tidak terhubung ke WiFi!")
        return
    
    speedtest_result = run_speedtest()
    if not speedtest_result:
        print("[ERROR] Speedtest gagal, pakai data parsial")
        download, upload, ping = 0, 0, 0
    else:
        download, upload, ping = speedtest_result
    
    score = calculate_score(download, upload, ping, signal)
    kategori = get_category(score)
    
    now = datetime.datetime.now()
    result = {
        "tanggal": now.strftime("%Y-%m-%d"),
        "jam": now.strftime("%H:%M:%S"),
        "ssid": ssid,
        "download": download,
        "upload": upload,
        "ping": ping,
        "signal": signal,
        "score": score,
        "kategori": kategori
    }
    
    # Cetak JSON ke layar (untuk debugging)
    print(json.dumps(result, ensure_ascii=False))
    
    # Kirim ke Laravel API
    try:
        response = requests.post(
            "http://localhost:8000/api/scan",
            json=result,
            timeout=10
        )
        if response.status_code == 201:
            print("[OK] Data berhasil dikirim ke server")
        else:
            print(f"[GAGAL] Server merespon: {response.status_code} - {response.text}")
    except Exception as e:
        print(f"[ERROR] Gagal mengirim ke server: {e}")

if __name__ == "__main__":
    main()