import subprocess
import re

def get_wifi_info():
    """
    Ambil SSID dan Signal dari Windows menggunakan netsh wlan show interfaces
    Returns: (ssid, signal_dbm) atau (None, None) jika gagal
    """
    try:
        output = subprocess.check_output(
            ["netsh", "wlan", "show", "interfaces"],
            encoding="utf-8",
            stderr=subprocess.DEVNULL
        )
        
        ssid = None
        signal = None
        
        for line in output.splitlines():
            line = line.strip()
            if "SSID" in line and "BSSID" not in line:
                # Contoh: "SSID                : WiFi Kantor"
                parts = line.split(":")
                if len(parts) >= 2:
                    ssid = parts[1].strip()
            elif "Signal" in line:
                # Contoh: "Signal              : 75%"
                parts = line.split(":")
                if len(parts) >= 2:
                    # Ambil angka, ubah persen ke dBm (kira-kira)
                    signal_pct = int(re.search(r'\d+', parts[1]).group())
                    # Konversi persen ke dBm (rumus aproksimasi: -100 + (persen/2))
                    signal_dbm = -100 + (signal_pct // 2)
                    signal = signal_dbm
        
        return ssid, signal
    
    except Exception as e:
        print(f"[ERROR] Gagal ambil WiFi info: {e}")
        return None, None

if __name__ == "__main__":
    ssid, signal = get_wifi_info()
    print(f"SSID: {ssid}")
    print(f"Signal: {signal} dBm")