<?php
require_once __DIR__ . '/includes/odoo_api.php';

$message_sent = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $empresa  = trim($_POST['empresa'] ?? '');
    $servicio = trim($_POST['servicio_interes'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    $full_description = $mensaje;
    if (!empty($servicio)) {
        $full_description = "Servicio solicitado: " . $servicio . "\n\n" . $mensaje;
    }

    if (!empty($nombre) && !empty($email)) {
        try {
            $lead_id = odoo(
                'crm.lead',
                'create',
                [[
                    'name'         => 'Consulta Web' . ($servicio ? " [$servicio]" : "") . ' — ' . ($empresa ?: $nombre),
                    'contact_name' => $nombre,
                    'email_from'   => $email,
                    'phone'        => $telefono,
                    'partner_name' => $empresa,
                    'description'  => $full_description,
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

// Cargar catálogo en tiempo real desde Odoo 19
$catalogo = odoo_get_catalog(COMPANY['ITDelivery'], 20);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITDelivery — Soluciones Tecnológicas & Catálogo Odoo</title>
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
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--text-main); }
        .hero {
            padding: 4rem 2rem 2rem 2rem;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 2.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #8b949e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
        .section-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .catalog-container {
            max-width: 1200px;
            margin: 0 auto 4rem auto;
            padding: 0 1.5rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
        }
        .product-badge {
            align-self: flex-start;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            background: var(--primary-glow);
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .product-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .product-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
            padding-top: 1rem;
        }
        .product-price {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--accent);
        }
        .btn-order {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-order:hover {
            background: #1f6feb;
        }
        .contact-section {
            max-width: 650px;
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
        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #161b22;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-main);
            font-size: 1rem;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        button[type="submit"] {
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
        button[type="submit"]:hover { background-color: #1f6feb; }
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
            <div class="nav-links">
                <a href="#catalogo">Catálogo</a>
                <a href="#contacto">Contacto</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>Catálogo de Servicios & Integración Odoo</h1>
            <p>Explorá nuestras ofertas y contratá soluciones directamente sincronizadas con nuestro ERP.</p>
        </section>

        <!-- Seccion Catalogo -->
        <section id="catalogo" class="catalog-container">
            <h2 class="section-title">Catálogo Sincronizado desde Odoo 19</h2>

            <?php if (empty($catalogo)): ?>
                <p style="text-align:center; color:var(--text-muted);">No se pudieron cargar productos del catálogo en este momento.</p>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($catalogo as $prod): ?>
                        <div class="product-card">
                            <div>
                                <span class="product-badge">
                                    <?= htmlspecialchars($prod['categ_id'][1] ?? 'Servicios') ?>
                                </span>
                                <h3 class="product-title"><?= htmlspecialchars($prod['name']) ?></h3>
                                <p class="product-desc">
                                    <?= htmlspecialchars(trim($prod['description_sale'] ?? 'Solución tecnológica profesional con integración directa.')) ?>
                                </p>
                            </div>
                            <div class="product-footer">
                                <span class="product-price">
                                    $<?= number_format($prod['list_price'], 2, ',', '.') ?>
                                </span>
                                <button type="button" class="btn-order" onclick="solicitarServicio('<?= htmlspecialchars(addslashes($prod['name'])) ?>')">
                                    Solicitar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Seccion Contacto / Solicitar -->
        <section id="contacto" class="contact-section">
            <div class="card">
                <h2 style="text-align:center; margin-bottom:1.5rem;">Formulario de Solicitud</h2>

                <?php if ($message_sent): ?>
                    <div class="alert alert-success">
                        ¡Consulta recibida! Se ha generado la oportunidad en Odoo CRM y nos comunicaremos con vos a la brevedad.
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php#contacto" method="POST">
                    <div class="form-group">
                        <label for="servicio_interes">Servicio de Interés</label>
                        <input type="text" id="servicio_interes" name="servicio_interes" placeholder="Seleccioná un servicio o escribí tu consulta">
                    </div>
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
                        <label for="mensaje">Mensaje / Detalle de la Solicitud</label>
                        <textarea id="mensaje" name="mensaje" rows="4"></textarea>
                    </div>
                    <button type="submit">Enviar Oportunidad a Odoo</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ITDelivery. Catálogo conectado a Odoo 19 Enterprise.</p>
    </footer>

    <script>
        function solicitarServicio(nombreServicio) {
            document.getElementById('servicio_interes').value = nombreServicio;
            document.getElementById('contacto').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
