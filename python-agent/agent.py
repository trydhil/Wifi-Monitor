import json
import datetime
import argparse
import requests
from network import get_wifi_info
from speedtest_lib import run_speedtest


def calculate_score(download, upload, ping, signal, interface):
    """
    Hitung skor 0-100.
    Untuk LAN: signal diabaikan (bobotnya dialihkan ke ping).
    """
    score_download = min(download / 100 * 100, 100) if download else 0
    score_upload   = min(upload / 50 * 100, 100)    if upload   else 0
    score_ping     = max(0, 100 - (ping * 2))        if ping     else 0

    if interface == "WLAN" and signal is not None:
        score_signal = max(0, 100 - (abs(signal + 100) * 1.5))
        weights = {'download': 0.35, 'upload': 0.25, 'ping': 0.20, 'signal': 0.20}
    else:
        # LAN: tidak ada signal — bobot signal dialihkan ke ping
        score_signal = 0
        weights = {'download': 0.35, 'upload': 0.25, 'ping': 0.40, 'signal': 0.00}

    total = (score_download * weights['download'] +
             score_upload   * weights['upload']   +
             score_ping     * weights['ping']      +
             score_signal   * weights['signal'])

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
    parser = argparse.ArgumentParser()
    parser.add_argument(
        "--save",
        action="store_true",
        help="Kirim & simpan hasil scan ke database Laravel."
    )
    args = parser.parse_args()

    print("[AGENT] Mendeteksi interface jaringan aktif...")

    # Deteksi otomatis WLAN atau LAN
    ssid_or_adapter, signal, interface = get_wifi_info()

    if not interface:
        print("[ERROR] Tidak ada koneksi jaringan aktif (WLAN maupun LAN)!")
        return

    print(f"[AGENT] Interface: {interface} — {ssid_or_adapter}")

    # Untuk LAN, SSID tidak relevan — tampilkan nama adapter
    ssid = ssid_or_adapter if interface == "WLAN" else None

    speedtest_result = run_speedtest()
    if not speedtest_result:
        print("[ERROR] Speedtest gagal total setelah beberapa percobaan.")
        if args.save:
            # JANGAN simpan data palsu (0,0,0) ke database — itu menyesatkan
            # statistik & insight (seolah-olah WiFi buruk, padahal cuma gagal ukur).
            print("[SKIP] Tidak ada data valid untuk disimpan. Scan jam ini dilewati.")
        else:
            print("[INFO] Mode preview — tidak ada data untuk ditampilkan.")
        return

    download, upload, ping = speedtest_result

    score    = calculate_score(download, upload, ping, signal, interface)
    kategori = get_category(score)

    now = datetime.datetime.now()
    result = {
        "tanggal"   : now.strftime("%Y-%m-%d"),
        "jam"       : now.strftime("%H:%M:%S"),
        "interface" : interface,                    # ← baru: 'WLAN' atau 'LAN'
        "ssid"      : ssid,                         # None untuk LAN
        "download"  : download,
        "upload"    : upload,
        "ping"      : ping,
        "signal"    : signal,                       # None untuk LAN
        "score"     : score,
        "kategori"  : kategori
    }

    print(json.dumps(result, ensure_ascii=False))

    if args.save:
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
    else:
        print("[INFO] Mode preview — data tidak disimpan ke database")


if __name__ == "__main__":
    main()
