<?php
require_once __DIR__ . '/../includes/odoo_api.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almitas Peludas — Peluquería Canina a Domicilio & Pedidos Mayoristas</title>
    <meta name="description" content="Peluquería & Estética Canina a Domicilio. Sumate al pedido mayorista de alimentos CatPro, piedritas Rubicat y accesorios para tus mascotas.">
    <meta name="theme-color" content="#0f172a">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #0f172a;
            --bg-alt: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.85);
            --card-border: rgba(251, 191, 36, 0.15);
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
            background: rgba(15, 23, 42, 0.9);
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

        .brand-icon {
            font-size: 1.8rem;
            filter: drop-shadow(0 0 10px var(--gold-glow));
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .brand-name span {
            color: var(--gold);
        }

        nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
        }

        nav a:hover {
            color: var(--gold);
        }

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
            padding: 2.5rem 1.5rem 5rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 3.5rem;
        }

        /* Hero Banner */
        .hero {
            text-align: center;
            max-width: 850px;
            margin: 0 auto;
            padding: 1.5rem 0 0.5rem 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.3);
            color: var(--gold);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 30%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-secondary);
            max-width: 680px;
            margin: 0 auto;
        }

        /* Wholesaler Progress Banner Card */
        .goal-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid var(--gold);
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.15);
            border-radius: var(--radius-lg);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .goal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--emerald) 100%);
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
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .goal-title p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .goal-stats {
            text-align: right;
        }

        .goal-amount {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
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
            position: relative;
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
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Grid Layout */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
        }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        /* Card Panels */
        .panel-card {
            background: var(--card-bg);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
        }

        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .panel-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        /* Products Grid */
        .products-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .product-item {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-md);
            padding: 1rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            transition: var(--transition);
        }

        .product-item:hover {
            border-color: rgba(251, 191, 36, 0.4);
            background: rgba(30, 41, 59, 0.8);
        }

        .product-info h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        .product-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .product-price {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--gold);
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--border-muted);
            border-radius: 8px;
            padding: 0.2rem 0.5rem;
        }

        .qty-btn {
            background: none;
            border: none;
            color: var(--gold);
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: rgba(251, 191, 36, 0.2);
        }

        .qty-val {
            font-weight: 700;
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
        }

        /* Cart Summary */
        .cart-summary {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 1.2rem;
            margin-top: auto;
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            font-family: 'Outfit', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .cart-total-row span:last-child {
            color: var(--gold);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid var(--border-muted);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.95rem;
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
            padding: 0.95rem;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
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

        .btn-whatsapp:hover {
            box-shadow: 0 8px 25px var(--emerald-glow);
        }

        /* Marketing & Copy Section */
        .copy-section {
            background: var(--card-bg);
            border: 1px solid var(--border-muted);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .copy-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
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
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
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
            padding: 1.5rem;
            font-family: monospace, monospace;
            font-size: 0.9rem;
            color: var(--text-secondary);
            white-space: pre-wrap;
            position: relative;
            margin-bottom: 1rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .btn-copy {
            align-self: flex-start;
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border-muted);
            color: var(--text);
            border-radius: 6px;
            font-size: 0.85rem;
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

        /* Alert Toast */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #10b981;
            color: #ffffff;
            padding: 1rem 1.5rem;
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
            padding: 2.5rem 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>

    <!-- Top Header -->
    <header>
        <div class="header-inner">
            <a href="/almitas" class="brand">
                <span class="brand-icon">🐾</span>
                <span class="brand-name">Almitas <span>Peludas</span></span>
            </a>
            <nav>
                <a href="#pedido-mayorista">📦 Pedido Mayorista</a>
                <a href="#turnera">✂️ Turnera</a>
                <a href="#copys">📲 Copys Difusión</a>
                <a href="#turnera" class="nav-btn">Reservar Turno</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-badge">✨ Peluquería Canina a Domicilio & Alimentos Mayoristas</div>
            <h1>El mejor cuidado para tu mascota, directo en tu hogar</h1>
            <p>Coordinamos la visita para estética canina sin estrés y armamos pedidos semanales de alimentos y sanitarios a precios diferencial.</p>
        </section>

        <!-- Wholesaler Progress Banner -->
        <section id="pedido-mayorista" class="goal-card">
            <div class="goal-header">
                <div class="goal-title">
                    <h2>📦 Pedido Mayorista Semanal (Alimentos & Piedritas)</h2>
                    <p>Alcanzando la cuota mínima del proveedor ($150.000) para obtener precio directo de fábrica.</p>
                </div>
                <div class="goal-stats">
                    <div class="goal-amount" id="goal-current-text">$58.680</div>
                    <div class="goal-target">Meta Mínima: $150.000</div>
                </div>
            </div>

            <div class="progress-track">
                <div class="progress-fill" id="goal-fill" style="width: 39.1%;"></div>
                <div class="progress-text" id="goal-percent-text">39.1% Completado ($58.680)</div>
            </div>

            <div class="goal-footer">
                <div>Falta reunir: <strong style="color: var(--text);" id="goal-remaining-text">$91.320</strong> en pedidos adicionales.</div>
                <div class="remaining-badge">🚚 Entrega a domicilio la próxima semana</div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="main-grid">
            
            <!-- Panel 1: Catálogo Interactivo de Alimentos -->
            <div class="panel-card">
                <h3 class="panel-title">🛒 Catálogo & Pedido Mayorista</h3>
                <p class="panel-subtitle">Seleccioná los productos que necesitás para tu mascota y sumalos al pedido semanal.</p>

                <div class="products-list">
                    
                    <div class="product-item">
                        <div class="product-info">
                            <h4>CatPro Castrados / Indoor (7.5 kg)</h4>
                            <p>Nutrición balanceada y control de peso para gatos</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <div class="product-price">$34.500</div>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty('catpro', -1)">-</button>
                                <span class="qty-val" id="qty-catpro">0</span>
                                <button class="qty-btn" onclick="updateQty('catpro', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-item">
                        <div class="product-info">
                            <h4>Rubicat Premium (10 kg - Bolsa Naranja)</h4>
                            <p>Piedras sanitarias aglomerantes superior</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <div class="product-price">$12.090</div>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty('rubicat', -1)">-</button>
                                <span class="qty-val" id="qty-rubicat">0</span>
                                <button class="qty-btn" onclick="updateQty('rubicat', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-item" style="border-color: rgba(251, 191, 36, 0.3);">
                        <div class="product-info">
                            <h4>Pack Promo "Peludo Equipado" ⭐</h4>
                            <p>1x CatPro 7.5kg + 1x Rubicat Premium 10kg</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <div class="product-price">$43.900</div>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty('pack_promo', -1)">-</button>
                                <span class="qty-val" id="qty-pack_promo">0</span>
                                <button class="qty-btn" onclick="updateQty('pack_promo', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-item">
                        <div class="product-info">
                            <h4>Pipeta Antipulgas Canina / Felina</h4>
                            <p>Protección contra pulgas y garrapatas (por dosis)</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <div class="product-price">$8.500</div>
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateQty('pipeta', -1)">-</button>
                                <span class="qty-val" id="qty-pipeta">0</span>
                                <button class="qty-btn" onclick="updateQty('pipeta', 1)">+</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="cart-summary">
                    <div class="cart-total-row">
                        <span>Total de tu pedido:</span>
                        <span id="cart-total-price">$0</span>
                    </div>

                    <div class="form-group">
                        <input type="text" id="order-name" placeholder="Tu Nombre Completo *">
                    </div>
                    <div class="form-group">
                        <input type="tel" id="order-phone" placeholder="Teléfono / WhatsApp *">
                    </div>
                    <div class="form-group">
                        <input type="text" id="order-address" placeholder="Dirección para la entrega *">
                    </div>

                    <button class="btn-primary btn-whatsapp" onclick="submitWholesaleOrder()">
                        📲 Enviar Pedido por WhatsApp
                    </button>
                </div>
            </div>

            <!-- Panel 2: Turnera Peluquería Canina a Domicilio -->
            <div class="panel-card" id="turnera">
                <h3 class="panel-title">✂️ Turnera a Domicilio</h3>
                <p class="panel-subtitle">Agendá una visita de peluquería y baño sin mover a tu perro de casa.</p>

                <form id="appointment-form" onsubmit="submitAppointment(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dueno_nombre">Nombre del Dueño/a *</label>
                            <input type="text" id="dueno_nombre" required placeholder="Ej: Santiago">
                        </div>
                        <div class="form-group">
                            <label for="telefono">WhatsApp / Teléfono *</label>
                            <input type="tel" id="telefono" required placeholder="Ej: 11 1234-5678">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mascota_nombre">Nombre de la Mascota *</label>
                            <input type="text" id="mascota_nombre" required placeholder="Ej: Firulais">
                        </div>
                        <div class="form-group">
                            <label for="mascota_raza">Raza / Tamaño</label>
                            <input type="text" id="mascota_raza" placeholder="Ej: Caniche / Mediano">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="direccion">Dirección de Visita *</label>
                            <input type="text" id="direccion" required placeholder="Ej: Av. Corrientes 1234">
                        </div>
                        <div class="form-group">
                            <label for="barrio_zona">Barrio / Zona *</label>
                            <input type="text" id="barrio_zona" placeholder="Ej: Palermo / Belgrano">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="servicio">Servicio Requerido *</label>
                        <select id="servicio" required>
                            <option value="Peluquería Canina Completa ($25.000)">Peluquería Canina Completa ($25.000)</option>
                            <option value="Baño & Higiene Profunda ($18.000)">Baño & Higiene Profunda ($18.000)</option>
                            <option value="Corte de Uñas & Desparasitación ($10.000)">Corte de Uñas & Desparasitación ($10.000)</option>
                        </select>
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
                        <label for="notas">Notas (ej: miedoso, requiere bozal, etc.)</label>
                        <textarea id="notas" rows="2" placeholder="Detalles sobre el carácter de la mascota..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary" id="btn-submit-apt">
                        📅 Reservar Turno en Odoo
                    </button>
                </form>
            </div>

        </div>

        <!-- Marketing & Copys Section -->
        <section id="copys" class="copy-section">
            <h3 class="panel-title">📲 Centro de Difusión & Copys de Redes</h3>
            <p class="panel-subtitle">Textos promocionales listos para publicar en Instagram, Facebook y enviar por grupos de WhatsApp.</p>

            <div class="copy-tabs">
                <button class="tab-btn active" onclick="showCopyTab('tab1')">Post Feed (IG/FB)</button>
                <button class="tab-btn" onclick="showCopyTab('tab2')">Historias / Estados</button>
                <button class="tab-btn" onclick="showCopyTab('tab3')">Mensaje Difusión WhatsApp</button>
            </div>

            <div id="tab1" class="copy-tab-content">
                <div class="copy-box" id="copy-text-1">¡Abrimos pedido de alimentos y sanitarios en Almitas Peludas! 🐾📦

Llegó el momento de reponer el alimento y las piedritas para tus peludos. Coordinamos pedido mayorista directo para ofrecerte los mejores precios y llevarlo a tu puerta.

🌟 Destacados de esta semana:
🐱 CatPro Gatos Indoor / Castrados (7.5 kg) – Nutrición balanceada y control de peso.
🍊 Piedras Sanitarias Rubicat Premium (10 kg) – Aglomerante superior y máximo control de olores.
🐶 Alimentos para perros y pipetas antipulgas (consultá por tu marca habitual).

📲 ¿Cómo encargar?
Escribinos por mensaje directo o WhatsApp indicando qué alimento o producto necesitás.
⏰ Tomamos pedidos hasta este Viernes a las 18 hs para entregar la próxima semana.

¡Sumate al pedido y asegurá el stock de tu mascota! ❤️</div>
                <button class="btn-copy" onclick="copyText('copy-text-1')">📋 Copiar Post Feed</button>
            </div>

            <div id="tab2" class="copy-tab-content" style="display:none;">
                <div class="copy-box" id="copy-text-2">🐾 ¡REPOSICIÓN DE STOCK EN ALMITAS PELUDAS! 📦
¿Te estás quedando sin alimento o piedritas?
Abrimos pedido de la semana. ¡Asegurá el tuyo antes de que cerremos la compra! 👇

🐱 OFERTA DESTACADA GATOS:
• CatPro Castrados / Indoor 7.5 kg
• Piedras Rubicat Premium 10 kg (Bolsa Naranja Aglomerante)
📲 Mándanos un WhatsApp para reservar tu bolsa.</div>
                <button class="btn-copy" onclick="copyText('copy-text-2')">📋 Copiar Texto Historias</button>
            </div>

            <div id="tab3" class="copy-tab-content" style="display:none;">
                <div class="copy-box" id="copy-text-3">Hola! 👋 ¿Cómo estás? Te escribimos desde Almitas Peludas.

Estamos armando el pedido mayorista de alimentos y productos de higiene de esta semana. Si necesitás reponer alimento, piedritas sanitarias (como Rubicat) o antipulgas, avísanos y te lo sumamos al pedido con entrega directa.

Decinos qué marca y presentación usás y te pasamos el precio actualizado. 🐾🛒</div>
                <button class="btn-copy" onclick="copyText('copy-text-3')">📋 Copiar Mensaje Difusión</button>
            </div>
        </section>
    </main>

    <!-- Toast Notification -->
    <div id="toast" class="toast">¡Texto copiado al portapapeles!</div>

    <footer>
        <p>&copy; <?= date('Y') ?> Almitas Peludas — Peluquería Canina a Domicilio & Gestión ERP Odoo 19 Enterprise.</p>
    </footer>

    <script>
        const PRODUCTS = {
            catpro:     { name: 'CatPro Castrados/Indoor 7.5kg', price: 34500 },
            rubicat:    { name: 'Rubicat Premium 10kg (Naranja)', price: 12090 },
            pack_promo: { name: 'Pack Promo (CatPro + Rubicat)', price: 43900 },
            pipeta:     { name: 'Pipeta Antipulgas', price: 8500 }
        };

        const cart = { catpro: 0, rubicat: 0, pack_promo: 0, pipeta: 0 };

        function updateQty(key, delta) {
            cart[key] = Math.max(0, (cart[key] || 0) + delta);
            document.getElementById(`qty-${key}`).innerText = cart[key];
            calculateCartTotal();
        }

        function calculateCartTotal() {
            let total = 0;
            for (const key in cart) {
                total += cart[key] * PRODUCTS[key].price;
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
                alert('Por favor ingresá tu nombre y teléfono.');
                return;
            }

            const items = [];
            for (const key in cart) {
                if (cart[key] > 0) {
                    items.push({
                        name: PRODUCTS[key].name,
                        qty: cart[key],
                        price: PRODUCTS[key].price
                    });
                }
            }

            if (items.length === 0) {
                alert('Seleccioná al menos 1 producto para armar tu pedido.');
                return;
            }

            // 1. Enviar a Odoo vía API
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

            // 2. Formatear mensaje WhatsApp
            let text = `🐾 *NUEVO PEDIDO MAYORISTA — ALMITAS PELUDAS*\n`;
            text += `------------------------------------\n`;
            text += `*Cliente:* ${name}\n`;
            text += `*Teléfono:* ${phone}\n`;
            text += `*Dirección:* ${address || 'A confirmar'}\n\n`;
            text += `*Productos:*\n`;
            items.forEach(i => {
                text += `• ${i.qty}x ${i.name} ($${(i.qty * i.price).toLocaleString('es-AR')})\n`;
            });
            text += `\n*TOTAL ESTIMADO:* $${total.toLocaleString('es-AR')}`;

            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }

        async function submitAppointment(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-apt');
            btn.disabled = true;
            btn.innerText = 'Enviando a Odoo...';

            const payload = {
                action: 'create_appointment',
                dueno_nombre: document.getElementById('dueno_nombre').value,
                telefono: document.getElementById('telefono').value,
                mascota_nombre: document.getElementById('mascota_nombre').value,
                mascota_raza: document.getElementById('mascota_raza').value,
                direccion: document.getElementById('direccion').value,
                barrio_zona: document.getElementById('barrio_zona').value,
                servicio: document.getElementById('servicio').value,
                fecha_turno: document.getElementById('fecha_turno').value,
                horario_turno: document.getElementById('horario_turno').value,
                notas: document.getElementById('notas').value
            };

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    showToast('¡Turno registrado en Odoo! Nos comunicaremos por WhatsApp.');
                    document.getElementById('appointment-form').reset();
                } else {
                    alert(data.error || 'Error registrando turno.');
                }
            } catch (err) {
                alert('Error conectando con la API.');
            } finally {
                btn.disabled = false;
                btn.innerText = '📅 Reservar Turno en Odoo';
            }
        }

        function showCopyTab(tabId) {
            document.querySelectorAll('.copy-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            event.target.classList.add('active');
        }

        function copyText(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                showToast('¡Texto copiado al portapapeles!');
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>
</body>
</html>
