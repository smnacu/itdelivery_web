<?php
session_start();

// Auto-login Daniela con acceso vitalicio por defecto
if (isset($_GET['login_dani'])) {
    $_SESSION['user_email'] = 'daniela@estudio-pagani.com.ar';
    $_SESSION['user_name'] = 'Daniela Pagani';
    $_SESSION['user_role'] = 'Titular / Acceso Vitalicio FULL';
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['user_email'] = $_POST['email'] ?? 'daniela@estudio-pagani.com.ar';
    $_SESSION['user_name'] = 'Daniela Pagani';
    $_SESSION['user_role'] = 'Titular / Acceso Vitalicio FULL';
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudio Pagani - Portal de Actualidad Paritaria & Retenciones</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0f172a;
            --navy-brand: #1b365d;
            --primary: #38bdf8;
            --accent: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --card-bg: rgba(30, 41, 59, 0.85);
            --border: rgba(255, 255, 255, 0.12);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-dark); color: var(--text-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-card { width: 100%; max-width: 440px; background: var(--card-bg); backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: 16px; padding: 40px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .brand-header { margin-bottom: 24px; }
        .brand-title { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 6px; }
        .brand-subtitle { font-size: 13px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .badge-lifetime { display: inline-flex; align-items: center; gap: 6px; background: rgba(245, 158, 11, 0.15); color: var(--accent); border: 1px solid rgba(245, 158, 11, 0.3); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 24px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 15px; outline: none; transition: all 0.3s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .btn-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); border: none; border-radius: 8px; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4); }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(2, 132, 199, 0.6); }
        .btn-direct { display: block; margin-top: 16px; color: var(--primary); font-size: 14px; text-decoration: none; font-weight: 600; }
        .btn-direct:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-header">
            <h1 class="brand-title">ESTUDIO PAGANI</h1>
            <div class="brand-subtitle">Asesoría Contable &bull; Impositiva &bull; Laboral</div>
        </div>

        <div class="badge-lifetime">
            ⭐ LICENCIA DANIELA PAGANI: ACCESO VITALICIO FULL
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="daniela@estudio-pagani.com.ar" required>
            </div>
            <div class="form-group">
                <label>Contraseña de Acceso</label>
                <input type="password" name="password" class="form-control" value="••••••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Ingresar al Portal de Actualidad</button>
        </form>

        <a href="index.php?login_dani=1" class="btn-direct">⚡ Acceso Directo de 1-Clic para Daniela &rarr;</a>
    </div>
</body>
</html>
