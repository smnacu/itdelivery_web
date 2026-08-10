<?php
require_once __DIR__ . '/../includes/odoo_api.php';

// Verificación de acceso seguro al panel de administración / groomer
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
    <title>Peluquería Canina a Domicilio y Cat Sitting en Capital Federal | Almitas Peludas</title>
    <meta name="description" content="Servicio profesional de peluquería canina y felina a domicilio en Capital Federal (CABA - Palermo, Belgrano, Recoleta, Caballito). Venta mayorista de alimento CatPro, Pro Plan, Royal Canin y piedritas sanitarias Rubicat.">
    <meta name="keywords" content="peluqueria canina a domicilio capital federal, grooming felino caba, cepillado de gatos buenos aires, cat sitting caba, piedritas rubicat mayorista, alimentos para mascotas capital federal">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://almitas.itdelivery.com.ar/">
    <meta name="theme-color" content="#0f172a">
    
    <!-- Geo Target SEO for CABA -->
    <meta name="geo.region" content="AR-C">
    <meta name="geo.placename" content="Buenos Aires, Capital Federal">
    <meta name="geo.position" content="-34.603722;-58.381592">
    <meta name="ICBM" content="-34.603722, -58.381592">

    <!-- Open Graph / Social Media SEO -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://itdelivery.com.ar/almitas/">
    <meta property="og:title" content="Peluquería Canina a Domicilio y Estética Felina CABA | Almitas Peludas">
    <meta property="og:description" content="Turnera online de baños, cortes y grooming a domicilio en Capital Federal. Venta directa de bolsas de alimentos y piedritas Rubicat.">
    <meta property="og:locale" content="es_AR">

    <!-- JSON-LD Structured Data (Google Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Almitas Peludas",
      "description": "Peluquería Canina y Felina a Domicilio con Cotizador por Tamaño, Diagnóstico de Manto, Cat Sitting y Venta Mayorista en CABA y GBA.",
      "url": "https://itdelivery.com.ar/almitas/",
      "telephone": "+541112345678",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Buenos Aires",
        "addressRegion": "Capital Federal / Gran Buenos Aires",
        "addressCountry": "AR"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-34.603722",
        "longitude": "-58.381592"
      },
      "priceRange": "$$$",
      "openingHours": "Mo-Sa 09:00-19:00",
      "areaServed": [
        { "@type": "AdministrativeArea", "name": "Palermo, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Belgrano, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Recoleta, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Nuñez, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Colegiales, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Caballito, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Almagro, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Flores, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Devoto, Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Vicente López, Gran Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Olivos, Gran Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "San Isidro, Gran Buenos Aires" },
        { "@type": "AdministrativeArea", "name": "Tigre, Gran Buenos Aires" }
      ],
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Servicios de Peluquería Canina y Felina a Domicilio",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Peluquería Canina a Domicilio - Perro Chico (hasta 8kg)",
              "description": "Baño, secado, higiene profunda y corte estético/raza para perros chicos."
            },
            "price": "25000",
            "priceCurrency": "ARS"
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Peluquería Canina a Domicilio - Perro Mediano (8 a 18kg)",
              "description": "Baño, secado, higiene profunda y corte estético/raza para perros medianos."
            },
            "price": "32000",
            "priceCurrency": "ARS"
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Peluquería Canina a Domicilio - Perro Grande (18 a 35kg)",
              "description": "Baño, deslanado, secado e higiene profunda para perros grandes."
            },
            "price": "42000",
            "priceCurrency": "ARS"
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Peluquería Canina a Domicilio - Perro Gigante (+35kg)",
              "description": "Baño, deslanado intensivo y acondicionamiento especializado para razas gigantes."
            },
            "price": "52000",
            "priceCurrency": "ARS"
          }
        ]
      }
    }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #0f172a;
            --bg-alt: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.85);
            --card-border: rgba(251, 191, 36, 0.25);
            --border-muted: rgba(255, 255, 255, 0.1);
            
            --gold: #fbbf24;
            --gold-hover: #f59e0b;
            --gold-glow: rgba(251, 191, 36, 0.25);
            
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.2);
            
            --text: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            --shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            --radius-lg: 20px;
            --radius-md: 12px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(251, 191, 36, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.06) 0%, transparent 40%);
        }

        /* Header */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(16px);
            background: rgba(15, 23, 42, 0.92);
            border-bottom: 1px solid var(--border-muted);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-badge {
            background: var(--gold);
            color: #0f172a;
            font-weight: 900;
            font-size: 0.85rem;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .brand-name span { color: var(--gold); }

        nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            transition: var(--transition);
        }

        nav a:hover { color: var(--gold); }

        .nav-btn {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            color: #0f172a !important;
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-md);
            font-weight: 700 !important;
            box-shadow: 0 4px 15px var(--gold-glow);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--gold-glow);
        }

        /* Container Layout */
        main {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem 5rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        /* Hero Banner */
        .hero {
            text-align: center;
            max-width: 850px;
            margin: 0 auto;
            padding: 1rem 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.3);
            color: var(--gold);
            padding: 0.35rem 0.9rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.6rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 0.85rem;
            background: linear-gradient(135deg, #ffffff 30%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 680px;
            margin: 0 auto;
        }

        /* Wholesaler Timer & Goal Banner */
        .goal-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--gold);
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.12);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .goal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--emerald) 100%);
        }

        .timer-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(251, 191, 36, 0.1);
            border: 1px dashed rgba(251, 191, 36, 0.4);
            border-radius: var(--radius-md);
            padding: 0.75rem 1.25rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .timer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: var(--gold);
            font-size: 0.9rem;
        }

        .timer-badge {
            background: var(--gold);
            color: #0f172a;
            padding: 0.2rem 0.65rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.8rem;
        }

        .goal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .goal-title h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text);
        }

        .goal-title p {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .goal-stats { text-align: right; }

        .goal-amount {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--gold);
        }

        .goal-target {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Progress Bar */
        .progress-track {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--border-muted);
            height: 24px;
            border-radius: 50px;
            overflow: hidden;
            position: relative;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            width: 39.1%;
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 50%, #10b981 100%);
            border-radius: 50px;
            transition: width 0.8s ease;
        }

        .progress-text {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
        }

        .goal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: var(--text-secondary);
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .remaining-badge {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--emerald);
            color: #34d399;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.82rem;
        }

        /* Grid Layout */
        .main-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 2rem;
        }

        @media (max-width: 880px) {
            .main-grid { grid-template-columns: 1fr; }
            nav { display: none; }
            .hero h1 { font-size: 2rem; }
        }

        /* Card Panels */
        .panel-card {
            background: var(--card-bg);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
        }

        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
            color: var(--text);
        }

        .panel-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }

        /* Search & Filters */
        .search-box {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-box input {
            padding-left: 2.5rem;
            font-size: 0.95rem;
            background: #0f172a;
            border: 1px solid var(--card-border);
        }

        .search-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold);
            font-size: 0.95rem;
            pointer-events: none;
        }

        .category-chips {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .chip {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--border-muted);
            color: var(--text-muted);
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .chip.active {
            background: rgba(251, 191, 36, 0.2);
            border-color: var(--gold);
            color: var(--gold);
        }

        /* Products List Scroll */
        .products-scroll {
            max-height: 400px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            padding-right: 0.25rem;
            margin-bottom: 1.25rem;
        }

        .product-item {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-md);
            padding: 0.8rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .product-item:hover {
            border-color: rgba(251, 191, 36, 0.4);
            background: rgba(30, 41, 59, 0.9);
        }

        .product-info h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }

        .product-info p {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .product-price {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            color: var(--gold);
            white-space: nowrap;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--border-muted);
            border-radius: 8px;
            padding: 0.15rem 0.35rem;
        }

        .qty-btn {
            background: none;
            border: none;
            color: var(--gold);
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: var(--transition);
        }

        .qty-btn:hover { background: rgba(251, 191, 36, 0.2); }

        .qty-val {
            font-weight: 700;
            font-size: 0.88rem;
            width: 18px;
            text-align: center;
        }

        /* Cart Summary & Account Breakdown */
        .cart-summary {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            margin-top: auto;
        }

        .cart-items-breakdown {
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-muted);
            padding-bottom: 0.75rem;
            font-size: 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            max-height: 120px;
            overflow-y: auto;
        }

        .cart-line-item {
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .cart-total-row span:last-child { color: var(--gold); }

        /* Form Controls */
        .form-group { margin-bottom: 1rem; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.3rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.7rem 0.9rem;
            background: #0f172a;
            border: 1px solid var(--border-muted);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.92rem;
            font-family: inherit;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-glow);
        }

        .btn-primary {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.02rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px var(--gold-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--gold-glow);
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px var(--emerald-glow);
        }

        .btn-whatsapp:hover { box-shadow: 0 8px 25px var(--emerald-glow); }

        /* Marketing & Copy Section */
        .copy-section {
            background: var(--card-bg);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow);
        }

        .copy-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-muted);
            padding-bottom: 0.75rem;
            overflow-x: auto;
        }

        .tab-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .tab-btn.active {
            background: rgba(251, 191, 36, 0.15);
            color: var(--gold);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .copy-box {
            background: #0f172a;
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            font-family: monospace;
            font-size: 0.88rem;
            color: var(--text-secondary);
            white-space: pre-wrap;
            position: relative;
            margin-bottom: 1rem;
            max-height: 260px;
            overflow-y: auto;
        }

        .btn-copy {
            align-self: flex-start;
            padding: 0.45rem 1.1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border-muted);
            color: var(--text);
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-copy:hover {
            background: rgba(251, 191, 36, 0.2);
            border-color: var(--gold);
            color: var(--gold);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #10b981;
            color: #ffffff;
            padding: 0.9rem 1.4rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
            pointer-events: none;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        footer {
            border-top: 1px solid var(--border-muted);
            padding: 2rem 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.82rem;
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>

    <!-- Header -->
    <header>
        <div class="header-inner">
            <a href="./" class="brand">
                <span class="brand-badge">ALMITAS</span>
                <span class="brand-name">Almitas <span>Peludas</span></span>
            </a>
            <nav>
                <a href="#pedido-mayorista">Pedido Mayorista</a>
                <a href="#turnera">Turnera Domicilio</a>
                <a href="https://www.instagram.com/almitaspeludas.ok/" target="_blank" rel="noopener" title="Instagram Almitas Peludas" style="display:inline-flex; align-items:center; gap:0.3rem;">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg> Instagram
                </a>
                <a href="https://www.facebook.com/profile.php?id=61583019582155" target="_blank" rel="noopener" title="Facebook Almitas Peludas" style="display:inline-flex; align-items:center; gap:0.3rem;">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Facebook
                </a>
                <a href="#turnera" class="nav-btn">Reservar Turno</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-badge">Peluqueria Canina a Domicilio y Catalogo Mayorista Morquis</div>
            <h1>Cuidado profesional para tu mascota y alimentos a precio mayorista</h1>
            <p>Atencion personalizada en tu hogar y compras grupales directo de distribuidora Morquis.</p>
        </section>

        <!-- Wholesaler Progress & Cutoff Timer Banner -->
        <section id="pedido-mayorista" class="goal-card">
            
            <div class="timer-strip">
                <div class="timer-info">
                    <span>Proximo cierre de pedido Morquis:</span>
                    <span class="timer-badge">Viernes a las 18:00 hs</span>
                </div>
                <div style="font-size:0.85rem; color: var(--text-secondary);">
                    Entregas: Martes a Jueves a domicilio.
                </div>
            </div>

            <div class="goal-header">
                <div class="goal-title">
                    <h2>Meta de Compra Mayorista (Morquis - Lista 5 AGO)</h2>
                    <p>Minimo de compra a costo exigido por proveedor: $150.000</p>
                </div>
                <div class="goal-stats">
                    <div class="goal-amount" id="goal-current-text">$58.680</div>
                    <div class="goal-target">Meta: $150.000</div>
                </div>
            </div>

            <div class="progress-track">
                <div class="progress-fill" id="goal-fill" style="width: 39.1%;"></div>
                <div class="progress-text" id="goal-percent-text">39.1% Completado ($58.680)</div>
            </div>

            <div class="goal-footer">
                <div>Falta reunir: <strong style="color: var(--gold);" id="goal-remaining-text">$91.320</strong> en pedidos adicionales.</div>
                <div class="remaining-badge">Sumate al pedido para asegurar stock</div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="main-grid">
            
            <!-- Panel 1: Catálogo Interactivo Morquis (600+ Artículos) -->
            <div class="panel-card">
                <h3 class="panel-title">Catalogo Mayorista Morquis (600+ Productos)</h3>
                <p class="panel-subtitle">Busca por marca o producto (CatPro, Rubicat, Pro Plan, Royal Canin, Fawna, Eukanuba, etc.) y arma tu pedido.</p>

                <!-- Search Input -->
                <div class="search-box">
                    <span class="search-icon">[Search]</span>
                    <input type="text" id="search-input" placeholder="Buscar alimento, marca o piedritas (ej: CatPro, Rubicat)..." oninput="renderCatalog()">
                </div>

                <!-- Category Filters -->
                <div class="category-chips">
                    <div class="chip active" onclick="filterCategory('ALL', this)">Todos (618)</div>
                    <div class="chip" onclick="filterCategory('Perros', this)">Perros</div>
                    <div class="chip" onclick="filterCategory('Gatos', this)">Gatos</div>
                    <div class="chip" onclick="filterCategory('Piedritas & Higiene', this)">Piedritas & Higiene</div>
                </div>

                <!-- Products Scroll Box -->
                <div class="products-scroll" id="products-list">
                    <!-- Dynamic rendering via JS -->
                </div>

                <!-- Cart Summary & Account Breakdown -->
                <div class="cart-summary">
                    <div class="cart-items-breakdown" id="cart-breakdown">
                        <div style="color: var(--text-muted); font-style: italic;">Sin productos seleccionados en el carrito.</div>
                    </div>

                    <div class="cart-total-row">
                        <span>Total de tu pedido:</span>
                        <span id="cart-total-price">$0</span>
                    </div>

                    <div class="form-group">
                        <input type="text" id="order-name" placeholder="Tu Nombre Completo *">
                    </div>
                    <div class="form-group">
                        <input type="tel" id="order-phone" placeholder="Telefono / WhatsApp *">
                    </div>
                    <div class="form-group">
                        <input type="text" id="order-address" placeholder="Direccion de entrega *">
                    </div>

                    <button class="btn-primary btn-whatsapp" onclick="submitWholesaleOrder()">
                        Enviar Pedido por WhatsApp
                    </button>
                </div>
            </div>

            <!-- Panel 2: Turnera Peluquería Canina a Domicilio y Cotizador de Manto -->
            <div class="panel-card" id="turnera">
                <h3 class="panel-title">Cotizador & Turnera a Domicilio</h3>
                <p class="panel-subtitle">Cotiza en tiempo real segun tamaño, especie, servicio de Cat Sitting por Rossmari y rutina de mantenimiento.</p>

                <form id="appointment-form" onsubmit="submitAppointment(event)">
                    <!-- Selector de Especie -->
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label style="font-weight: 700; color: var(--gold); display: block; margin-bottom: 0.5rem;">Especie & Servicio Requerido *</label>
                        <div style="display: flex; gap: 0.75rem;">
                            <button type="button" id="btn-especie-perro" class="chip active" style="flex: 1; padding: 0.65rem; text-align: center; justify-content: center; font-weight: 600;" onclick="setEspecie('perro')">🐶 Perro (Peluquería & Grooming)</button>
                            <button type="button" id="btn-especie-gato" class="chip" style="flex: 1; padding: 0.65rem; text-align: center; justify-content: center; font-weight: 600;" onclick="setEspecie('gato')">🐱 Gato (Cat Sitting por Rossmari)</button>
                        </div>
                    </div>

                    <!-- Card Destacada Rossmari para Gatos -->
                    <div id="rossmari-card" style="display: none; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: var(--radius-md); padding: 0.85rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.4rem;">🐱</span>
                            <div>
                                <strong style="color: var(--emerald); font-size: 0.95rem;">Rossmari — Cat Sitter & Especialista Felina de Almitas Peludas</strong>
                                <p style="font-size: 0.82rem; color: var(--text-secondary); margin-top: 0.2rem; line-height: 1.4;">
                                    Cuidado respetuoso sin estrés en tu hogar. Visitas de alimentación, higiene de litera, juego adaptado y reportes diarios con fotos/videos a tu WhatsApp.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dueno_nombre">Nombre del Dueno/a *</label>
                            <input type="text" id="dueno_nombre" required placeholder="Ej: Santiago">
                        </div>
                        <div class="form-group">
                            <label for="telefono">WhatsApp / Telefono *</label>
                            <input type="tel" id="telefono" required placeholder="Ej: 11 1234-5678">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mascota_nombre">Nombre de la Mascota *</label>
                            <input type="text" id="mascota_nombre" required placeholder="Ej: Firulais / Mishi">
                        </div>
                        <div class="form-group">
                            <label for="mascota_raza">Raza / Mestizo</label>
                            <input type="text" id="mascota_raza" placeholder="Ej: Caniche, Siamés, Mestizo...">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="direccion">Direccion de Visita *</label>
                            <input type="text" id="direccion" required placeholder="Ej: Av. Corrientes 1234, Piso 2A">
                        </div>
                        <div class="form-group">
                            <label for="zona_atencion">Zona de Atencion & Viatico *</label>
                            <select id="zona_atencion" required>
                                <option value="CABA Norte / Centro (Palermo, Belgrano, Recoleta, Nuñez, Colegiales)" data-viatico="0">CABA Norte / Centro (Palermo, Belgrano, Recoleta...) - Viático $0</option>
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
                                <label for="mascota_tamano">Tamano de la Mascota *</label>
                                <select id="mascota_tamano">
                                    <option value="Chico (hasta 8 kg - Caniche, Pug, Yorkie)" data-base="25000">Chico (hasta 8 kg - Caniche, Pug, Yorkie) - Base $25.000</option>
                                    <option value="Mediano (8 a 18 kg - Beagle, Cocker, Schnauzer)" data-base="32000">Mediano (8 a 18 kg - Beagle, Cocker, Schnauzer) - Base $32.000</option>
                                    <option value="Grande (18 a 35 kg - Golden, Labrador, Border Collie)" data-base="42000">Grande (18 a 35 kg - Golden, Labrador, Border Collie) - Base $42.000</option>
                                    <option value="Gigante (+35 kg - San Bernardo, Gran Danés, Mastín)" data-base="52000">Gigante (+35 kg - San Bernardo, Gran Danés, Mastín) - Base $52.000</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="manto_estado">Estado del Manto & Trabajo *</label>
                                <select id="manto_estado">
                                    <option value="Normal / Manto Saludable" data-adicional="0">Normal / Manto Saludable (+$0)</option>
                                    <option value="Muda / Deslanado Intensivo de Subpelo" data-adicional="5000">Muda / Deslanado Intensivo de Subpelo (+$5.000)</option>
                                    <option value="Nudos Moderados / Desanudado Progresivo" data-adicional="8000">Nudos Moderados / Desanudado Progresivo (+$8.000)</option>
                                    <option value="Fieltrado / Nudos Severos (Trabajo Paciente / Higiénico)" data-adicional="12000">Fieltrado / Nudos Severos (+$12.000)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tratamiento_servicio">Tratamiento Requerido *</label>
                                <select id="tratamiento_servicio">
                                    <option value="Baño & Higiene Profunda (Uñas, Oídos, Sanitario)" data-adicional="0">Baño & Higiene Profunda (Uñas, Oídos, Sanitario - +$0)</option>
                                    <option value="Corte de Raza / Tijera / Grooming Completo" data-adicional="5000">Corte de Raza / Tijera / Grooming Completo (+$5.000)</option>
                                    <option value="Baño Terapéutico / Dermocosmético (Piel Sensible)" data-adicional="4000">Baño Terapéutico / Dermocosmético (Piel Sensible - +$4.000)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="frecuencia_rutina">Plan de Rutina & Hábito del Manto *</label>
                                <select id="frecuencia_rutina">
                                    <option value="Rutina Manto Perfecto (Cada 3 Semanas - 10% OFF)" data-descuento="0.10">Rutina Manto Perfecto (Cada 3 Semanas - 10% OFF)</option>
                                    <option value="Rutina Higiene Regular (Cada 4 Semanas - 5% OFF)" data-descuento="0.05" selected>Rutina Higiene Regular (Cada 4 Semanas - 5% OFF)</option>
                                    <option value="Visita Eventual / Ocasional" data-descuento="0">Visita Eventual / Ocasional (Sin Descuento)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Campos Modo Gato (Rossmari Cat Sitting) -->
                    <div id="container-gato-opciones" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="servicio_felino">Servicio Felino Rossmari *</label>
                                <select id="servicio_felino">
                                    <option value="Cat Sitting / Visita de Cuidado a Domicilio (Rossmari)" data-base="18000">Cat Sitting / Visita de Cuidado a Domicilio (Litera, comida, juego) - Base $18.000</option>
                                    <option value="Cepillado Felino & Deslanado Suave sin Estrés" data-base="22000">Cepillado Felino & Deslanado Suave sin Estrés - Base $22.000</option>
                                    <option value="Combo Multigato (Cat Sitting + Grooming Suave)" data-base="28000">Combo Multigato (Cat Sitting + Grooming Suave) - Base $28.000</option>
                                    <option value="Pack Almitas Felino (Sitting + Bolsón Rubicat 10kg + Envío Gratis)" data-base="35000">Pack Almitas Felino (Sitting + Bolsón Rubicat 10kg + Envío Gratis) - Base $35.000</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cantidad_gatos">Cantidad de Gatos en el Hogar *</label>
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
                                <option value="Visita Única / Eventual" data-descuento="0">Visita Única / Eventual (Sin Descuento)</option>
                                <option value="Pack Diario Viajero (3 a 7 días seguidos - 10% OFF)" data-descuento="0.10">Pack Diario Viajero (3 a 7 días - 10% OFF)</option>
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
                        <label for="manto_descripcion">Diagnostico del Manto & Sensibilidades de la Mascota</label>
                        <textarea id="manto_descripcion" rows="2" placeholder="Ej: Pelo rizado con tendencia a nudos en lomo, piel sensible al secador, le incomoda el corte de uñas..."></textarea>
                    </div>

                    <!-- Live Dynamic Price Calculator Summary Card -->
                    <div id="live-summary-card" class="cart-summary" style="margin-bottom: 1rem; border-color: rgba(16, 185, 129, 0.4); background: rgba(15, 23, 42, 0.9);">
                        <div style="font-size: 0.82rem; font-weight: 700; color: var(--emerald); margin-bottom: 0.4rem; display: flex; align-items: center; justify-content: space-between;">
                            <span>COTIZACIÓN EN VIVO & PLAN DE RUTINA</span>
                            <span class="status-badge" style="background: rgba(16,185,129,0.15); padding: 0.15rem 0.5rem; border-radius: 4px; border: 1px solid rgba(16,185,129,0.3); font-size: 0.75rem;">🟢 Odoo 19 Direct Sync</span>
                        </div>
                        <div id="summary-text-detail" style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5;">
                            Calculando presupuesto estimado...
                        </div>
                    </div>

                    <button type="submit" class="btn-primary btn-whatsapp" id="btn-submit-apt">
                        Reservar Turno en Odoo y Confirmar por WhatsApp
                    </button>
                </form>
            </div>

        </div>

        <?php if ($is_admin): ?>
        <!-- Section: Ficha de Atención Post-Servicio (Uso Interno Groomer & Reporte Cliente) -->
        <section id="ficha-grooming" class="panel-card" style="margin-top: 2rem; border-color: rgba(16, 185, 129, 0.3);">
            <h3 class="panel-title" style="color: var(--emerald);">Ficha Clínica Post-Atención & Reporte de Grooming (Panel Interno)</h3>
            <p class="panel-subtitle">Registra la conducta real, estado de piel/manto encontrado y genera la Ficha de Sesión para el dueño y Odoo 19.</p>

            <form id="post-grooming-form" onsubmit="submitPostGroomingReport(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label for="report_dueno_nombre">Nombre del Dueño/a *</label>
                        <input type="text" id="report_dueno_nombre" required placeholder="Ej: Santiago">
                    </div>
                    <div class="form-group">
                        <label for="report_dueno_telefono">WhatsApp / Teléfono *</label>
                        <input type="tel" id="report_dueno_telefono" required placeholder="Ej: 11 1234-5678">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="report_mascota_nombre">Nombre de la Mascota *</label>
                        <input type="text" id="report_mascota_nombre" required placeholder="Ej: Firulais">
                    </div>
                    <div class="form-group">
                        <label for="report_temperamento">Temperamento & Manejo Conductual *</label>
                        <select id="report_temperamento" required>
                            <option value="Tranquilo / Colaborativo (Excelente comportamiento)">🟢 Tranquilo / Colaborativo (Excelente comportamiento)</option>
                            <option value="Inquieto / Sensible (Requiere paciencia y trabajo pausado)">🟡 Inquieto / Sensible (Requiere paciencia)</option>
                            <option value="Reactivo / Intento de Mordida (Uso de Bozal / Manejo Especial)">🔴 Reactivo / Intento de Mordida (Requiere Bozal / Manejo Especial)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="report_piel_estado">Estado de Piel & Salud Cutánea *</label>
                        <select id="report_piel_estado" required>
                            <option value="Sana / Excelente condición">Sana / Excelente condición</option>
                            <option value="Piel Sensible / Enrojecimiento / Irritación">Piel Sensible / Enrojecimiento / Irritación</option>
                            <option value="Dermatitis / Alergia Cutánea Visible">Dermatitis / Alergia Cutánea Visible</option>
                            <option value="Presencia de Pulgas / Garrapatas (Tratamiento Recomendado)">Presencia de Pulgas / Garrapatas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report_procedimiento">Trabajo Realizado *</label>
                        <select id="report_procedimiento" required>
                            <option value="Baño Profundo + Secado + Higiene Sanitaria">Baño Profundo + Secado + Higiene Sanitaria</option>
                            <option value="Corte de Raza / Tijera Completo + Baño">Corte de Raza / Tijera Completo + Baño</option>
                            <option value="Deslanado Intensivo de Subpelo + Baño">Deslanado Intensivo de Subpelo + Baño</option>
                            <option value="Desanudado Paciente + Corte Higiénico + Baño">Desanudado Paciente + Corte Higiénico + Baño</option>
                            <option value="Baño Terapéutico Dermocosmético">Baño Terapéutico Dermocosmético</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="report_proxima_visita">Recomendación de Próxima Cita *</label>
                        <select id="report_proxima_visita" required>
                            <option value="Volver en 21 días (Ideal para mantenimiento de manto/tijera y prevención de nudos)">En 21 días (Recomendado para manto tijera/anti-nudos)</option>
                            <option value="Volver en 28 días (Frecuencia estándar de higiene regular)">En 28 días (Frecuencia estándar regular)</option>
                            <option value="Volver en 45 días (Mantenimiento básico)">En 45 días (Mantenimiento básico)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report_mordida_recargo">Ajuste / Recargo por Dificultad o Mordida</label>
                        <select id="report_mordida_recargo">
                            <option value="Sin Recargo (Tarifa Estándar)">Sin Recargo (Tarifa Estándar)</option>
                            <option value="Recargo Manejo Especial / Mordida (+$5.000)">Recargo Manejo Especial / Mordida (+$5.000)</option>
                            <option value="Recargo Trabajo Extra Nudos Severos (+$8.000)">Recargo Trabajo Extra Nudos Severos (+$8.000)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="report_observaciones">Recomendaciones Pedagógicas & Tips para el Dueño</label>
                    <textarea id="report_observaciones" rows="3" placeholder="Ej: Recomendamos cepillar con carda suave 5 minutos por día en zona de orejas y lomo para evitar nudos. Se portó muy bien con el secador pero requiere bozal preventivo al cortar uñas."></textarea>
                </div>

                <button type="submit" class="btn-primary btn-whatsapp" id="btn-submit-report" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    Guardar Ficha en Odoo 19 y Enviar Reporte al Dueño por WhatsApp
                </button>
            </form>
        </section>

        <!-- Marketing & Copys Section (Clean Text - No Emojis) -->
        <section id="copys" class="copy-section">
            <h3 class="panel-title">Centro de Difusión y Copys de Redes (Panel Interno)</h3>
            <p class="panel-subtitle">Textos promocionales limpios sin emojis listos para publicar en Instagram, Facebook y WhatsApp.</p>

            <div class="copy-tabs">
                <button class="tab-btn active" onclick="showCopyTab('tab1')">Post Feed (IG/FB)</button>
                <button class="tab-btn" onclick="showCopyTab('tab2')">Historias / Estados</button>
                <button class="tab-btn" onclick="showCopyTab('tab3')">Mensaje Difusión WhatsApp</button>
            </div>

            <div id="tab1" class="copy-tab-content">
                <div class="copy-box" id="copy-text-1">!Abrimos pedido de alimentos y sanitarios en Almitas Peludas!

Llego el momento de reponer el alimento y las piedritas para tus peludos. Coordinamos pedido mayorista directo para ofrecerte los mejores precios y llevarlo a tu puerta.

DESTACADOS DE ESTA SEMANA:
- CatPro Gatos Indoor / Castrados (7.5 kg) - Nutricion balanceada y control de peso.
- Piedras Sanitarias Rubicat Premium (10 kg) - Aglomerante superior y maximo control de olores.
- Alimentos para perros y pipetas antipulgas (consulta por tu marca habitual).

COMO ENCARGAR:
Escribinos por mensaje directo o WhatsApp indicando que alimento o producto necesitas.
Tomamos pedidos hasta este Viernes a las 18 hs para entregar la proxima semana.

Sumate al pedido y me aseguras el stock de tu mascota.</div>
                <button class="btn-copy" onclick="copyText('copy-text-1')">Copiar Post Feed</button>
            </div>

            <div id="tab2" class="copy-tab-content" style="display:none;">
                <div class="copy-box" id="copy-text-2">REPOSICION DE STOCK EN ALMITAS PELUDAS
Te estas quedando sin alimento o piedritas?
Abrimos pedido de la semana. Asegura el tuyo antes de que cerremos la compra el Viernes a las 18 hs.

OFERTA DESTACADA GATOS:
- CatPro Castrados / Indoor 7.5 kg
- Piedras Rubicat Premium 10 kg (Bolsa Naranja Aglomerante)
Mandanos un WhatsApp para reservar tu bolsa.</div>
                <button class="btn-copy" onclick="copyText('copy-text-2')">Copiar Texto Historias</button>
            </div>

            <div id="tab3" class="copy-tab-content" style="display:none;">
                <div class="copy-box" id="copy-text-3">Hola! Como estas? Te escribimos desde Almitas Peludas.

Estamos armando el pedido mayorista de alimentos y productos de higiene Morquis de esta semana. Si necesitas reponer alimento, piedritas sanitarias (como Rubicat) o antipulgas, avisanos antes del Viernes a las 18:00 hs y te lo sumamos al pedido con entrega directa.

Decinos que marca y presentacion usas y te pasamos el precio actualizado.</div>
                <button class="btn-copy" onclick="copyText('copy-text-3')">Copiar Mensaje Difusion</button>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Toast Notification -->
    <div id="toast" class="toast">Texto copiado al portapapeles.</div>

    <footer style="text-align:center; padding: 2rem 1.5rem; border-top: 1px solid var(--border-muted); margin-top: 3rem; display:flex; flex-direction:column; align-items:center; gap: 0.75rem;">
        <div style="display:flex; gap: 1.5rem; justify-content:center; align-items:center; flex-wrap:wrap;">
            <a href="https://www.instagram.com/almitaspeludas.ok/" target="_blank" rel="noopener" style="color: var(--gold); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.4rem;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg> @almitaspeludas.ok
            </a>
            <a href="https://www.facebook.com/profile.php?id=61583019582155" target="_blank" rel="noopener" style="color: var(--gold); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.4rem;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Facebook Oficial
            </a>
        </div>
        <p style="color: var(--text-muted); font-size: 0.85rem;">&copy; <?= date('Y') ?> Almitas Peludas &bull; Peluquería Canina a Domicilio CABA &bull; Gestión Odoo 19 Enterprise</p>
    </footer>

    <!-- Load Morquis Catalog JS -->
    <script src="morquis_catalog.js"></script>
    <script>
        let currentCategory = 'ALL';
        const userCart = {}; // { index: qty }

        function filterCategory(cat, element) {
            currentCategory = cat;
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
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
                filtered = filtered.filter(p => 
                    p.name.toLowerCase().includes(search) || 
                    p.brand.toLowerCase().includes(search)
                );
            }

            const displayItems = filtered.slice(0, 45);

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
                            <p>${item.brand ? 'Marca: ' + item.brand + ' - ' : ''}${item.category}</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div class="product-price">${formattedPrice}</div>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateItemQty(${itemIndex}, -1)">-</button>
                                <span class="qty-val">${qty}</span>
                                <button class="qty-btn" onclick="updateItemQty(${itemIndex}, 1)">+</button>
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
                        <div class="cart-line-item">
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
                alert('Por favor ingresa tu nombre y telefono.');
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
                alert('Selecciona al menos 1 producto del catalogo.');
                return;
            }

            // 1. Registrar en Odoo via API
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
            } catch (e) {
                console.warn('Error enviando a Odoo:', e);
            }

            // 2. Enviar a WhatsApp (Clean text - NO Emojis)
            let text = `NUEVO PEDIDO MAYORISTA - ALMITAS PELUDAS\n`;
            text += `------------------------------------\n`;
            text += `Cliente: ${name}\n`;
            text += `Telefono: ${phone}\n`;
            text += `Direccion: ${address || 'A confirmar'}\n\n`;
            text += `Productos:\n`;
            items.forEach(i => {
                text += `- ${i.qty}x ${i.name} ($${(i.qty * i.price).toLocaleString('es-AR')})\n`;
            });
            text += `\nTOTAL ESTIMADO: $${total.toLocaleString('es-AR')}`;

            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }

        let currentEspecie = 'perro';

        function setEspecie(esp) {
            currentEspecie = esp;
            const btnPerro = document.getElementById('btn-especie-perro');
            const btnGato = document.getElementById('btn-especie-gato');
            const rossmariCard = document.getElementById('rossmari-card');
            const perroContainer = document.getElementById('container-perro-opciones');
            const gatoContainer = document.getElementById('container-gato-opciones');

            if (esp === 'gato') {
                btnPerro.classList.remove('active');
                btnGato.classList.add('active');
                rossmariCard.style.display = 'block';
                perroContainer.style.display = 'none';
                gatoContainer.style.display = 'block';
            } else {
                btnGato.classList.remove('active');
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
                    basePrice,
                    adicionalManto: adicionalGatos,
                    adicionalTratamiento: 0,
                    viaticoZona,
                    descPct,
                    descuento,
                    subtotal,
                    total,
                    tamanoTxt: getSelectValue('cantidad_gatos', '1 Gato en Casa'),
                    mantoTxt: 'Cuidado Felino sin Estrés (Rossmari)',
                    tratamientoTxt: getSelectValue('servicio_felino', 'Cat Sitting a Domicilio'),
                    zonaTxt,
                    rutinaTxt: getSelectValue('frecuencia_felina', 'Visita Única / Eventual')
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
                    basePrice,
                    adicionalManto,
                    adicionalTratamiento,
                    viaticoZona,
                    descPct,
                    descuento,
                    subtotal,
                    total,
                    tamanoTxt: getSelectValue('mascota_tamano', ''),
                    mantoTxt: getSelectValue('manto_estado', ''),
                    tratamientoTxt: getSelectValue('tratamiento_servicio', ''),
                    zonaTxt,
                    rutinaTxt: getSelectValue('frecuencia_rutina', '')
                };
            }
        }

        async function submitAppointment(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-apt');
            btn.disabled = true;
            btn.innerText = 'Enviando a Odoo...';

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
                    showToast('Turno registrado en Odoo 19. Abriendo WhatsApp para confirmación instantánea...');

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
                        text += `Direccion: ${payload.direccion}\n\n`;
                        text += `COTIZACION ESTIMADA:\n`;
                        text += `- Presupuesto Base: $${payload.subtotal.toLocaleString('es-AR')}\n`;
                        if (payload.descuento_aplicado > 0) text += `- Descuento Pack Viajero: -$${payload.descuento_aplicado.toLocaleString('es-AR')}\n`;
                        text += `TOTAL ESTIMADO: $${payload.total_cotizado.toLocaleString('es-AR')}\n\n`;
                        text += `Solicito confirmación de la visita por Rossmari por este medio.`;
                    } else {
                        text = `RESERVA DE TURNO Y DIAGNOSTICO DE MANTO - ALMITAS PELUDAS\n`;
                        text += `------------------------------------\n`;
                        text += `Cliente: ${payload.dueno_nombre} (${payload.telefono})\n`;
                        text += `Mascota: ${payload.mascota_nombre} (${payload.mascota_raza || 'Mestizo'})\n`;
                        text += `Tamaño: ${payload.mascota_tamano}\n`;
                        text += `Estado Manto: ${payload.manto_estado}\n`;
                        text += `Tratamiento: ${payload.tratamiento_servicio}\n`;
                        text += `Plan de Rutina: ${payload.frecuencia_rutina}\n`;
                        if (payload.manto_descripcion) text += `Notas Manto: ${payload.manto_descripcion}\n`;
                        text += `Zona: ${payload.zona_atencion}\n`;
                        text += `Fecha: ${payload.fecha_turno} (${payload.horario_turno})\n`;
                        text += `Direccion: ${payload.direccion}\n\n`;
                        text += `COTIZACION ESTIMADA:\n`;
                        text += `- Presupuesto Base: $${payload.subtotal.toLocaleString('es-AR')}\n`;
                        if (payload.descuento_aplicado > 0) text += `- Descuento Plan Recurrente: -$${payload.descuento_aplicado.toLocaleString('es-AR')}\n`;
                        text += `TOTAL ESTIMADO: $${payload.total_cotizado.toLocaleString('es-AR')}\n\n`;
                        text += `Solicito confirmación del turno por este medio.`;
                    }

                    const waUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
                    setTimeout(() => window.open(waUrl, '_blank'), 600);

                    document.getElementById('appointment-form').reset();
                    updateLiveSummary();
                } else {
                    alert(data.error || 'Error registrando turno.');
                }
            } catch (err) {
                alert('Error conectando con la API.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Reservar Turno en Odoo y Confirmar por WhatsApp';
            }
        }

        async function submitPostGroomingReport(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-report');
            btn.disabled = true;
            btn.innerText = 'Guardando Ficha en Odoo...';

            const payload = {
                action: 'save_post_grooming_report',
                dueno_nombre: document.getElementById('report_dueno_nombre').value.trim(),
                telefono: document.getElementById('report_dueno_telefono').value.trim(),
                mascota_nombre: document.getElementById('report_mascota_nombre').value.trim(),
                temperamento: document.getElementById('report_temperamento').value,
                piel_estado: document.getElementById('report_piel_estado').value,
                procedimiento: document.getElementById('report_procedimiento').value,
                proxima_visita: document.getElementById('report_proxima_visita').value,
                recargo_dificultad: document.getElementById('report_mordida_recargo').value,
                observaciones: document.getElementById('report_observaciones').value.trim()
            };

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    showToast('Ficha de atención registrada en Odoo 19. Abriendo WhatsApp para enviar reporte al cliente...');

                    // Generar reporte educativo y profesional para el dueño (Clean text - NO Emojis)
                    let text = `FICHA DE ATENCION DE GROOMING - ALMITAS PELUDAS\n`;
                    text += `----------------------------------------------\n`;
                    text += `Mascota: ${payload.mascota_nombre}\n`;
                    text += `Tutor/a: ${payload.dueno_nombre}\n\n`;
                    text += `RESUMEN DE LA SESION:\n`;
                    text += `- Trabajo Realizado: ${payload.procedimiento}\n`;
                    text += `- Estado de Piel: ${payload.piel_estado}\n`;
                    text += `- Comportamiento: ${payload.temperamento.split(' (')[0]}\n`;
                    if (payload.recargo_dificultad && !payload.recargo_dificultad.includes('Sin Recargo')) {
                        text += `- Observación de Manejo: ${payload.recargo_dificultad}\n`;
                    }
                    text += `\nRECOMENDACION DE PROXIMA CITA:\n`;
                    text += `- ${payload.proxima_visita}\n`;

                    if (payload.observaciones) {
                        text += `\nTIPS & CUIDADOS EN CASA:\n`;
                        text += `${payload.observaciones}\n`;
                    }

                    text += `\nGracias por confiar el cuidado de ${payload.mascota_nombre} a Almitas Peludas. ¡Nos vemos en la próxima sesión!`;

                    const waUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
                    setTimeout(() => window.open(waUrl, '_blank'), 600);

                    document.getElementById('post-grooming-form').reset();
                } else {
                    alert(data.error || 'Error guardando reporte.');
                }
            } catch (err) {
                alert('Error conectando con la API.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Guardar Ficha en Odoo 19 y Enviar Reporte al Dueño por WhatsApp';
            }
        }

        function showCopyTab(tabId, evt) {
            document.querySelectorAll('.copy-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
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
            const dueno = document.getElementById('dueno_nombre')?.value.trim() || 'Cliente';
            const mascota = document.getElementById('mascota_nombre')?.value.trim() || 'Mascota';
            const fecha = document.getElementById('fecha_turno')?.value || '';
            const horario = document.getElementById('horario_turno')?.value || '';

            const quote = calculateAppointmentQuote();

            const summaryEl = document.getElementById('summary-text-detail');
            if (summaryEl) {
                summaryEl.innerHTML = `
                    <strong>Reserva:</strong> ${dueno} &bull; <strong>Mascota:</strong> ${mascota}<br>
                    <strong>Diagnóstico & Servicio:</strong> ${quote.tamanoTxt.split(' - ')[0]} &bull; ${quote.mantoTxt.split(' (')[0]} &bull; ${quote.tratamientoTxt.split(' (')[0]}<br>
                    <strong>Zona:</strong> ${quote.zonaTxt.split(' (')[0]} (Viático: $${quote.viaticoZona.toLocaleString('es-AR')})<br>
                    <strong>Plan de Rutina:</strong> ${quote.rutinaTxt}${quote.descuento > 0 ? ' (Descuento: -$' + quote.descuento.toLocaleString('es-AR') + ')' : ''}<br>
                    <div style="margin-top: 0.5rem; padding-top: 0.4rem; border-top: 1px dashed rgba(255,255,255,0.15); display:flex; justify-content:space-between; align-items:center;">
                        <span>Fecha: <strong>${fecha ? fecha : 'Por definir'} (${horario})</strong></span>
                        <span style="font-size: 1.1rem; font-weight: 800; color: var(--gold);">TOTAL ESTIMADO: $${quote.total.toLocaleString('es-AR')}</span>
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderCatalog();

            // Bind live reservation summary listeners (Peirano pattern)
            const inputs = ['dueno_nombre', 'mascota_nombre', 'mascota_raza', 'direccion', 'zona_atencion', 'mascota_tamano', 'manto_estado', 'tratamiento_servicio', 'frecuencia_rutina', 'servicio_felino', 'cantidad_gatos', 'frecuencia_felina', 'fecha_turno', 'horario_turno'];
            inputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateLiveSummary);
                    el.addEventListener('change', updateLiveSummary);
                }
            });

            // Initial summary calculation
            updateLiveSummary();

            // Sunday validation listener
            document.getElementById('fecha_turno')?.addEventListener('change', function() {
                if (!this.value) return;
                const chosenDate = new Date(this.value + 'T00:00:00');
                if (chosenDate.getDay() === 0) { // 0 = Domingo
                    alert('Atención: Los domingos no realizamos atención a domicilio. Por favor selecciona una fecha de lunes a sábado.');
                    this.value = '';
                    updateLiveSummary();
                }
            });
        });
    </script>
</body>
</html>
