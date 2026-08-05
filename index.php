<?php
require_once __DIR__ . '/includes/odoo_api.php';

$message_sent = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $empresa  = trim($_POST['empresa'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if (!empty($nombre) && !empty($email)) {
        try {
            $lead_id = odoo(
                'crm.lead',
                'create',
                [[
                    'name'         => 'Contacto Landing Web — ' . ($empresa ?: $nombre),
                    'contact_name' => $nombre,
                    'email_from'   => $email,
                    'phone'        => $telefono,
                    'partner_name' => $empresa,
                    'description'  => $mensaje,
                ]],
                [],
                COMPANY['ITDelivery']
            );
            $message_sent = true;
        } catch (Throwable $e) {
            $error_msg = "No se pudo registrar la consulta en Odoo: " . $e->getMessage();
        }
    } else {
        $error_msg = "Por favor completá los campos obligatorios (Nombre y Email).";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITDelivery — Soluciones Tecnológicas & Software</title>
    <meta name="description" content="Desarrollo de software a medida, integración de sistemas ERP Odoo y soluciones tecnológicas para empresas.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0d1117;
            --card-bg: rgba(22, 27, 34, 0.75);
            --border-color: rgba(48, 54, 61, 0.8);
            --primary: #2f81f7;
            --primary-glow: rgba(47, 129, 247, 0.25);
            --text-main: #f0f6fc;
            --text-muted: #8b949e;
            --accent: #3fb950;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(13, 17, 23, 0.85);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .logo span { color: var(--primary); }
        .hero {
            padding: 5rem 2rem;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #8b949e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
        }
        .contact-section {
            max-width: 600px;
            margin: 0 auto 5rem auto;
            width: 100%;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }
        input, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #161b22;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-main);
            font-size: 1rem;
            font-family: inherit;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        button {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        button:hover {
            background-color: #1f6feb;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-success { background: rgba(63, 185, 80, 0.15); border: 1px solid var(--accent); color: #3fb950; }
        .alert-error { background: rgba(248, 81, 73, 0.15); border: 1px solid #f85149; color: #f85149; }
        footer {
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="/" class="logo">IT<span>Delivery</span></a>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>Soluciones Tecnológicas & Integración ERP</h1>
            <p>Impulsamos tu negocio con desarrollo a medida e integraciones nativas en Odoo Enterprise.</p>
        </section>

        <section class="contact-section">
            <div class="card">
                <h2>Contactanos</h2>

                <?php if ($message_sent): ?>
                    <div class="alert alert-success">
                        ¡Gracias por contactarnos! Tu mensaje fue registrado correctamente en nuestro sistema y nos comunicaremos a la brevedad.
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <input type="text" id="empresa" name="empresa">
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje / Consulta</label>
                        <textarea id="mensaje" name="mensaje" rows="4"></textarea>
                    </div>
                    <button type="submit">Enviar Consulta</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ITDelivery. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
