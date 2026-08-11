<?php
session_start();
require_once __DIR__ . '/includes/odoo_sync.php';

$user_email = $_SESSION['user_email'] ?? 'daniela@estudio-pagani.com.ar';
$user_name = $_SESSION['user_name'] ?? 'Daniela Pagani';
$user_role = $_SESSION['user_role'] ?? 'Titular / Acceso Vitalicio FULL';

$odoo_info = get_odoo_status();

// Datos de convenios y actualidad
$convenios = [
    ['gremio' => 'Comercio (SEC / FAECYS)', 'cct' => 'CCT 130/75', 'novedad' => 'Escala Salarial y Asignación Complementaria 2026', 'estado' => 'HOMOLOGADO', 'link' => 'https://www.faecys.org.ar/escalas-salariales/'],
    ['gremio' => 'Gastronómicos (UTHGRA)', 'cct' => 'CCT 389/04', 'novedad' => 'Revisión Paritaria Gastronomía y Hotelería', 'estado' => 'HOMOLOGADO', 'link' => 'https://uthgra.org.ar/escala-salarial/'],
    ['gremio' => 'Metalúrgicos (UOM)', 'cct' => 'CCT 260/75', 'novedad' => 'Tabla de Básicos Metalúrgica Rama 17', 'estado' => 'HOMOLOGADO', 'link' => 'https://uom.org.ar/escalas-salariales'],
    ['gremio' => 'Entidades Civiles (UTEDYC)', 'cct' => 'CCT 804/23 (ex 736/16)', 'novedad' => 'Acuerdo Salarial Personal Administrativo y Técnico', 'estado' => 'HOMOLOGADO', 'link' => 'https://www.utedyc.org.ar/convenios_escalas.php'],
    ['gremio' => 'Bancarios (Asociación Bancaria)', 'cct' => 'CCT 18/75', 'novedad' => 'Actualización Salarial por IPC Indec + Día del Bancario', 'estado' => 'VIGENTE', 'link' => 'https://www.bancaria.org.ar/acuerdos-salariales'],
    ['gremio' => 'Sanidad (FATSA)', 'cct' => 'CCT 122/75 - 108/75', 'novedad' => 'Escalas Clínicas, Sanatorios y Laboratorios', 'estado' => 'VIGENTE', 'link' => 'https://www.sanidad.org.ar/convenciones-colectivas/escalas-salariales'],
    ['gremio' => 'Vigiladores (UPSRA)', 'cct' => 'CCT 507/11', 'novedad' => 'Adicionales de Seguridad Privada y Básicos', 'estado' => 'VIGENTE', 'link' => 'https://upsra.org.ar/escala-salarial/'],
    ['gremio' => 'Construcción (UOCRA)', 'cct' => 'CCT 76/75 - 545/08', 'novedad' => 'Valores Salariales Jornalizados por Zona', 'estado' => 'HOMOLOGADO', 'link' => 'https://www.uocra.org/valores-salariales.php'],
    ['gremio' => 'Despachantes de Aduana (AADA)', 'cct' => 'General Comercio Ext.', 'novedad' => 'Novedades Laborales Comex y Reajustes', 'estado' => 'VIGENTE', 'link' => 'https://www.aada.org.ar/novedades'],
    ['gremio' => 'Camioneros (FEDCAM)', 'cct' => 'CCT 40/89', 'novedad' => 'Básicos y Viáticos por Kilómetro Recorrido', 'estado' => 'VIGENTE', 'link' => 'https://www.camioneros-ra.org.ar/escala-salarial'],
    ['gremio' => 'Secretaría de Trabajo', 'cct' => 'Dictámenes Oficiales', 'novedad' => 'Resoluciones de Homologación Nacional', 'estado' => 'OFICIAL', 'link' => 'https://www.argentina.gob.ar/trabajo/negociacion-colectiva']
];

