<?php
require_once __DIR__ . '/../includes/odoo_api.php';

// Verificación de acceso seguro al panel de administración / groomer interno
$is_admin = isset($_GET['admin']) || isset($_COOKIE['almitas_admin']);
if (isset($_GET['admin']) && $_GET['admin'] === '1') {
    setcookie('almitas_admin', '1', time() + 86400 * 30, '/');
    $is_admin = true;
} elseif (isset($_GET['logout'])) {
    setcookie('almitas_admin', '', time() - 3600, '/');
    $is_admin = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almitas Peludas 🐾 | Grooming & Cat Sitting a Domicilio CABA</title>
    <meta name="description" content="Servicio amoroso y profesional de peluquería canina, grooming felino y Cat Sitting por Rossmari a domicilio en CABA (Palermo, Belgrano, Recoleta, Caballito). Venta de alimentos CatPro, Pro Plan y piedritas Rubicat.">
    <meta name="keywords" content="peluqueria canina a domicilio capital federal, cat sitting caba rossmari, grooming felino sin estres, almitas peludas, piedritas rubicat caba">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://almitas.itdelivery.com.ar/">
    <meta name="theme-color" content="#0f172a">

    <!-- Open Graph / Social SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Almitas Peludas 🐾 | Cuidado Amoroso & Grooming a Domicilio">
    <meta property="og:description" content="Atención sin estrés en la comodidad de tu hogar. Peluquería canina, felina y Cat Sitting profesional por Rossmari en CABA.">
    <meta property="og:url" content="https://almitas.itdelivery.com.ar/">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0f172a;
            --bg-alt: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.75);
            --card-border: rgba(251, 191, 36, 0.25);
            --border-muted: rgba(255, 255, 255, 0.1);
            
            --gold: #fbbf24;
            --gold-hover: #f59e0b;
            --gold-glow: rgba(251, 191, 36, 0.22);
            
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.2);
            
            --text: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            --shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.45);
            --radius-lg: 24px;
            --radius-md: 14px;
            --transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Ambient Glow background */
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none; z-index: 0;
            background: 
                radial-gradient(circle at 15% 15%, rgba(251, 191, 36, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(16, 185, 129, 0.07) 0%, transparent 45%);
        }

        /* Top Header */
        header {
            position: sticky; top: 0; z-index: 100;
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            background: rgba(15, 23, 42, 0.88);
            border-bottom: 1px solid var(--border-muted);
        }

        .header-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 1.1rem 1.5rem;
            display: flex; justify-content: space-between; align-items: center;
        }

        .brand {
            display: flex; align-items: center; gap: 0.75rem; text-decoration: none;
        }

        .brand-icon {
            font-size: 1.6rem;
            filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.4));
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800; font-size: 1.5rem;
            color: var(--text); letter-spacing: -0.02em;
        }

        .brand-name span { color: var(--gold); }

        nav { display: flex; gap: 1.5rem; align-items: center; }

        nav a {
            color: var(--text-secondary); text-decoration: none;
            font-size: 0.95rem; font-weight: 500; transition: var(--transition);
        }

        nav a:hover { color: var(--gold); }

        .btn-nav {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            color: #0f172a !important; padding: 0.6rem 1.3rem;
            border-radius: var(--radius-md); font-weight: 700 !important;
            box-shadow: 0 4px 16px var(--gold-glow);
        }

        .btn-nav:hover { transform: translateY(-2px); box-shadow: 0 6px 22px var(--gold-glow); }

        @media (max-width: 768px) { nav { display: none; } }

        /* Main Wrapper */
        main {
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto;
            padding: 2.5rem 1.5rem 6rem 1.5rem;
            display: flex; flex-direction: column; gap: 3.5rem;
        }

        /* Hero Section */
        .hero {
            text-align: center; max-width: 860px; margin: 0 auto;
            padding: 1.5rem 0 0.5rem 0;
        }

        .hero-pill {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.35);
            color: var(--gold); padding: 0.4rem 1.1rem; border-radius: 50px;
            font-size: 0.88rem; font-weight: 700; margin-bottom: 1.25rem;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem; font-weight: 900; line-height: 1.15;
            margin-bottom: 1.2rem;
            background: linear-gradient(135deg, #ffffff 40%, var(--gold) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.18rem; color: var(--text-secondary);
            max-width: 720px; margin: 0 auto 2rem auto; line-height: 1.6;
        }

        .hero-cta-group {
            display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;
        }

        .btn-hero {
            padding: 0.95rem 1.8rem; border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.05rem;
            text-decoration: none; cursor: pointer; transition: var(--transition);
            display: inline-flex; align-items: center; gap: 0.6rem;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            color: #0f172a; box-shadow: 0 6px 25px var(--gold-glow);
        }

        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px var(--gold-glow); }

        .btn-hero-secondary {
            background: rgba(16, 185, 129, 0.15); border: 1px solid var(--emerald);
            color: #34d399; box-shadow: 0 4px 20px var(--emerald-glow);
        }

        .btn-hero-secondary:hover { transform: translateY(-3px); background: rgba(16, 185, 129, 0.25); }

        @media (max-width: 600px) {
            .hero h1 { font-size: 2.1rem; }
            .hero p { font-size: 1rem; }
            .btn-hero { width: 100%; justify-content: center; }
        }

        /* Services Grid Cards */
        .services-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.75rem;
        }

        .service-card {
            background: var(--card-bg); border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg); padding: 2rem;
            box-shadow: var(--shadow); transition: var(--transition);
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
        }

        .service-card:hover {
            border-color: var(--card-border); transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);
        }

        .service-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--emerald) 100%);
        }

        .service-header { margin-bottom: 1.25rem; }

        .service-badge {
            display: inline-block; font-size: 0.8rem; font-weight: 700;
            padding: 0.25rem 0.75rem; border-radius: 50px; margin-bottom: 0.75rem;
        }

        .badge-gold { background: rgba(251, 191, 36, 0.15); color: var(--gold); border: 1px solid rgba(251, 191, 36, 0.3); }
        .badge-emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

        .service-title {
            font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800;
            color: var(--text); margin-bottom: 0.5rem;
        }

        .service-desc {
            font-size: 0.95rem; color: var(--text-secondary); line-height: 1.5;
        }

        .service-list {
            list-style: none; margin: 1.25rem 0; display: flex; flex-direction: column; gap: 0.6rem;
        }

        .service-list li {
            font-size: 0.9rem; color: var(--text-secondary);
            display: flex; align-items: center; gap: 0.5rem;
        }

        .service-list li span { color: var(--gold); }

        /* Turnera Main Section */
        .booking-section {
            background: var(--card-bg); border: 1px solid var(--card-border);
            border-radius: var(--radius-lg); padding: 2.25rem;
            box-shadow: var(--shadow);
        }

        .booking-title-group { text-align: center; margin-bottom: 2rem; }

        .booking-title-group h2 {
            font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;
            color: var(--text); margin-bottom: 0.4rem;
        }

        .booking-title-group p { font-size: 1rem; color: var(--text-muted); }

        /* Species Toggle Chips */
        .species-toggle {
            display: flex; gap: 1rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;
        }

        .species-btn {
            flex: 1; padding: 0.9rem 1.2rem; border-radius: var(--radius-md);
            background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-muted);
            color: var(--text-muted); font-family: 'Outfit', sans-serif; font-weight: 700;
            font-size: 1.05rem; cursor: pointer; transition: var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        }

        .species-btn.active {
            background: rgba(251, 191, 36, 0.15); border-color: var(--gold);
            color: var(--gold); box-shadow: 0 4px 20px var(--gold-glow);
        }

        .species-btn.gato-active {
            background: rgba(16, 185, 129, 0.15); border-color: var(--emerald);
            color: #34d399; box-shadow: 0 4px 20px var(--emerald-glow);
        }

        /* Form Controls */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        @media (max-width: 650px) { .form-row { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 1rem; }

        label {
            display: block; font-size: 0.85rem; font-weight: 600;
            color: var(--text-secondary); margin-bottom: 0.4rem;
        }

        input, select, textarea {
            width: 100%; padding: 0.75rem 1rem; background: #0f172a;
            border: 1px solid var(--border-muted); border-radius: 10px;
            color: var(--text); font-size: 0.95rem; font-family: inherit;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-glow);
        }

        /* Live Quote Summary Card */
        .quote-card {
            background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(16, 185, 129, 0.4);
            border-radius: var(--radius-md); padding: 1.5rem; margin: 1.75rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .quote-header {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.85rem; font-weight: 700; color: var(--emerald); margin-bottom: 0.6rem;
        }

        .quote-sync-badge {
            background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.75rem;
        }

        .btn-submit-apt {
            width: 100%; padding: 1.05rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.1rem;
            border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition);
            box-shadow: 0 6px 25px var(--emerald-glow); display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        }

        .btn-submit-apt:hover { transform: translateY(-2px); box-shadow: 0 10px 35px var(--emerald-glow); }

        /* Store / Products Section */
        .store-section {
            background: var(--card-bg); border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg); padding: 2.25rem; box-shadow: var(--shadow);
        }

        .search-box { position: relative; margin-bottom: 1.25rem; }

        .search-box input {
            padding-left: 2.8rem; background: #0f172a; border-color: var(--card-border);
        }

        .search-icon {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: var(--gold); pointer-events: none;
        }

        .category-chips {
            display: flex; gap: 0.6rem; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 0.5rem;
        }

        .chip {
            background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-muted);
            color: var(--text-muted); padding: 0.4rem 1rem; border-radius: 50px;
            font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: var(--transition); white-space: nowrap;
        }

        .chip.active { background: rgba(251, 191, 36, 0.2); border-color: var(--gold); color: var(--gold); }

        .products-scroll {
            max-height: 420px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem;
            margin-bottom: 1.5rem; padding-right: 0.25rem;
        }

        .product-item {
            background: rgba(15, 23, 42, 0.7); border: 1px solid var(--border-muted);
            border-radius: var(--radius-md); padding: 0.9rem 1.2rem;
            display: flex; justify-content: space-between; align-items: center; gap: 1rem;
        }

        .product-info h4 { font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text); }
        .product-info p { font-size: 0.8rem; color: var(--text-muted); }
        .product-price { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--gold); }

        .qty-controls {
            display: flex; align-items: center; gap: 0.4rem; background: #0f172a;
            border: 1px solid var(--border-muted); border-radius: 8px; padding: 0.2rem 0.5rem;
        }

        .qty-btn {
            background: none; border: none; color: var(--gold); font-weight: 800;
            font-size: 1.1rem; cursor: pointer; width: 26px; height: 26px;
        }

        /* Toast */
        .toast {
            position: fixed; bottom: 2rem; right: 2rem; background: #10b981;
            color: #ffffff; padding: 1rem 1.6rem; border-radius: var(--radius-md);
            font-weight: 700; box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 1000;
            opacity: 0; transform: translateY(20px); transition: var(--transition); pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }

        /* Footer */
        footer {
            border-top: 1px solid var(--border-muted); padding: 3rem 1.5rem;
            text-align: center; color: var(--text-muted); font-size: 0.88rem;
            display: flex; flex-direction: column; align-items: center; gap: 1rem;
        }

        .footer-socials { display: flex; gap: 1.5rem; justify-content: center; align-items: center; flex-wrap: wrap; }
        .footer-socials a { color: var(--gold); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem; }
    </style>
