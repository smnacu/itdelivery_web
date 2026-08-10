<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/odoo_api.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true) ?? $_POST;

$action = $data['action'] ?? '';

// Configuración de la meta de compras mayorista Morquis
$TARGET_AMOUNT = 150000;
$CURRENT_AMOUNT = 58680; // Pedido inicial Karioka (1x CatPro + 2x Rubicat Premium)

// Función auxiliar para encolar tareas de forma 100% fail-safe (<10ms)
function enqueue_task(string $action, array $payload): string
{
    $storage_dir = __DIR__ . '/../storage';
    if (!is_dir($storage_dir)) {
        mkdir($storage_dir, 0755, true);
    }
    $queue_file = $storage_dir . '/queue_data.json';
    $queue = file_exists($queue_file) ? json_decode(file_get_contents($queue_file), true) ?? [] : [];
    
    $item_id = uniqid('queue_', true);
    $queue[] = [
        'id'        => $item_id,
        'action'    => $action,
        'data'      => $payload,
        'created_at'=> date('Y-m-d H:i:s'),
        'attempts'  => 0
    ];

    file_put_contents($queue_file, json_encode($queue, JSON_PRETTY_PRINT));
    return $item_id;
}

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

if ($action === 'get_stock') {
    // Consulta los productos de Almitas Peludas (Company ID 6) con stock en Odoo 19
    try {
        $products = odoo(
            'product.product',
            'search_read',
            [[['sale_ok', '=', true]]],
            [
                'fields' => ['id', 'name', 'list_price', 'qty_available', 'virtual_available', 'default_code'],
                'limit'  => 100
            ],
            COMPANY['Almitas Peludas']
        );

        echo json_encode(['success' => true, 'products' => $products]);
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'sync_morquis_to_odoo') {
    // Verificar token administrativo de seguridad
    $admin_token = getenv('ADMIN_SYNC_TOKEN') ?: 'itd_secure_sync_key_2026';
    $provided_token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? ($data['admin_token'] ?? '');

    if (empty($provided_token) || !hash_equals($admin_token, $provided_token)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Acceso denegado. Token de administración no válido.']);
        exit;
    }

    // Importa/Sincroniza los artículos del catálogo de Morquis a Odoo 19 Company 6
    $catalog_file = __DIR__ . '/morquis_parsed_all.json';
    if (!file_exists($catalog_file)) {
        echo json_encode(['success' => false, 'error' => 'No se encontro el archivo morquis_parsed_all.json']);
        exit;
    }

    $morquis_items = json_decode(file_get_contents($catalog_file), true) ?? [];
    $synced = 0;
    $errors = [];

    // Tomamos los primeros 20 para sincronización rápida
    $sample_batch = array_slice($morquis_items, 0, 20);

    foreach ($sample_batch as $item) {
        try {
            odoo(
                'product.template',
                'create',
                [[
                    'name'        => $item['name'],
                    'list_price'  => $item['sale_price'] ?? ($item['cost_price'] * 1.35),
                    'standard_price' => $item['cost_price'],
                    'description_sale' => "Proveedor: Morquis - Marca: {$item['brand']}",
                    'type'        => 'consu', // Consumible / Producto almacenable
                    'company_id'  => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );
            $synced++;
        } catch (Throwable $e) {
            $errors[] = $item['name'] . ': ' . $e->getMessage();
        }
    }

    echo json_encode([
        'success' => true,
        'message' => "Sincronizados $synced productos en Odoo 19 bajo Company ID 6 (Almitas Peludas).",
        'errors'  => $errors
    ]);
    exit;
}

if ($action === 'create_appointment') {
    $dueno_nombre   = trim($data['dueno_nombre'] ?? '');
    $telefono       = trim($data['telefono'] ?? '');
    $direccion      = trim($data['direccion'] ?? '');
    $fecha_turno    = trim($data['fecha_turno'] ?? '');

    if (empty($dueno_nombre) || empty($telefono) || empty($direccion) || empty($fecha_turno)) {
        echo json_encode(['success' => false, 'error' => 'Por favor completa los campos obligatorios.']);
        exit;
    }

    // 1. Encolar tarea para procesamiento fail-safe instantáneo
    $queue_id = enqueue_task('create_appointment', $data);

    // 2. Intentar envio directo a Odoo 19 (si Odoo responde rápido)
    try {
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

        $lead_desc = "TURNO PELUQUERIA CANINA A DOMICILIO\n"
                   . "----------------------------------------\n"
                   . "Cliente: $dueno_nombre ($telefono)\n"
                   . "Mascota: {$data['mascota_nombre']} ({$data['mascota_raza']})\n"
                   . "Servicio: {$data['servicio']}\n"
                   . "Fecha: $fecha_turno ({$data['horario_turno']})\n"
                   . "Direccion: $direccion\n";

        $lead_id = odoo(
            'crm.lead',
            'create',
            [[
                'name'         => "Turno Peluqueria: {$data['mascota_nombre']} - $fecha_turno",
                'partner_id'   => $partner_id,
                'contact_name' => $dueno_nombre,
                'phone'        => $telefono,
                'description'  => $lead_desc,
                'company_id'   => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        echo json_encode([
            'success'  => true,
            'message'  => 'Turno reservado con exito en Odoo 19.',
            'queue_id' => $queue_id,
            'lead_id'  => $lead_id
        ]);
    } catch (Throwable $e) {
        // Si Odoo está lento/caído, retornamos éxito garantizado porque ya quedó en la cola local
        echo json_encode([
            'success'  => true,
            'message'  => 'Turno registrado en cola de alta disponibilidad.',
            'queue_id' => $queue_id,
            'note'     => 'Se procesara automaticamente en segundo plano.'
        ]);
    }
    exit;
}

if ($action === 'create_wholesale_order') {
    $dueno_nombre = trim($data['dueno_nombre'] ?? '');
    $telefono     = trim($data['telefono'] ?? '');
    $items        = $data['items'] ?? [];

    if (empty($dueno_nombre) || empty($telefono) || empty($items)) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios del pedido o cliente.']);
        exit;
    }

    // 1. Encolar tarea para procesamiento fail-safe instantaneo
    $queue_id = enqueue_task('create_wholesale_order', $data);

    // 2. Intentar envío directo a Odoo 19
    try {
        $partner_id = odoo(
            'res.partner',
            'create',
            [[
                'name'       => $dueno_nombre,
                'phone'      => $telefono,
                'street'     => $data['direccion'] ?? '',
                'company_id' => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        $summary_lines = [];
        foreach ($items as $it) {
            $subtotal = $it['qty'] * $it['price'];
            $summary_lines[] = "- {$it['qty']}x {$it['name']} - $" . number_format($subtotal, 0, ',', '.');
        }
        $detail = implode("\n", $summary_lines);

        $lead_desc = "PEDIDO MAYORISTA ALMITAS PELUDAS\n"
                   . "----------------------------------------\n"
                   . "Cliente: $dueno_nombre ($telefono)\n\n"
                   . "Productos:\n$detail\n\n"
                   . "TOTAL ESTIMADO: $" . number_format($data['total_amount'], 0, ',', '.') . "\n";

        $lead_id = odoo(
            'crm.lead',
            'create',
            [[
                'name'            => "Pedido Mayorista: $dueno_nombre - $" . number_format($data['total_amount'], 0, ',', '.'),
                'partner_id'      => $partner_id,
                'contact_name'    => $dueno_nombre,
                'phone'            => $telefono,
                'expected_revenue' => $data['total_amount'],
                'description'     => $lead_desc,
                'company_id'      => COMPANY['Almitas Peludas'],
            ]],
            [],
            COMPANY['Almitas Peludas']
        );

        echo json_encode([
            'success'  => true,
            'message'  => 'Pedido registrado exitosamente en Odoo 19.',
            'queue_id' => $queue_id,
            'lead_id'  => $lead_id
        ]);
    } catch (Throwable $e) {
        echo json_encode([
            'success'  => true,
            'message'  => 'Pedido guardado en cola de alta disponibilidad.',
            'queue_id' => $queue_id
        ]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Accion no valida.']);