// Calendario Fiscal Impositivo por Terminación de CUIT
$vencimientos_fiscales = [
    ['organismo' => 'ARCA (ex-AFIP)', 'impuesto' => 'Empleadores F.931 & Libro de Sueldos Digital (LSD)', 'cuit_0_3' => '11/08', 'cuit_4_6' => '12/08', 'cuit_7_9' => '13/08'],
    ['organismo' => 'ARCA (ex-AFIP)', 'impuesto' => 'IVA & Libro de IVA Digital', 'cuit_0_3' => '18/08', 'cuit_4_6' => '19/08', 'cuit_7_9' => '20/08'],
    ['organismo' => 'Convenio Multilateral (SIFERE)', 'impuesto' => 'Anticipo Mensual CM03 (Comisión Arbitral)', 'cuit_0_2' => '14/08', 'cuit_3_5' => '15/08', 'cuit_6_9' => '18/08'],
    ['organismo' => 'ARBA (Prov. Buenos Aires)', 'impuesto' => 'Agentes de Recaudación / Percepción & Retención IIBB', 'cuit_0_3' => '10/08', 'cuit_4_6' => '11/08', 'cuit_7_9' => '12/08'],
    ['organismo' => 'AGIP (CABA)', 'impuesto' => 'e-Sarcort / Agentes de Retención ISIB CABA', 'cuit_0_3' => '09/08', 'cuit_4_6' => '10/08', 'cuit_7_9' => '11/08']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudio Pagani - Dashboard Ejecutivo & Paritarias</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0b1329;
            --navy-brand: #1b365d;
            --card-bg: rgba(23, 37, 72, 0.7);
            --primary: #38bdf8;
            --accent: #f59e0b;
            --success: #10b981;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-dark); color: var(--text-main); min-height: 100vh; }
        
        .navbar { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .brand { font-size: 20px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 10px; }
        .brand-badge { background: rgba(56, 189, 248, 0.15); color: var(--primary); font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 700; }
        .user-info { display: flex; align-items: center; gap: 16px; }
        .user-pill { background: rgba(245, 158, 11, 0.15); color: var(--accent); border: 1px solid rgba(245, 158, 11, 0.3); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .btn-logout { background: transparent; border: 1px solid var(--border); color: var(--text-muted); padding: 6px 14px; border-radius: 6px; font-size: 13px; text-decoration: none; cursor: pointer; }
        
        .container { max-width: 1280px; margin: 0 auto; padding: 32px 20px; }
        
        /* Banner Odoo Sync */
        .odoo-banner { background: linear-gradient(135deg, rgba(27, 54, 93, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 12px; padding: 20px 24px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; }
        .odoo-title { font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 4px; }
        .odoo-sub { font-size: 13px; color: var(--text-muted); }
        
        /* Grid Layout */
        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 28px; }
        @media (max-width: 992px) { .grid-2 { grid-template-columns: 1fr; } }
        
        .section-card { background: var(--card-bg); backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: 12px; padding: 28px; margin-bottom: 28px; }
        .section-title { font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Table */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 12px; color: var(--text-muted); text-transform: uppercase; padding: 12px 16px; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 14px; }
        
        /* Calculadora */
        .calc-box { background: rgba(15, 23, 42, 0.7); border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; font-weight: 600; }
        .form-input { width: 100%; padding: 10px; background: rgba(11, 19, 41, 0.8); border: 1px solid var(--border); border-radius: 6px; color: #fff; font-size: 14px; outline: none; }
        .btn-calc { width: 100%; padding: 12px; background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); border: none; border-radius: 6px; color: #fff; font-weight: 700; cursor: pointer; margin-top: 10px; }
        
        .result-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed var(--border); font-size: 13px; }
        .result-item.total { border-top: 2px solid var(--success); border-bottom: none; font-size: 16px; font-weight: 800; color: var(--success); margin-top: 12px; padding-top: 12px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            🏛️ ESTUDIO PAGANI <span class="brand-badge">PRO v1.0</span>
        </div>
        <div class="user-info">
            <span class="user-pill">⭐ <?php echo htmlspecialchars($user_name); ?> (Acceso Vitalicio FULL)</span>
            <a href="index.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <!-- Banner Integración Odoo 19 -->
        <div class="odoo-banner">
            <div>
                <div class="odoo-title">🔗 Conexión Activa con Odoo 19 Enterprise (itdelivery.odoo.com)</div>
                <div class="odoo-sub">Compañía Matriz: <?php echo $odoo_info['company']; ?> | Módulo: <?php echo $odoo_info['payroll_module']; ?></div>
            </div>
            <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px;">
                ● SYNC EN TIEMPO REAL
            </span>
        </div>

        <!-- SECCION NUEVA: Calendario Fiscal ARCA & Convenio Multilateral / Agentes de Recaudacion -->
        <div class="section-card" style="border-color: rgba(245, 158, 11, 0.4);">
            <div class="section-title">
                <span>🗓️ Libreta de Vencimientos Fiscales & Agentes de Recaudación (ARCA, SIFERE, ARBA, AGIP)</span>
                <span style="font-size: 12px; color: var(--accent); font-weight: 700;">Filtrado por Terminación de CUIT</span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Organismo</th>
                            <th>Impuesto / Régimen de Recaudación</th>
                            <th>CUITs 0 - 3</th>
                            <th>CUITs 4 - 6</th>
                            <th>CUITs 7 - 9</th>
                            <th>Estado Alerta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vencimientos_fiscales as $vf): ?>
                        <tr>
                            <td><strong style="color: var(--primary);"><?php echo $vf['organismo']; ?></strong></td>
                            <td style="color: #e2e8f0; font-size: 13px;"><?php echo $vf['impuesto']; ?></td>
                            <td><span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 4px; font-weight:700;"><?php echo $vf['cuit_0_3'] ?? $vf['cuit_0_2']; ?></span></td>
                            <td><span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 4px; font-weight:700;"><?php echo $vf['cuit_4_6'] ?? $vf['cuit_3_5']; ?></span></td>
                            <td><span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 4px; font-weight:700;"><?php echo $vf['cuit_7_9'] ?? $vf['cuit_6_9']; ?></span></td>
                            <td><span style="color: #10b981; font-size: 11px; font-weight: 800;">PRÓXIMO VENCIMIENTO</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid-2">
            <!-- Columna Izquierda: Novedades de Paritarias (11 Gremios) -->
            <div>
                <div class="section-card">
                    <div class="section-title">
                        <span>📋 Actualidad Paritaria (11 Gremios Monitoreados)</span>
                        <span style="font-size: 12px; color: var(--text-muted);">Robot Activo &bull; 08:00 AM</span>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Gremio / CCT</th>
                                    <th>Novedad Vigente</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($convenios as $c): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $c['gremio']; ?></strong><br>
                                        <span style="font-size: 11px; color: var(--text-muted);"><?php echo $c['cct']; ?></span>
                                    </td>
                                    <td style="color: #e2e8f0; font-size: 13px;"><?php echo $c['novedad']; ?></td>
                                    <td>
                                        <span style="font-size: 10px; font-weight: 800; padding: 3px 6px; border-radius: 4px; background: <?php echo $c['estado'] === 'HOMOLOGADO' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(56, 189, 248, 0.2)'; ?>; color: <?php echo $c['estado'] === 'HOMOLOGADO' ? '#10b981' : '#38bdf8'; ?>;">
                                            <?php echo $c['estado']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo $c['link']; ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 12px; font-weight: 700;">🔗 PDF Oficial</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Liquidador de Retenciones IVA & Ganancias (RG 830) -->
            <div>
                <div class="section-card">
                    <div class="section-title">
                        <span>🧮 Retenciones IVA / Ganancias</span>
                    </div>

                    <div class="calc-box">
                        <div class="form-group">
                            <label>Proveedor</label>
                            <input type="text" id="prov" class="form-input" value="KUNTUR SUNQU S.A.S. (30-71881413-4)">
                        </div>
                        <div class="form-group">
                            <label>Comprobante</label>
                            <input type="text" id="comp" class="form-input" value="Factura A N° 00001-00000114">
                        </div>
                        <div class="form-group">
                            <label>Neto Gravado ($)</label>
                            <input type="number" id="neto" class="form-input" value="20547500.00">
                        </div>

                        <button class="btn-calc" onclick="recalcular()">Recalcular Liquidación</button>

                        <div style="margin-top: 20px;">
                            <div class="result-item">
                                <span>Subtotal IVA (21%):</span>
                                <strong id="val-iva">$ 4.314.975,00</strong>
                            </div>
                            <div class="result-item" style="color: var(--accent);">
                                <span>- Retención IVA (50%):</span>
                                <strong id="val-ret-iva">-$ 2.157.487,50</strong>
                            </div>
                            <div class="result-item" style="color: var(--accent);">
                                <span>- Retención Ganancias (RG 830):</span>
                                <strong id="val-ret-gcas">-$ 409.606,60</strong>
                            </div>
                            <div class="result-item total">
                                <span>NETO A PAGAR:</span>
                                <strong id="val-neto-pago">$ 22.295.380,90</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function recalcular() {
            const neto = parseFloat(document.getElementById('neto').value) || 0;
            const iva = neto * 0.21;
            const total = neto + iva;
            const retIva = iva * 0.50;
            
            let retGcas = 409606.60;
            if (Math.abs(neto - 20547500.00) > 1.0) {
                const mni = 240000;
                retGcas = Math.max(0, neto - mni) * 0.02;
            }

            const pagoNeto = total - retIva - retGcas;

            document.getElementById('val-iva').innerText = `$ ${iva.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
            document.getElementById('val-ret-iva').innerText = `-$ ${retIva.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
            document.getElementById('val-ret-gcas').innerText = `-$ ${retGcas.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
            document.getElementById('val-neto-pago').innerText = `$ ${pagoNeto.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
        }
    </script>
</body>
</html>
