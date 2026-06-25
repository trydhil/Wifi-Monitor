@echo off
REM ====== KONFIGURASI: ganti baris di bawah sesuai lokasi python.exe di laptop ini ======
set PYTHON_EXE=C:\Users\ASUS\AppData\Local\Programs\Python\Python314\python.exe

cd /d "%~dp0"
"%PYTHON_EXE%" agent.py --save >> agent_log.txt 2>&1