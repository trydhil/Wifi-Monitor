import speedtest

def run_speedtest():
    """
    Jalankan speedtest menggunakan library speedtest-cli
    Returns: (download_mbps, upload_mbps, ping_ms) atau None
    """
    try:
        st = speedtest.Speedtest()
        st.get_best_server()
        download = st.download() / 1_000_000  # bit/s -> Mbps
        upload = st.upload() / 1_000_000
        ping = st.results.ping
        return round(download, 2), round(upload, 2), round(ping, 2)
    except Exception as e:
        print(f"[ERROR] Speedtest gagal: {e}")
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