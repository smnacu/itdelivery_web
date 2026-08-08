<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

require_once __DIR__ . '/includes/odoo_api.php';

$message_sent = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. HONEYPOT TRAP (Campo tramposo para bots)
    $honeypot = trim($_POST['b_hp_email_verify'] ?? '');
    if (!empty($honeypot)) {
        // Simular respuesta exitosa para engatusar al bot sin llamar a Odoo
        $message_sent = true;
    } else {
        // 2. TIME TRAP (Detección de envíos automáticos ultra rápidos < 2 seg)
        $form_time = (int)($_POST['b_form_time'] ?? 0);
        $time_spent = time() - $form_time;
        
        // 3. RATE LIMITING EN SESIÓN (Máximo 3 envíos cada 5 minutos)
        $now = time();
        $_SESSION['last_submits'] = array_filter(
            $_SESSION['last_submits'] ?? [],
            fn($t) => ($now - $t) < 300
        );

        if ($form_time > 0 && $time_spent < 2) {
            $error_msg = "El envío fue demasiado rápido. Por favor intenta de nuevo.";
        } elseif (count($_SESSION['last_submits']) >= 3) {
            $error_msg = "Has alcanzado el límite de consultas permitidas. Por favor aguardá 5 minutos.";
        } else {
            // 4. SANITIZACIÓN & VALIDACIÓN ESTRICTA
            $nombre   = htmlspecialchars(strip_tags(trim($_POST['nombre'] ?? '')), ENT_QUOTES, 'UTF-8');
            $raw_email = trim($_POST['email'] ?? '');
            $email    = filter_var($raw_email, FILTER_VALIDATE_EMAIL);
            $telefono = htmlspecialchars(strip_tags(trim($_POST['telefono'] ?? '')), ENT_QUOTES, 'UTF-8');
            $empresa  = htmlspecialchars(strip_tags(trim($_POST['empresa'] ?? '')), ENT_QUOTES, 'UTF-8');
            $servicio = htmlspecialchars(strip_tags(trim($_POST['servicio_interes'] ?? '')), ENT_QUOTES, 'UTF-8');
            $mensaje  = htmlspecialchars(strip_tags(trim($_POST['mensaje'] ?? '')), ENT_QUOTES, 'UTF-8');

            // Limitar longitud máxima de campos para mitigar abusos de payload
            $nombre   = mb_substr($nombre, 0, 100);
            $telefono = mb_substr($telefono, 0, 30);
            $empresa  = mb_substr($empresa, 0, 100);
            $servicio = mb_substr($servicio, 0, 100);
            $mensaje  = mb_substr($mensaje, 0, 2000);

            $full_description = $mensaje;
            if (!empty($servicio)) {
                $full_description = "Servicio de interés: " . $servicio . "\n\n" . $mensaje;
            }

            if (!empty($nombre) && $email !== false) {
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
                    $_SESSION['last_submits'][] = time();
                    $message_sent = true;
                } catch (Throwable $e) {
                    $error_msg = "No se pudo registrar la consulta en Odoo: " . $e->getMessage();
                }
            } else {
                $error_msg = "Por favor ingresá un nombre válido y un correo electrónico con formato correcto.";
            }
        }
    }
}

