<?php
require_once __DIR__ . '/../includes/odoo_api.php';

$appointment_created = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dueno_nombre   = trim($_POST['dueno_nombre'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $telefono       = trim($_POST['telefono'] ?? '');
    $direccion      = trim($_POST['direccion'] ?? '');
    $barrio_zona    = trim($_POST['barrio_zona'] ?? '');
    $mascota_nombre = trim($_POST['mascota_nombre'] ?? '');
    $mascota_raza   = trim($_POST['mascota_raza'] ?? '');
    $servicio       = trim($_POST['servicio'] ?? 'Peluquería Canina a Domicilio');
    $fecha_turno    = trim($_POST['fecha_turno'] ?? '');
    $horario_turno  = trim($_POST['horario_turno'] ?? '');
    $notas          = trim($_POST['notas'] ?? '');

    if (!empty($dueno_nombre) && !empty($telefono) && !empty($direccion) && !empty($fecha_turno)) {
        try {
            // 1. Crear / Buscar Cliente en Odoo (Almitas Peludas - Company ID 3)
            $partner_id = odoo(
                'res.partner',
                'create',
                [[
                    'name'         => $dueno_nombre,
                    'email'        => $email,
                    'phone'        => $telefono,
                    'street'       => $direccion,
                    'city'         => $barrio_zona,
                    'comment'      => "Mascota: $mascota_nombre ($mascota_raza)",
                    'company_id'   => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            // 2. Registrar Oportunidad / Turno en CRM Odoo
            $lead_desc = "🐾 TURNO PELUQUERÍA CANINA A DOMICILIO\n"
                       . "----------------------------------------\n"
                       . "Mascota: $mascota_nombre ($mascota_raza)\n"
                       . "Servicio: $servicio\n"
                       . "Fecha Solicitada: $fecha_turno ($horario_turno)\n"
                       . "Dirección de Visita: $direccion, $barrio_zona\n"
                       . "Notas adicionales: $notas\n";

            $lead_id = odoo(
                'crm.lead',
                'create',
                [[
                    'name'         => "Turno $mascota_nombre — $fecha_turno $horario_turno",
                    'partner_id'   => $partner_id,
                    'contact_name' => $dueno_nombre,
                    'email_from'   => $email,
                    'phone'        => $telefono,
                    'description'  => $lead_desc,
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            $appointment_created = true;
        } catch (Throwable $e) {
            $error_msg = "Error registrando turno en Odoo: " . $e->getMessage();
        }
    } else {
        $error_msg = "Por favor completá los campos obligatorios (Nombre, Teléfono, Dirección y Fecha).";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almitas Peludas — Peluquería & Estética Canina a Domicilio</title>
    <meta name="description" content="Turnera online para el cuidado y peluquería canina a domicilio. Reservá tu turno de forma fácil y rápida.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.85);
            --border-color: rgba(51, 65, 85, 0.8);
            --primary: #fbbf24; /* Calido dorado / pet friendly */
            --primary-glow: rgba(251, 191, 36, 0.2);
            --accent: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
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
            background: rgba(15, 23, 42, 0.9);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-container {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
        }
        .logo span { color: var(--primary); }
        .hero {
            padding: 3.5rem 1.5rem 2rem 1.5rem;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #ffffff 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
        }
        .booking-container {
            max-width: 680px;
            margin: 0 auto 4rem auto;
            width: 100%;
            padding: 0 1.5rem;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }
        input, select, textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 0.95rem;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        button[type="submit"] {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #0f172a;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }
        .alert {
            padding: 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid var(--success); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; }
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
        <div class="header-container">
            <a href="/almitas" class="logo">🐾 Almitas <span>Peludas</span></a>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>Turnera de Estética Canina a Domicilio</h1>
            <p>Coordinamos la visita directamente en tu hogar para la comodidad de tu mascota.</p>
        </section>

        <section class="booking-container">
            <div class="card">
                <h2 class="card-title">Reserva de Turno a Domicilio</h2>

                <?php if ($appointment_created): ?>
                    <div class="alert alert-success">
                        ¡Turno registrado con éxito! Nos comunicaremos con vos para confirmar la hora exacta de la visita a tu domicilio.
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dueno_nombre">Nombre del Dueño/a *</label>
                            <input type="text" id="dueno_nombre" name="dueno_nombre" required placeholder="Ej: Santiago">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp *</label>
                            <input type="tel" id="telefono" name="telefono" required placeholder="Ej: 11 1234-5678">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mascota_nombre">Nombre de la Mascota *</label>
                            <input type="text" id="mascota_nombre" name="mascota_nombre" required placeholder="Ej: Firulais">
                        </div>
                        <div class="form-group">
                            <label for="mascota_raza">Raza / Tamaño</label>
                            <input type="text" id="mascota_raza" name="mascota_raza" placeholder="Ej: Caniche / Canino mediano">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="direccion">Dirección Completa *</label>
                            <input type="text" id="direccion" name="direccion" required placeholder="Ej: Calle 123 Nro 456">
                        </div>
                        <div class="form-group">
                            <label for="barrio_zona">Barrio / Zona *</label>
                            <input type="text" id="barrio_zona" name="barrio_zona" placeholder="Ej: Recoleta / Palermo / Belgrano">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="servicio">Servicio Requerido *</label>
                        <select id="servicio" name="servicio" required>
                            <option value="Peluquería Canina Completa ($25.000)">Peluquería Canina Completa ($25.000)</option>
                            <option value="Baño & Higiene ($18.000)">Baño & Higiene ($18.000)</option>
                            <option value="Corte de Uñas & Desparasitación">Corte de Uñas & Desparasitación</option>
                            <option value="Servicio Especial a Medida">Servicio Especial a Medida</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha_turno">Fecha Preferida *</label>
                            <input type="date" id="fecha_turno" name="fecha_turno" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label for="horario_turno">Franja Horaria Preferida *</label>
                            <select id="horario_turno" name="horario_turno" required>
                                <option value="Mañana (09:00 - 13:00)">Mañana (09:00 - 13:00)</option>
                                <option value="Tarde (13:00 - 17:00)">Tarde (13:00 - 17:00)</option>
                                <option value="A confirmar por WhatsApp">A confirmar por WhatsApp</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notas">Notas o detalles especiales (ej: miedoso, requiere bozal, etc.)</label>
                        <textarea id="notas" name="notas" rows="3"></textarea>
                    </div>

                    <button type="submit">Reservar Turno a Domicilio</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Almitas Peludas. Gestión de Turnos en Odoo 19 Enterprise.</p>
    </footer>
</body>
</html>
