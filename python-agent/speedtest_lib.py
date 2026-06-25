import time
import speedtest

def run_speedtest(max_retries=2, retry_delay=5):
    """
    Jalankan speedtest menggunakan library speedtest-cli.
    Pakai secure=True (HTTPS) karena banyak jaringan kampus/firewall
    suka blokir atau intercept request HTTP biasa ke endpoint speedtest,
    yang bikin error seperti "Connection reset" atau "403 Forbidden".

    Returns: (download_mbps, upload_mbps, ping_ms) atau None kalau semua percobaan gagal
    """
    last_error = None

    for attempt in range(1, max_retries + 1):
        try:
            st = speedtest.Speedtest(secure=True, timeout=15)
            st.get_best_server()
            download = st.download() / 1_000_000  # bit/s -> Mbps
            upload = st.upload() / 1_000_000
            ping = st.results.ping
            return round(download, 2), round(upload, 2), round(ping, 2)
        except Exception as e:
            last_error = e
            print(f"[ERROR] Speedtest gagal (percobaan {attempt}/{max_retries}): {e}")
            if attempt < max_retries:
                time.sleep(retry_delay)

    print(f"[ERROR] Speedtest gagal setelah {max_retries} percobaan: {last_error}")
    return None

if __name__ == "__main__":
    result = run_speedtest()
    if result:
        download, upload, ping = result
        print(f"Download: {download} Mbps")
        print(f"Upload: {upload} Mbps")
        print(f"Ping: {ping} ms")
    else:
        print("Speedtest gagal")