// Cargar catálogo en tiempo real desde Odoo 19 para la empresa matriz ITDelivery
$catalogo = odoo_get_catalog(COMPANY['ITDelivery'], 12);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITDelivery — Consultoría IT, Odoo 19 Enterprise & IA</title>
    <meta name="description" content="Especialistas en ERP Odoo 19 Enterprise, Arquitectura Cloud, Agentes de IA y Desarrollo de Software a Medida.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #090d16;
            --card-bg: rgba(22, 27, 38, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --card-hover-border: rgba(47, 129, 247, 0.5);
            --primary: #2f81f7;
            --primary-glow: rgba(47, 129, 247, 0.25);
            --accent: #3fb950;
            --accent-glow: rgba(63, 185, 80, 0.2);
            --purple: #a371f7;
            --text-main: #f0f6fc;
            --text-muted: #9198a1;
            --text-sub: #c9d1d9;
            --header-bg: rgba(9, 13, 22, 0.85);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Ambient Glow Effect */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 100vh;
            z-index: -1;
            pointer-events: none;
            background: 
                radial-gradient(circle at 20% 20%, rgba(47, 129, 247, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 80% 60%, rgba(163, 113, 247, 0.1) 0%, transparent 40%);
        }

        /* Header Navigation */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--header-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .logo span {
            color: var(--primary);
        }

        .logo-badge {
            font-size: 0.65rem;
            background: rgba(47, 129, 247, 0.15);
            color: var(--primary);
            border: 1px solid rgba(47, 129, 247, 0.3);
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: 700;
            margin-left: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--text-main);
        }

        .btn-cta-nav {
            background: var(--primary);
            color: #ffffff !important;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 600 !important;
            box-shadow: 0 0 15px var(--primary-glow);
            transition: all 0.2s ease !important;
        }

        .btn-cta-nav:hover {
            background: #1f6feb;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            max-width: 1000px;
            margin: 0 auto;
            padding: 6rem 2rem 4rem 2rem;
            text-align: center;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-bottom: 2rem;
        }

        .hero-pill .dot {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent);
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 30%, #8b949e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 760px;
            margin: 0 auto 2.5rem auto;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            padding: 0.85rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px var(--primary-glow);
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1f6feb;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid var(--card-border);
            padding: 0.85rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Section Container */
        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
            width: 100%;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-subtitle {
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        /* Pillars Grid */
        .pillars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .pillar-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 2rem;
            transition: all 0.25 ease;
            display: flex;
            flex-direction: column;
        }

        .pillar-card:hover {
            transform: translateY(-4px);
            border-color: var(--card-hover-border);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        .pillar-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(47, 129, 247, 0.1);
            border: 1px solid rgba(47, 129, 247, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
        }

        .pillar-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .pillar-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Odoo Catalog Section */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .product-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .product-category {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--primary);
            background: rgba(47, 129, 247, 0.1);
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .product-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--card-border);
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--accent);
        }

        .btn-order-service {
            background: rgba(47, 129, 247, 0.15);
            color: var(--primary);
            border: 1px solid rgba(47, 129, 247, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-order-service:hover {
            background: var(--primary);
            color: #ffffff;
        }

        /* Contact Form */
        .contact-container {
            max-width: 700px;
            margin: 0 auto;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-group-full {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-sub);
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(13, 17, 23, 0.9);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-success {
            background: rgba(63, 185, 80, 0.12);
            border: 1px solid var(--accent);
            color: #3fb950;
        }

        .alert-error {
            background: rgba(248, 81, 73, 0.12);
            border: 1px solid #f85149;
            color: #f85149;
        }

        /* Footer */
        footer {
            margin-top: auto;
            border-top: 1px solid var(--card-border);
            padding: 2.5rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
            background: rgba(9, 13, 22, 0.9);
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.4rem; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group-full { grid-column: span 1; }
            .nav-links { display: none; }
            .contact-container { padding: 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="ambient-bg"></div>

    <header>
        <div class="nav-container">
            <a href="/" class="logo">
                IT<span>Delivery</span>
                <span class="logo-badge">Odoo 19 ERP</span>
            </a>
            <nav class="nav-links">
                <a href="#servicios">Servicios</a>
                <a href="#catalogo">Catálogo Odoo</a>
                <a href="#contacto" class="btn-cta-nav">Contacto</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-pill">
                <span class="dot"></span>
                <span>Arquitectura Cloud, ERP & Agentes de IA</span>
            </div>
            <h1>Transformación Digital & Soluciones ERP a Medida</h1>
            <p>Diseñamos e implementamos arquitecturas tecnológicas de alto rendimiento. Integración nativa con Odoo 19 Enterprise, infraestructura resiliente y agentes inteligentes para optimizar la escala operativa de tu empresa.</p>
            <div class="hero-actions">
                <a href="#contacto" class="btn-primary">Solicitar Asesoramiento</a>
                <a href="#servicios" class="btn-secondary">Explorar Pilares</a>
            </div>
        </section>

        <!-- Pilares de Servicio -->
        <section id="servicios" class="section">
            <div class="section-header">
                <div class="section-subtitle">Nuestra Propuesta de Valor</div>
                <h2 class="section-title">Pilares Tecnológicos de ITDelivery</h2>
            </div>
            <div class="pillars-grid">
                <div class="pillar-card">
                    <div class="pillar-icon">⚡</div>
                    <h3 class="pillar-title">Odoo 19 Enterprise</h3>
                    <p class="pillar-desc">Implementación integral del ERP matriz, desarrollo de conectores personalizados JSON-RPC/REST, localización argentina y soporte multi-company.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">🤖</div>
                    <h3 class="pillar-title">IA & Agentes Autónomos</h3>
                    <p class="pillar-desc">Integración de modelos LLM, protocolo MCP (Model Context Protocol) y automatizaciones inteligentes adaptadas a los flujos operativos de la empresa.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">☁️</div>
                    <h3 class="pillar-title">Arquitectura Cloud & DevOps</h3>
                    <p class="pillar-desc">Configuración de Cloudflare Tunnels, seguridad perimetral SSL, hosting de alta disponibilidad en Ferozo/Odoo.sh y pipelines de CI/CD.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">💻</div>
                    <h3 class="pillar-title">Software Engineering</h3>
                    <p class="pillar-desc">Desarrollo de aplicaciones web y mobile escalables (PHP, Node.js, Flutter, React) orientadas a la optimización de procesos de negocio.</p>
                </div>
            </div>
        </section>

        <!-- Catálogo de Servicios Odoo -->
        <section id="catalogo" class="section">
            <div class="section-header">
                <div class="section-subtitle">Catálogo en Tiempo Real</div>
                <h2 class="section-title">Servicios & Soluciones Sincronizadas</h2>
            </div>

            <?php if (empty($catalogo)): ?>
                <p style="text-align:center; color:var(--text-muted); padding: 2rem;">
                    Sincronizando catálogo con el servidor de Odoo 19...
                </p>
            <?php else: ?>
                <div class="catalog-grid">
                    <?php foreach ($catalogo as $prod): ?>
                        <div class="product-card">
                            <div>
                                <span class="product-category">
                                    <?= htmlspecialchars($prod['categ_id'][1] ?? 'Servicios IT') ?>
                                </span>
                                <h3 class="product-name"><?= htmlspecialchars($prod['name']) ?></h3>
                                <p class="product-description">
                                    <?= htmlspecialchars(trim($prod['description_sale'] ?? 'Solución tecnológica integral con soporte y acompañamiento especializado.')) ?>
                                </p>
                            </div>
                            <div class="product-footer">
                                <span class="product-price">
                                    $<?= number_format($prod['list_price'], 2, ',', '.') ?>
                                </span>
                                <button type="button" class="btn-order-service" onclick="solicitarServicio('<?= htmlspecialchars(addslashes($prod['name'])) ?>')">
                                    Solicitar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Formulario de Contacto / Odoo CRM -->
        <section id="contacto" class="section">
            <div class="section-header">
                <div class="section-subtitle">Contacto Directo</div>
                <h2 class="section-title">Iniciá tu Proyecto con ITDelivery</h2>
            </div>
            
            <div class="contact-container">
                <?php if ($message_sent): ?>
                    <div class="alert alert-success">
                        ¡Gracias por contactarnos! Tu consulta ha sido registrada exitosamente en nuestro ERP Odoo CRM. Un especialista se comunicará a la brevedad.
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php#contacto" method="POST">
                    <!-- Honeypot invisible anti-bot -->
                    <div style="display:none !important;" aria-hidden="true">
                        <label for="b_hp_email_verify">No llenar este campo:</label>
                        <input type="text" id="b_hp_email_verify" name="b_hp_email_verify" tabindex="-1" autocomplete="off">
                    </div>
                    <!-- Time-trap stamp -->
                    <input type="hidden" name="b_form_time" value="<?= time() ?>">

                    <div class="form-grid">
                        <div class="form-group form-group-full">
                            <label for="servicio_interes">Servicio de Interés</label>
                            <input type="text" class="form-control" id="servicio_interes" name="servicio_interes" placeholder="Ej. Implementación Odoo 19, Consultoría IA, Cloud Infrastructure">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre Completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Tu nombre">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico *</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="ejemplo@empresa.com">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="+54 11 ...">
                        </div>
                        <div class="form-group">
                            <label for="empresa">Empresa / Organización</label>
                            <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Nombre de tu empresa">
                        </div>
                        <div class="form-group form-group-full">
                            <label for="mensaje">Detalle de la Consulta</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Contanos sobre tu proyecto o requerimiento tecnológico..."></textarea>
                        </div>
                        <div class="form-group form-group-full" style="margin-top: 1rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; text-align: center;">
                                Enviar Consulta a Odoo CRM
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ITDelivery. Firma de Arquitectura IT & Consultoría ERP Conectada a Odoo 19 Enterprise.</p>
    </footer>

    <script>
        function solicitarServicio(nombreServicio) {
            document.getElementById('servicio_interes').value = nombreServicio;
            document.getElementById('contacto').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
    <?php require_once __DIR__ . '/includes/floating_bot.php'; ?>
</body>
</html>
