#!/usr/bin/env python3
"""
ITDelivery Web — Script de despliegue automatizado por FTPS (TLS)
Servidor: c2031975.ferozo.com
"""

import os
import ssl
from ftplib import FTP_TLS

FTP_HOST = "c2031975.ferozo.com"
FTP_USER = "antigravity@itdelivery.com.ar"
FTP_PASS = "rdU@/Au@q9tq2gB"
LOCAL_DIR = "/home/santinacu/proyectos/itdelivery_web"
REMOTE_TARGET = "/" # Directorio raíz public_html de ITDelivery

EXCLUDE_DIRS = {'.git', '.github', 'storage', '__pycache__'}
EXCLUDE_FILES = {'.gitignore', 'deploy_itdelivery.py', '.env'}

def deploy():
    print(f"🚀 Conectando por FTPS a {FTP_HOST}...")
    
    context = ssl.create_default_context()
    context.check_hostname = False
    context.verify_mode = ssl.CERT_NONE

    ftps = FTP_TLS(context=context)
    ftps.connect(FTP_HOST, 21, timeout=30)
    ftps.login(FTP_USER, FTP_PASS)
    ftps.prot_p() # Encriptar transferencia de datos
    print("✅ Conexión FTPS autenticada y segura.")

    uploaded_count = 0

    for root, dirs, files in os.walk(LOCAL_DIR):
        # Excluir carpetas no deseadas
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]

        rel_path = os.path.relpath(root, LOCAL_DIR)
        remote_dir = REMOTE_TARGET if rel_path == "." else os.path.join(REMOTE_TARGET, rel_path).replace("\\", "/")

        # Asegurar directorio remoto
        if remote_dir != "/":
            try:
                ftps.cwd(remote_dir)
            except Exception:
                print(f"📁 Creando directorio remoto: {remote_dir}")
                ftps.mkd(remote_dir)
                ftps.cwd(remote_dir)
        else:
            ftps.cwd("/")

        for f in files:
            if f in EXCLUDE_FILES or f.startswith('.'):
                continue

            local_file = os.path.join(root, f)
            print(f"⬆️  Subiendo: {os.path.relpath(local_file, LOCAL_DIR)} -> {remote_dir}/{f}")

            with open(local_file, 'rb') as fp:
                ftps.storbinary(f"STOR {f}", fp)
                uploaded_count += 1

    ftps.quit()
    print(f"🎉 Despliegue FTPS finalizado con éxito. {uploaded_count} archivos actualizados en el servidor.")

if __name__ == "__main__":
    deploy()