</head>
<body>
    <div class="bg-glow"></div>

    <!-- Header -->
    <header>
        <div class="header-inner">
            <a href="./" class="brand">
                <span class="brand-icon">🐾</span>
                <span class="brand-name">Almitas <span>Peludas</span></span>
            </a>
            <nav>
                <a href="#servicios">Servicios</a>
                <a href="#turnera">Turnera Online</a>
                <a href="#tienda">Alimentos &amp; Piedritas</a>
                <a href="https://www.instagram.com/almitaspeludas.ok/" target="_blank" rel="noopener">@almitaspeludas.ok</a>
                <a href="#turnera" class="btn-nav">Reservar Turno</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-pill">✨ Cuidado Amoroso a Domicilio en CABA</div>
            <h1>El cuidado sin estrés que tu peludito merece en casa</h1>
            <p>Peluquería canina, grooming felino y Cat Sitting por Rossmari. Atención personalizada en tu hogar sin traslados, jaulas ni esperas.</p>
            <div class="hero-cta-group">
                <a href="#turnera" onclick="setEspecie('perro')" class="btn-hero btn-hero-primary">🐶 Grooming Canino</a>
                <a href="#turnera" onclick="setEspecie('gato')" class="btn-hero btn-hero-secondary">🐱 Cat Sitting Rossmari</a>
            </div>
        </section>

        <!-- Services Cards Section -->
        <section id="servicios" class="services-grid">
            <!-- Card 1: Grooming Canino -->
            <div class="service-card">
                <div class="service-header">
                    <span class="service-badge badge-gold">Peluquería Canina</span>
                    <h3 class="service-title">Grooming Canino a Domicilio</h3>
                    <p class="service-desc">Atención paciente adaptada al temperamento y manto de tu perrito en la tranquilidad de tu hogar.</p>
                </div>
                <ul class="service-list">
                    <li><span>✔</span> Baño profundo &amp; secado profesional</li>
                    <li><span>✔</span> Corte de raza, tijera &amp; despeje higiénico</li>
                    <li><span>✔</span> Deslanado intensivo anti-muda de subpelo</li>
                    <li><span>✔</span> Corte de uñas &amp; limpieza de oídos</li>
                </ul>
                <a href="#turnera" onclick="setEspecie('perro')" class="btn-hero btn-hero-primary" style="margin-top: 1rem; width: 100%; justify-content: center;">Cotizar Turno Canino</a>
            </div>

            <!-- Card 2: Cat Sitting Rossmari -->
            <div class="service-card" style="border-color: rgba(16, 185, 129, 0.35);">
                <div class="service-header">
                    <span class="service-badge badge-emerald">Especial Felinos por Rossmari</span>
                    <h3 class="service-title">Cat Sitting &amp; Grooming Felino</h3>
                    <p class="service-desc">Cuidado amoroso respetuoso en tu hogar cuando viajás o trabajás. Sin sacar al michi de su territorio.</p>
                </div>
                <ul class="service-list">
                    <li><span>✔</span> Visitas de alimentación &amp; agua fresca</li>
                    <li><span>✔</span> Limpieza de litera &amp; cepillado suave</li>
                    <li><span>✔</span> Juego adaptado &amp; contención emocional</li>
                    <li><span>✔</span> Reportes diarios con fotos/videos a WhatsApp</li>
                </ul>
                <a href="#turnera" onclick="setEspecie('gato')" class="btn-hero btn-hero-secondary" style="margin-top: 1rem; width: 100%; justify-content: center;">Reservar Cat Sitting</a>
            </div>
        </section>

        <!-- Booking Turnera Section -->
        <section id="turnera" class="booking-section">
            <div class="booking-title-group">
                <h2>Cotizá y Reservá tu Cita a Domicilio</h2>
                <p>Seleccioná la especie de tu compañero y calculá el costo estimado en tiempo real para CABA.</p>
            </div>

            <!-- Species Toggle -->
            <div class="species-toggle">
                <button type="button" id="btn-especie-perro" class="species-btn active" onclick="setEspecie('perro')">
                    🐶 Perro (Grooming &amp; Baño)
                </button>
                <button type="button" id="btn-especie-gato" class="species-btn" onclick="setEspecie('gato')">
                    🐱 Gato (Rossmari Cat Sitting)
                </button>
            </div>

            <!-- Card Rossmari Cat Sitter Info -->
            <div id="rossmari-card" style="display: none; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: var(--radius-md); padding: 1.1rem; margin-bottom: 1.75rem;">
                <div style="display: flex; align-items: center; gap: 0.9rem;">
                    <span style="font-size: 1.8rem;">🐱</span>
                    <div>
                        <strong style="color: var(--emerald); font-size: 1rem;">Rossmari — Cat Sitter &amp; Especialista Felina</strong>
                        <p style="font-size: 0.88rem; color: var(--text-secondary); margin-top: 0.25rem; line-height: 1.4;">
                            Cuidado respetuoso sin estrés en tu hogar. Visitas de alimentación, higiene de litera, juego adaptado y reportes diarios con fotos/videos directos a tu WhatsApp.
                        </p>
                    </div>
                </div>
            </div>

            <form id="appointment-form" onsubmit="submitAppointment(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label for="dueno_nombre">Tu Nombre Completo *</label>
                        <input type="text" id="dueno_nombre" required placeholder="Ej: Santiago">
                    </div>
                    <div class="form-group">
                        <label for="telefono">WhatsApp / Teléfono *</label>
                        <input type="tel" id="telefono" required placeholder="Ej: 11 1234-5678">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mascota_nombre">Nombre de tu Mascota *</label>
                        <input type="text" id="mascota_nombre" required placeholder="Ej: Firulais / Mishi">
                    </div>
                    <div class="form-group">
                        <label for="mascota_raza">Raza o Mestizo</label>
                        <input type="text" id="mascota_raza" placeholder="Ej: Caniche, Siamés, Mestizo...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="direccion">Dirección de Visita en CABA *</label>
                        <input type="text" id="direccion" required placeholder="Ej: Av. Corrientes 1234, Piso 2A">
                    </div>
                    <div class="form-group">
                        <label for="zona_atencion">Zona de Atención *</label>
                        <select id="zona_atencion" required>
                            <option value="CABA Norte / Centro (Palermo, Belgrano, Recoleta, Nuñez, Colegiales)" data-viatico="0">CABA Norte / Centro (Palermo, Belgrano, Recoleta...)</option>
                            <option value="CABA Sur / Oeste (Caballito, Almagro, Flores, Devoto)" data-viatico="2000">CABA Sur / Oeste (Caballito, Almagro, Devoto...) - Viático $2.000</option>
                            <option value="GBA Norte (Olivos, San Isidro, Tigre, Vicente López)" data-viatico="5000">GBA Norte (Olivos, San Isidro, Vicente López...) - Viático $5.000</option>
                            <option value="GBA Oeste / Sur (Avellaneda, Lanús, San Martín, Morón)" data-viatico="6000">GBA Oeste / Sur (Avellaneda, Lanús, San Martín...) - Viático $6.000</option>
                        </select>
                    </div>
                </div>

                <!-- Campos Modo Perro -->
                <div id="container-perro-opciones">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mascota_tamano">Tamaño de la Mascota *</label>
                            <select id="mascota_tamano">
                                <option value="Chico (hasta 8 kg - Caniche, Pug, Yorkie)" data-base="25000">Chico (hasta 8 kg - Caniche, Pug, Yorkie) - $25.000</option>
                                <option value="Mediano (8 a 18 kg - Beagle, Cocker, Schnauzer)" data-base="32000">Mediano (8 a 18 kg - Beagle, Cocker, Schnauzer) - $32.000</option>
                                <option value="Grande (18 a 35 kg - Golden, Labrador, Border Collie)" data-base="42000">Grande (18 a 35 kg - Golden, Labrador) - $42.000</option>
                                <option value="Gigante (+35 kg - San Bernardo, Gran Danés)" data-base="52000">Gigante (+35 kg - San Bernardo) - $52.000</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="manto_estado">Estado del Pelaje *</label>
                            <select id="manto_estado">
                                <option value="Normal / Manto Saludable" data-adicional="0">Normal / Manto Saludable</option>
                                <option value="Muda / Deslanado Intensivo de Subpelo" data-adicional="5000">Muda / Deslanado Intensivo (+$5.000)</option>
                                <option value="Nudos Moderados / Desanudado Progresivo" data-adicional="8000">Nudos Moderados / Desanudado (+$8.000)</option>
                                <option value="Fieltrado / Nudos Severos" data-adicional="12000">Fieltrado / Nudos Severos (+$12.000)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tratamiento_servicio">Servicio Requerido *</label>
                            <select id="tratamiento_servicio">
                                <option value="Baño &amp; Higiene Profunda (Uñas, Oídos, Sanitario)" data-adicional="0">Baño &amp; Higiene Profunda</option>
                                <option value="Corte de Raza / Tijera / Grooming Completo" data-adicional="5000">Corte de Raza / Tijera Completo (+$5.000)</option>
                                <option value="Baño Terapéutico Dermocosmético (Piel Sensible)" data-adicional="4000">Baño Terapéutico Dermocosmético (+$4.000)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="frecuencia_rutina">Frecuencia &amp; Descuento *</label>
                            <select id="frecuencia_rutina">
                                <option value="Rutina Manto Perfecto (Cada 3 Semanas - 10% OFF)" data-descuento="0.10">Cada 3 Semanas (10% OFF)</option>
                                <option value="Rutina Higiene Regular (Cada 4 Semanas - 5% OFF)" data-descuento="0.05" selected>Cada 4 Semanas (5% OFF)</option>
                                <option value="Visita Eventual / Ocasional" data-descuento="0">Visita Eventual (Sin Descuento)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Campos Modo Gato -->
                <div id="container-gato-opciones" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="servicio_felino">Servicio Felino Rossmari *</label>
                            <select id="servicio_felino">
                                <option value="Cat Sitting / Visita de Cuidado a Domicilio (Rossmari)" data-base="18000">Cat Sitting / Visita de Cuidado a Domicilio - Base $18.000</option>
                                <option value="Cepillado Felino &amp; Deslanado Suave sin Estrés" data-base="22000">Cepillado Felino &amp; Deslanado Suave - Base $22.000</option>
                                <option value="Combo Multigato (Cat Sitting + Grooming Suave)" data-base="28000">Combo Multigato (Sitting + Grooming) - Base $28.000</option>
                                <option value="Pack Almitas Felino (Sitting + Bolsón Rubicat 10kg + Envío Gratis)" data-base="35000">Pack Almitas Felino (Sitting + Rubicat 10kg) - Base $35.000</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_gatos">Cantidad de Gatos en Casa *</label>
                            <select id="cantidad_gatos">
                                <option value="1 Gato en Casa" data-adicional="0">1 Gato en Casa (+$0)</option>
                                <option value="2 Gatos en Casa" data-adicional="4000">2 Gatos en Casa (+$4.000)</option>
                                <option value="3 o más Gatos (Hogar Multigato)" data-adicional="7000">3 o más Gatos (Hogar Multigato +$7.000)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="frecuencia_felina">Frecuencia / Pack Viajero *</label>
                        <select id="frecuencia_felina">
                            <option value="Visita Única / Eventual" data-descuento="0">Visita Única / Eventual</option>
                            <option value="Pack Diario Viajero (3 a 7 días seguidos - 10% OFF)" data-descuento="0.10">Pack Diario Viajero (3 a 7 días seguidos - 10% OFF)</option>
                            <option value="Mantenimiento Quincenal de Cepillado (5% OFF)" data-descuento="0.05">Mantenimiento Quincenal (5% OFF)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_turno">Fecha Preferida *</label>
                        <input type="date" id="fecha_turno" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="horario_turno">Franja Horaria *</label>
                        <select id="horario_turno" required>
                            <option value="Mañana (09:00 - 13:00)">Mañana (09:00 - 13:00)</option>
                            <option value="Tarde (13:00 - 17:00)">Tarde (13:00 - 17:00)</option>
                            <option value="A confirmar por WhatsApp">A confirmar por WhatsApp</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="manto_descripcion">Notas o Preferencias Especiales para tu Peludito</label>
                    <textarea id="manto_descripcion" rows="2" placeholder="Ej: Es sensible al secador, prefiere jugar con varita de plumas, nudos suaves en orejas..."></textarea>
                </div>

                <!-- Live Dynamic Price Calculator Summary Card -->
                <div class="quote-card">
                    <div class="quote-header">
                        <span>PRESUPUESTO ESTIMADO DE TU CITA</span>
                        <span class="quote-sync-badge">🟢 Registro en Odoo 19</span>
                    </div>
                    <div id="summary-text-detail" style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.5;">
                        Calculando cotización estimada...
                    </div>
                </div>

                <button type="submit" class="btn-submit-apt" id="btn-submit-apt">
                    Reservar Cita y Confirmar por WhatsApp
                </button>
            </form>
        </section>

        <!-- Store / Products Section -->
        <section id="tienda" class="store-section">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--text); margin-bottom: 0.4rem;">Tienda de Alimentos &amp; Piedritas Sanitarias</h3>
            <p style="font-size: 0.92rem; color: var(--text-muted); margin-bottom: 1.5rem;">Sumá alimento o piedras sanitarias Rubicat a tu pedido con entrega directa en tu domicilio.</p>

            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="search-input" placeholder="Buscar por marca o alimento (CatPro, Rubicat, Pro Plan, Royal Canin)..." oninput="renderCatalog()">
            </div>

            <div class="category-chips">
                <div class="chip active" onclick="filterCategory('ALL', this)">Todos los Productos</div>
                <div class="chip" onclick="filterCategory('Perros', this)">Perros</div>
                <div class="chip" onclick="filterCategory('Gatos', this)">Gatos</div>
                <div class="chip" onclick="filterCategory('Piedritas & Higiene', this)">Piedritas &amp; Higiene</div>
            </div>

            <div class="products-scroll" id="products-list">
                <!-- Dynamic items rendered via JS -->
            </div>

            <div style="background: rgba(15, 23, 42, 0.95); border: 1px solid var(--card-border); border-radius: var(--radius-md); padding: 1.25rem;">
                <div id="cart-breakdown" style="margin-bottom: 0.8rem; font-size: 0.88rem; color: var(--text-secondary);">
                    <div style="color: var(--text-muted); font-style: italic;">Sin productos seleccionados en el carrito.</div>
                </div>
                <div style="display: flex; justify-content: space-between; font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--text); margin-bottom: 1rem;">
                    <span>Total del Carrito:</span>
                    <span id="cart-total-price" style="color: var(--gold);">$0</span>
                </div>
                <div class="form-row">
                    <input type="text" id="order-name" placeholder="Tu Nombre Completo *">
                    <input type="tel" id="order-phone" placeholder="WhatsApp / Teléfono *">
                </div>
                <input type="text" id="order-address" placeholder="Dirección de Entrega *" style="margin-bottom: 1rem;">
                <button class="btn-submit-apt" style="background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%); color: #0f172a;" onclick="submitWholesaleOrder()">
                    Enviar Pedido por WhatsApp
                </button>
            </div>
        </section>

        <?php if ($is_admin): ?>
        <!-- Section: Ficha de Atención Post-Servicio (Uso Interno Groomer & Reporte Cliente) -->
        <section id="ficha-grooming" class="booking-section" style="border-color: rgba(16, 185, 129, 0.35);">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--emerald); margin-bottom: 0.4rem;">Ficha Clínica Post-Atención &amp; Reporte (Panel Groomer Interno)</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Registrá la conducta real, estado de piel/manto encontrado y generá la Ficha de Sesión para el dueño y Odoo 19.</p>

            <form id="post-grooming-form" onsubmit="submitPostGroomingReport(event)">
                <div class="form-row">
                    <input type="text" id="report_dueno_nombre" required placeholder="Nombre del Dueño/a *">
                    <input type="tel" id="report_dueno_telefono" required placeholder="WhatsApp / Teléfono *">
                </div>

                <div class="form-row">
                    <input type="text" id="report_mascota_nombre" required placeholder="Nombre de la Mascota *">
                    <select id="report_temperamento" required>
                        <option value="Tranquilo / Colaborativo (Excelente comportamiento)">🟢 Tranquilo / Colaborativo</option>
                        <option value="Inquieto / Sensible (Requiere paciencia y trabajo pausado)">🟡 Inquieto / Sensible</option>
                        <option value="Reactivo / Intento de Mordida (Uso de Bozal / Manejo Especial)">🔴 Reactivo / Manejo Especial</option>
                    </select>
                </div>

                <div class="form-row">
                    <select id="report_piel_estado" required>
                        <option value="Sana / Excelente condición">Sana / Excelente condición</option>
                        <option value="Piel Sensible / Enrojecimiento / Irritación">Piel Sensible / Enrojecimiento</option>
                        <option value="Dermatitis / Alergia Cutánea Visible">Dermatitis / Alergia Cutánea</option>
                        <option value="Presencia de Pulgas / Garrapatas (Tratamiento Recomendado)">Presencia de Pulgas / Garrapatas</option>
                    </select>
                    <select id="report_procedimiento" required>
                        <option value="Baño Profundo + Secado + Higiene Sanitaria">Baño Profundo + Secado + Higiene</option>
                        <option value="Corte de Raza / Tijera Completo + Baño">Corte de Raza / Tijera + Baño</option>
                        <option value="Deslanado Intensivo de Subpelo + Baño">Deslanado Intensivo + Baño</option>
                        <option value="Desanudado Paciente + Corte Higiénico + Baño">Desanudado Paciente + Corte + Baño</option>
                        <option value="Baño Terapéutico Dermocosmético">Baño Terapéutico Dermocosmético</option>
                    </select>
                </div>

                <div class="form-row">
                    <select id="report_proxima_visita" required>
                        <option value="Volver en 21 días (Ideal para mantenimiento de manto/tijera)">Recomendar volver en 21 días</option>
                        <option value="Volver en 28 días (Frecuencia estándar de higiene regular)">Recomendar volver en 28 días</option>
                        <option value="Volver en 45 días (Mantenimiento básico)">Recomendar volver en 45 días</option>
                    </select>
                    <select id="report_mordida_recargo">
                        <option value="Sin Recargo (Tarifa Estándar)">Sin Recargo (Tarifa Estándar)</option>
                        <option value="Recargo Manejo Especial / Mordida (+$5.000)">Recargo Manejo Especial (+$5.000)</option>
                        <option value="Recargo Trabajo Extra Nudos Severos (+$8.000)">Recargo Nudos Severos (+$8.000)</option>
                    </select>
                </div>

                <textarea id="report_observaciones" rows="3" placeholder="Recomendaciones Pedagógicas & Tips para el Dueño..." style="margin-bottom: 1rem;"></textarea>

                <button type="submit" class="btn-submit-apt">
                    Guardar Ficha en Odoo 19 y Enviar Reporte por WhatsApp
                </button>
            </form>
        </section>

        <!-- Marketing & Copys Section -->
        <section id="copys" class="store-section">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--text); margin-bottom: 0.4rem;">Centro de Difusión y Copys de Redes (Panel Interno)</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem;">Textos promocionales limpios sin emojis listos para publicar en Instagram, Facebook y WhatsApp.</p>

            <div style="display: flex; gap: 0.75rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-muted); padding-bottom: 0.75rem;">
                <button class="chip active" onclick="showCopyTab('tab1', event)">Post Feed (IG/FB)</button>
                <button class="chip" onclick="showCopyTab('tab2', event)">Historias / Estados</button>
                <button class="chip" onclick="showCopyTab('tab3', event)">Mensaje Difusión WhatsApp</button>
            </div>

            <div id="tab1" class="copy-tab-content">
                <div style="background: #0f172a; border: 1px solid var(--border-muted); border-radius: var(--radius-md); padding: 1rem; font-family: monospace; font-size: 0.88rem; color: var(--text-secondary); white-space: pre-wrap; margin-bottom: 1rem;" id="copy-text-1">!Abrimos pedido de alimentos y sanitarios en Almitas Peludas!

