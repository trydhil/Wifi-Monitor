import subprocess
import re

def get_wifi_info():
    """
    Coba ambil info WLAN dulu.
    Returns: (ssid, signal_dbm, interface_type)
    interface_type: 'WLAN' atau 'LAN'
    """
    try:
        output = subprocess.check_output(
            ["netsh", "wlan", "show", "interfaces"],
            encoding="utf-8",
            stderr=subprocess.DEVNULL
        )

        ssid   = None
        signal = None

        for line in output.splitlines():
            line = line.strip()
            if "SSID" in line and "BSSID" not in line:
                parts = line.split(":")
                if len(parts) >= 2:
                    ssid = parts[1].strip()
            elif "Signal" in line:
                parts = line.split(":")
                if len(parts) >= 2:
                    signal_pct = int(re.search(r'\d+', parts[1]).group())
                    signal = -100 + (signal_pct // 2)

        # Kalau SSID berhasil diambil → interface WLAN aktif
        if ssid:
            return ssid, signal, "WLAN"

    except Exception as e:
        print(f"[INFO] WLAN tidak tersedia: {e}")

    # Fallback: cek LAN (Ethernet) lewat ipconfig
    return _get_lan_info()


def _get_lan_info():
    """
    Fallback: deteksi koneksi LAN via ipconfig.
    Returns: (adapter_name, None, 'LAN') atau (None, None, None)
    """
    try:
        output = subprocess.check_output(
            ["ipconfig"],
            encoding="utf-8",
            stderr=subprocess.DEVNULL
        )

        current_adapter = None
        has_ip          = False

        for line in output.splitlines():
            # Baris nama adapter, contoh: "Ethernet adapter Ethernet:"
            adapter_match = re.match(r'^(\S.+):$', line)
            if adapter_match:
                # Flush adapter sebelumnya kalau belum ada IP
                current_adapter = adapter_match.group(1).strip()
                has_ip = False

            # Cek apakah adapter ini punya IPv4
            if "IPv4 Address" in line and current_adapter:
                # Pastikan ini adapter Ethernet/LAN, bukan loopback atau VPN
                name_lower = current_adapter.lower()
                if any(kw in name_lower for kw in ["ethernet", "local area", "lan"]):
                    has_ip = True
                    return current_adapter, None, "LAN"

    except Exception as e:
        print(f"[ERROR] Gagal deteksi LAN: {e}")

    return None, None, None


if __name__ == "__main__":
    ssid_or_adapter, signal, iface = get_wifi_info()
    print(f"Interface : {iface}")
    print(f"SSID/Name : {ssid_or_adapter}")
    print(f"Signal    : {signal} dBm" if signal is not None else "Signal    : N/A (LAN)")
