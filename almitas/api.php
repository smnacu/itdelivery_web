<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/odoo_api.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true) ?? $_POST;

$action = $data['action'] ?? '';

// Configuración de la meta de compras mayorista
$TARGET_AMOUNT = 150000;
$CURRENT_AMOUNT = 58680; // Pedido inicial Karioka (1x CatPro + 2x Rubicat Premium)

if ($action === 'get_goal_status') {
    $remaining = max(0, $TARGET_AMOUNT - $CURRENT_AMOUNT);
    $percentage = round(($CURRENT_AMOUNT / $TARGET_AMOUNT) * 100, 1);
    
    echo json_encode([
        'success'    => true,
        'target'     => $TARGET_AMOUNT,
        'current'    => $CURRENT_AMOUNT,
        'remaining'  => $remaining,
        'percentage' => $percentage
    ]);
    exit;
}

if ($action === 'create_appointment') {
    $dueno_nombre   = trim($data['dueno_nombre'] ?? '');
    $email          = trim($data['email'] ?? '');
    $telefono       = trim($data['telefono'] ?? '');
    $direccion      = trim($data['direccion'] ?? '');
    $barrio_zona    = trim($data['barrio_zona'] ?? '');
    $mascota_nombre = trim($data['mascota_nombre'] ?? '');
    $mascota_raza   = trim($data['mascota_raza'] ?? '');
    $servicio       = trim($data['servicio'] ?? 'Peluquería Canina Completa');
    $fecha_turno    = trim($data['fecha_turno'] ?? '');
    $horario_turno  = trim($data['horario_turno'] ?? '');
    $notas          = trim($data['notas'] ?? '');

    if (empty($dueno_nombre) || empty($telefono) || empty($direccion) || empty($fecha_turno)) {
        echo json_encode(['success' => false, 'error' => 'Por favor completá los campos obligatorios.']);
        exit;
    }

    try {
        // 1. Crear / Buscar Cliente en Odoo 19 (Almitas Peludas - Company ID 3)
        $partner_id = odoo(
            'res.partner',
            'create',
            [[
                'name'       => $dueno_nombre,
                'email'      => $email,
                'phone'      => $telefono,
                'street'     => $direccion,
                'city'       => $barrio_zona,
                'comment'    => "Mascota: $mascota_nombre ($mascota_raza)",
                'company_id' => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        // 2. Registrar Oportunidad / Turno CRM
        $lead_desc = "🐾 TURNO PELUQUERÍA CANINA A DOMICILIO\n"
                   . "----------------------------------------\n"
                   . "Cliente: $dueno_nombre ($telefono)\n"
                   . "Mascota: $mascota_nombre ($mascota_raza)\n"
                   . "Servicio: $servicio\n"
                   . "Fecha Solicitada: $fecha_turno ($horario_turno)\n"
                   . "Dirección: $direccion, $barrio_zona\n"
                   . "Notas: $notas\n";

        $lead_id = odoo(
            'crm.lead',
            'create',
            [[
                'name'         => "Turno Peluquería: $mascota_nombre — $fecha_turno ($horario_turno)",
                'partner_id'   => $partner_id,
                'contact_name' => $dueno_nombre,
                'email_from'   => $email,
                'phone'        => $telefono,
                'description'  => $lead_desc,
                'company_id'   => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        echo json_encode([
            'success'    => true,
            'message'    => '¡Turno reservado con éxito! Nos comunicaremos con vos por WhatsApp para confirmar.',
            'lead_id'    => $lead_id,
            'partner_id' => $partner_id
        ]);
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'error' => 'Error Odoo: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'create_wholesale_order') {
    $dueno_nombre = trim($data['dueno_nombre'] ?? '');
    $telefono     = trim($data['telefono'] ?? '');
    $direccion    = trim($data['direccion'] ?? '');
    $items        = $data['items'] ?? [];
    $total_amount = (float)($data['total_amount'] ?? 0);
    $notas        = trim($data['notas'] ?? '');

    if (empty($dueno_nombre) || empty($telefono) || empty($items)) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios del pedido o cliente.']);
        exit;
    }

    try {
        // 1. Registrar cliente en Odoo
        $partner_id = odoo(
            'res.partner',
            'create',
            [[
                'name'       => $dueno_nombre,
                'phone'      => $telefono,
                'street'     => $direccion,
                'company_id' => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        // 2. Formatear resumen del pedido
        $summary_lines = [];
        foreach ($items as $item) {
            $name  = $item['name'] ?? 'Producto';
            $qty   = (int)($item['qty'] ?? 1);
            $price = (float)($item['price'] ?? 0);
            $subtotal = $qty * $price;
            $summary_lines[] = "• {$qty}x {$name} - $" . number_format($subtotal, 0, ',', '.');
        }
        $order_detail = implode("\n", $summary_lines);

        $lead_desc = "📦 PEDIDO MAYORISTA ALMITAS PELUDAS\n"
                   . "----------------------------------------\n"
                   . "Cliente: $dueno_nombre ($telefono)\n"
                   . "Dirección: $direccion\n\n"
                   . "Productos:\n$order_detail\n\n"
                   . "TOTAL ESTIMADO: $" . number_format($total_amount, 0, ',', '.') . "\n"
                   . "Notas: $notas\n";

        $lead_id = odoo(
            'crm.lead',
            'create',
            [[
                'name'         => "Pedido Mayorista: $dueno_nombre — $" . number_format($total_amount, 0, ',', '.'),
                'partner_id'   => $partner_id,
                'contact_name' => $dueno_nombre,
                'phone'        => $telefono,
                'planned_revenue' => $total_amount,
                'description'  => $lead_desc,
                'company_id'   => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        echo json_encode([
            'success'    => true,
            'message'    => 'Pedido registrado exitosamente en Odoo.',
            'lead_id'    => $lead_id,
            'summary'    => $lead_desc
        ]);
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'error' => 'Error registrando pedido: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