Llegó el momento de reponer el alimento y las piedritas para tus peludos. Coordinamos pedido directo para ofrecerte los mejores precios y llevarlo a tu puerta.

DESTACADOS DE ESTA SEMANA:
- CatPro Gatos Indoor / Castrados (7.5 kg) - Nutrición balanceada.
- Piedras Sanitarias Rubicat Premium (10 kg) - Máximo control de olores.

Sumate al pedido y asegurá el stock de tu mascota.</div>
                <button class="btn-nav" style="font-size: 0.85rem;" onclick="copyText('copy-text-1')">Copiar Post Feed</button>
            </div>

            <div id="tab2" class="copy-tab-content" style="display:none;">
                <div style="background: #0f172a; border: 1px solid var(--border-muted); border-radius: var(--radius-md); padding: 1rem; font-family: monospace; font-size: 0.88rem; color: var(--text-secondary); white-space: pre-wrap; margin-bottom: 1rem;" id="copy-text-2">REPOSICION DE STOCK EN ALMITAS PELUDAS
¿Te estás quedando sin alimento o piedritas?
Asegurá tu bolsa de CatPro o Rubicat Premium enviándonos un WhatsApp.</div>
                <button class="btn-nav" style="font-size: 0.85rem;" onclick="copyText('copy-text-2')">Copiar Texto Historias</button>
            </div>

            <div id="tab3" class="copy-tab-content" style="display:none;">
                <div style="background: #0f172a; border: 1px solid var(--border-muted); border-radius: var(--radius-md); padding: 1rem; font-family: monospace; font-size: 0.88rem; color: var(--text-secondary); white-space: pre-wrap; margin-bottom: 1rem;" id="copy-text-3">¡Hola! Te escribimos desde Almitas Peludas.
Estamos coordinando el pedido de alimentos y productos de higiene de esta semana. Decinos qué marca usás y te pasamos la cotización con entrega a domicilio.</div>
                <button class="btn-nav" style="font-size: 0.85rem;" onclick="copyText('copy-text-3')">Copiar Mensaje Difusión</button>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Toast Notification -->
    <div id="toast" class="toast">Texto copiado al portapapeles.</div>

    <footer>
        <div class="footer-socials">
            <a href="https://www.instagram.com/almitaspeludas.ok/" target="_blank" rel="noopener">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg> Instagram @almitaspeludas.ok
            </a>
            <a href="https://www.facebook.com/profile.php?id=61583019582155" target="_blank" rel="noopener">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Facebook Oficial
            </a>
        </div>
        <p>&copy; <?= date('Y') ?> Almitas Peludas &bull; Peluquería Canina, Felina &amp; Cat Sitting a Domicilio CABA</p>
        <?php if ($is_admin): ?>
            <a href="./?logout=1" style="color: var(--text-muted); font-size: 0.75rem; text-decoration: underline;">🔒 Salir de Gestión Interna</a>
        <?php else: ?>
            <a href="./?admin=1" style="color: var(--text-muted); opacity: 0.35; font-size: 0.75rem; text-decoration: none;">Acceso Groomer / Admin</a>
        <?php endif; ?>
    </footer>

    <!-- Load Morquis Catalog JS -->
    <script src="morquis_catalog.js"></script>
    <script>
        let currentCategory = 'ALL';
        let currentEspecie = 'perro';
        const userCart = {};

        function setEspecie(esp) {
            currentEspecie = esp;
            const btnPerro = document.getElementById('btn-especie-perro');
            const btnGato = document.getElementById('btn-especie-gato');
            const rossmariCard = document.getElementById('rossmari-card');
            const perroContainer = document.getElementById('container-perro-opciones');
            const gatoContainer = document.getElementById('container-gato-opciones');

            if (esp === 'gato') {
                btnPerro.classList.remove('active', 'gato-active');
                btnGato.classList.add('gato-active');
                rossmariCard.style.display = 'block';
                perroContainer.style.display = 'none';
                gatoContainer.style.display = 'block';
            } else {
                btnGato.classList.remove('active', 'gato-active');
                btnPerro.classList.add('active');
                rossmariCard.style.display = 'none';
                gatoContainer.style.display = 'none';
                perroContainer.style.display = 'block';
            }
            updateLiveSummary();
        }

        function getDatasetValue(elementId, datasetKey, fallback = '0') {
            const el = document.getElementById(elementId);
            if (!el || el.selectedIndex < 0) return fallback;
            const opt = el.options[el.selectedIndex];
            return (opt && opt.dataset && opt.dataset[datasetKey] !== undefined) ? opt.dataset[datasetKey] : fallback;
        }

        function getSelectValue(elementId, fallback = '') {
            const el = document.getElementById(elementId);
            if (!el || el.selectedIndex < 0) return fallback;
            return el.value || fallback;
        }

        function calculateAppointmentQuote() {
            const viaticoZona = parseInt(getDatasetValue('zona_atencion', 'viatico', '0'), 10);
            const zonaTxt = getSelectValue('zona_atencion', '');

            if (currentEspecie === 'gato') {
                const basePrice = parseInt(getDatasetValue('servicio_felino', 'base', '18000'), 10);
                const adicionalGatos = parseInt(getDatasetValue('cantidad_gatos', 'adicional', '0'), 10);
                const descPct = parseFloat(getDatasetValue('frecuencia_felina', 'descuento', '0'));

                const subtotal = basePrice + adicionalGatos + viaticoZona;
                const descuento = Math.round(subtotal * descPct);
                const total = subtotal - descuento;

                return {
                    especie: 'gato',
                    basePrice, adicionalManto: adicionalGatos, adicionalTratamiento: 0, viaticoZona, descPct, descuento, subtotal, total,
                    tamanoTxt: getSelectValue('cantidad_gatos', '1 Gato en Casa'),
                    mantoTxt: 'Cuidado Felino sin Estrés (Rossmari)',
                    tratamientoTxt: getSelectValue('servicio_felino', 'Cat Sitting a Domicilio'),
                    zonaTxt, rutinaTxt: getSelectValue('frecuencia_felina', 'Visita Única / Eventual')
                };
            } else {
                const basePrice = parseInt(getDatasetValue('mascota_tamano', 'base', '25000'), 10);
                const adicionalManto = parseInt(getDatasetValue('manto_estado', 'adicional', '0'), 10);
                const adicionalTratamiento = parseInt(getDatasetValue('tratamiento_servicio', 'adicional', '0'), 10);
                const descPct = parseFloat(getDatasetValue('frecuencia_rutina', 'descuento', '0'));

                const subtotal = basePrice + adicionalManto + adicionalTratamiento + viaticoZona;
                const descuento = Math.round(subtotal * descPct);
                const total = subtotal - descuento;

                return {
                    especie: 'perro',
                    basePrice, adicionalManto, adicionalTratamiento, viaticoZona, descPct, descuento, subtotal, total,
                    tamanoTxt: getSelectValue('mascota_tamano', ''),
                    mantoTxt: getSelectValue('manto_estado', ''),
                    tratamientoTxt: getSelectValue('tratamiento_servicio', ''),
                    zonaTxt, rutinaTxt: getSelectValue('frecuencia_rutina', '')
                };
            }
        }

        async function submitAppointment(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-apt');
            btn.disabled = true;
            btn.innerText = 'Enviando reserva a Odoo...';

            const quote = calculateAppointmentQuote();

            const payload = {
                action: 'create_appointment',
                dueno_nombre: document.getElementById('dueno_nombre').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                mascota_nombre: document.getElementById('mascota_nombre').value.trim(),
                mascota_raza: document.getElementById('mascota_raza').value.trim(),
                direccion: document.getElementById('direccion').value.trim(),
                zona_atencion: quote.zonaTxt,
                mascota_tamano: quote.tamanoTxt,
                manto_estado: quote.mantoTxt,
                tratamiento_servicio: quote.tratamientoTxt,
                frecuencia_rutina: quote.rutinaTxt,
                manto_descripcion: document.getElementById('manto_descripcion').value.trim(),
                fecha_turno: document.getElementById('fecha_turno').value,
                horario_turno: document.getElementById('horario_turno').value,
                total_cotizado: quote.total,
                subtotal: quote.subtotal,
                descuento_aplicado: quote.descuento,
                viatico: quote.viaticoZona
            };

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    showToast('Reserva registrada en Odoo 19. Abriendo WhatsApp...');

                    let text = ``;
                    if (quote.especie === 'gato') {
                        text = `RESERVA DE CAT SITTING Y CUIDADO FELINO (ROSSMARI) - ALMITAS PELUDAS\n`;
                        text += `------------------------------------\n`;
                        text += `Cliente: ${payload.dueno_nombre} (${payload.telefono})\n`;
                        text += `Mascota: ${payload.mascota_nombre} (${payload.mascota_raza || 'Mishi'})\n`;
                        text += `Servicio: ${payload.tratamiento_servicio}\n`;
                        text += `Cantidad: ${payload.mascota_tamano}\n`;
                        text += `Plan / Frecuencia: ${payload.frecuencia_rutina}\n`;
                        if (payload.manto_descripcion) text += `Notas Especiales: ${payload.manto_descripcion}\n`;
                        text += `Zona: ${payload.zona_atencion}\n`;
                        text += `Fecha: ${payload.fecha_turno} (${payload.horario_turno})\n`;
                        text += `Dirección: ${payload.direccion}\n\n`;
                        text += `PRESUPUESTO ESTIMADO: $${payload.total_cotizado.toLocaleString('es-AR')}\n\n`;
                        text += `Solicito confirmación de la visita por Rossmari por este medio.`;
                    } else {
                        text = `RESERVA DE GROOMING CANINO A DOMICILIO - ALMITAS PELUDAS\n`;
                        text += `------------------------------------\n`;
                        text += `Cliente: ${payload.dueno_nombre} (${payload.telefono})\n`;
                        text += `Mascota: ${payload.mascota_nombre} (${payload.mascota_raza || 'Mestizo'})\n`;
                        text += `Tamaño: ${payload.mascota_tamano}\n`;
                        text += `Tratamiento: ${payload.tratamiento_servicio}\n`;
                        text += `Frecuencia: ${payload.frecuencia_rutina}\n`;
                        if (payload.manto_descripcion) text += `Notas: ${payload.manto_descripcion}\n`;
                        text += `Zona: ${payload.zona_atencion}\n`;
                        text += `Fecha: ${payload.fecha_turno} (${payload.horario_turno})\n`;
                        text += `Dirección: ${payload.direccion}\n\n`;
                        text += `PRESUPUESTO ESTIMADO: $${payload.total_cotizado.toLocaleString('es-AR')}\n\n`;
                        text += `Solicito confirmación del turno por este medio.`;
                    }

                    const waUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
                    setTimeout(() => window.open(waUrl, '_blank'), 600);

                    document.getElementById('appointment-form').reset();
                    updateLiveSummary();
                } else {
                    alert(data.error || 'Error registrando reserva.');
                }
            } catch (err) {
                alert('Error conectando con la API.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Reservar Cita y Confirmar por WhatsApp';
            }
        }

        function filterCategory(cat, element) {
            currentCategory = cat;
            document.querySelectorAll('.category-chips .chip').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
            renderCatalog();
        }

        function renderCatalog() {
            const container = document.getElementById('products-list');
            const search = document.getElementById('search-input').value.toLowerCase().trim();

            let filtered = MORQUIS_CATALOG;
            if (currentCategory !== 'ALL') {
                filtered = filtered.filter(p => p.category === currentCategory);
            }
            if (search) {
                filtered = filtered.filter(p => p.name.toLowerCase().includes(search) || p.brand.toLowerCase().includes(search));
            }

            const displayItems = filtered.slice(0, 40);

            if (displayItems.length === 0) {
                container.innerHTML = `<div style="text-align:center; padding: 2rem; color: var(--text-muted);">No se encontraron productos para "${search}".</div>`;
                return;
            }

            container.innerHTML = displayItems.map((item) => {
                const itemIndex = MORQUIS_CATALOG.indexOf(item);
                const qty = userCart[itemIndex] || 0;
                const formattedPrice = '$' + item.cost_price.toLocaleString('es-AR');

                return `
                    <div class="product-item">
                        <div class="product-info">
                            <h4>${item.name}</h4>
                            <p>${item.brand ? 'Marca: ' + item.brand + ' &bull; ' : ''}${item.category}</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div class="product-price">${formattedPrice}</div>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn" onclick="updateItemQty(${itemIndex}, -1)">-</button>
                                <span style="font-weight:700; width:18px; text-align:center;">${qty}</span>
                                <button type="button" class="qty-btn" onclick="updateItemQty(${itemIndex}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateItemQty(index, delta) {
            userCart[index] = Math.max(0, (userCart[index] || 0) + delta);
            renderCatalog();
            calculateCartTotal();
        }

        function calculateCartTotal() {
            let total = 0;
            const breakdownEl = document.getElementById('cart-breakdown');
            const lines = [];

            for (const idx in userCart) {
                if (userCart[idx] > 0) {
                    const item = MORQUIS_CATALOG[idx];
                    const subtotal = userCart[idx] * item.cost_price;
                    total += subtotal;

                    lines.push(`
                        <div style="display:flex; justify-content:space-between; color: var(--text-secondary);">
                            <span>${userCart[idx]}x ${item.name}</span>
                            <span>$${subtotal.toLocaleString('es-AR')}</span>
                        </div>
                    `);
                }
            }

            if (lines.length > 0) {
                breakdownEl.innerHTML = lines.join('');
            } else {
                breakdownEl.innerHTML = '<div style="color: var(--text-muted); font-style: italic;">Sin productos seleccionados en el carrito.</div>';
            }

            document.getElementById('cart-total-price').innerText = '$' + total.toLocaleString('es-AR');
            return total;
        }

        async function submitWholesaleOrder() {
            const name = document.getElementById('order-name').value.trim();
            const phone = document.getElementById('order-phone').value.trim();
            const address = document.getElementById('order-address').value.trim();
            const total = calculateCartTotal();

            if (!name || !phone) {
                alert('Por favor ingresa tu nombre y teléfono.');
                return;
            }

            const items = [];
            for (const idx in userCart) {
                if (userCart[idx] > 0) {
                    items.push({
                        name: MORQUIS_CATALOG[idx].name,
                        qty: userCart[idx],
                        price: MORQUIS_CATALOG[idx].cost_price
                    });
                }
            }

            if (items.length === 0) {
                alert('Selecciona al menos 1 producto del catálogo.');
                return;
            }

            try {
                await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'create_wholesale_order',
                        dueno_nombre: name,
                        telefono: phone,
                        direccion: address,
                        items: items,
                        total_amount: total
                    })
                });
            } catch (e) {}

            let text = `PEDIDO DE ALIMENTOS / PRODUCTOS - ALMITAS PELUDAS\n`;
            text += `------------------------------------\n`;
            text += `Cliente: ${name}\n`;
            text += `Teléfono: ${phone}\n`;
            text += `Dirección: ${address || 'A confirmar'}\n\n`;
            text += `Productos:\n`;
            items.forEach(i => {
                text += `- ${i.qty}x ${i.name} ($${(i.qty * i.price).toLocaleString('es-AR')})\n`;
            });
            text += `\nTOTAL ESTIMADO: $${total.toLocaleString('es-AR')}`;

            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }

        function showCopyTab(tabId, evt) {
            document.querySelectorAll('.copy-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.copy-section .chip').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            const target = evt ? evt.target : (window.event ? window.event.target : null);
            if (target) target.classList.add('active');
        }

        function copyText(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                showToast('Texto copiado al portapapeles.');
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function updateLiveSummary() {
            const dueno = document.getElementById('dueno_nombre')?.value.trim() || 'Tutor/a';
            const mascota = document.getElementById('mascota_nombre')?.value.trim() || 'Mascota';
            const fecha = document.getElementById('fecha_turno')?.value || '';
            const horario = document.getElementById('horario_turno')?.value || '';

            const quote = calculateAppointmentQuote();

            const summaryEl = document.getElementById('summary-text-detail');
            if (summaryEl) {
                if (quote.especie === 'gato') {
                    summaryEl.innerHTML = `
                        <strong>Reserva Felina:</strong> ${dueno} &bull; <strong>Mascota:</strong> ${mascota}<br>
                        <strong>Servicio:</strong> ${quote.tratamientoTxt} &bull; <strong>Hogar:</strong> ${quote.tamanoTxt}<br>
                        <strong>Atención por:</strong> ${quote.mantoTxt}<br>
                        <strong>Frecuencia:</strong> ${quote.rutinaTxt}${quote.descuento > 0 ? ' (Descuento: -$' + quote.descuento.toLocaleString('es-AR') + ')' : ''}<br>
                        <div style="margin-top: 0.6rem; padding-top: 0.4rem; border-top: 1px dashed rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center;">
                            <span>Fecha: <strong>${fecha ? fecha : 'Por definir'} (${horario})</strong></span>
                            <span style="font-size: 1.15rem; font-weight: 800; color: var(--gold);">TOTAL ESTIMADO: $${quote.total.toLocaleString('es-AR')}</span>
                        </div>
                    `;
                } else {
                    summaryEl.innerHTML = `
                        <strong>Reserva Canina:</strong> ${dueno} &bull; <strong>Mascota:</strong> ${mascota}<br>
                        <strong>Servicio:</strong> ${quote.tamanoTxt.split(' - ')[0]} &bull; ${quote.mantoTxt.split(' (')[0]} &bull; ${quote.tratamientoTxt.split(' (')[0]}<br>
                        <strong>Frecuencia:</strong> ${quote.rutinaTxt}${quote.descuento > 0 ? ' (Descuento: -$' + quote.descuento.toLocaleString('es-AR') + ')' : ''}<br>
                        <div style="margin-top: 0.6rem; padding-top: 0.4rem; border-top: 1px dashed rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center;">
                            <span>Fecha: <strong>${fecha ? fecha : 'Por definir'} (${horario})</strong></span>
                            <span style="font-size: 1.15rem; font-weight: 800; color: var(--gold);">TOTAL ESTIMADO: $${quote.total.toLocaleString('es-AR')}</span>
                        </div>
                    `;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderCatalog();

            const inputs = ['dueno_nombre', 'mascota_nombre', 'mascota_raza', 'direccion', 'zona_atencion', 'mascota_tamano', 'manto_estado', 'tratamiento_servicio', 'frecuencia_rutina', 'servicio_felino', 'cantidad_gatos', 'frecuencia_felina', 'fecha_turno', 'horario_turno'];
            inputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateLiveSummary);
                    el.addEventListener('change', updateLiveSummary);
                }
            });

            updateLiveSummary();

            document.getElementById('fecha_turno')?.addEventListener('change', function() {
                if (!this.value) return;
                const chosenDate = new Date(this.value + 'T00:00:00');
                if (chosenDate.getDay() === 0) {
                    alert('Atención: Los domingos no realizamos atención a domicilio. Por favor selecciona una fecha de lunes a sábado.');
                    this.value = '';
                    updateLiveSummary();
                }
            });
        });
    </script>
</body>
</html>
