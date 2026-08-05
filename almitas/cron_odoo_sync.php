<?php
/**
 * Cron de Sincronizacion Asincronica en Segundo Plano: Web -> Odoo 19 Enterprise
 * Almitas Peludas (Company ID 3)
 */

require_once __DIR__ . '/../includes/odoo_api.php';

$queue_file = __DIR__ . '/queue_data.json';

if (!file_exists($queue_file)) {
    exit("No hay items en la cola de sincronizacion.\n");
}

$queue = json_decode(file_get_contents($queue_file), true) ?? [];
if (empty($queue)) {
    exit("Cola de sincronizacion vacia.\n");
}

$processed = 0;
$failed = 0;
$remaining_queue = [];

foreach ($queue as $item) {
    $action = $item['action'] ?? '';
    $data   = $item['data']   ?? [];
    $id     = $item['id']     ?? '';

    try {
        if ($action === 'create_appointment') {
            // 1. Registrar Cliente
            $partner_id = odoo(
                'res.partner',
                'create',
                [[
                    'name'       => $data['dueno_nombre'],
                    'email'      => $data['email'] ?? '',
                    'phone'      => $data['telefono'],
                    'street'     => $data['direccion'],
                    'city'       => $data['barrio_zona'] ?? '',
                    'comment'    => "Mascota: {$data['mascota_nombre']} ({$data['mascota_raza']})",
                    'company_id' => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            // 2. Registrar Lead CRM
            $lead_desc = "TURNO PELUQUERIA CANINA A DOMICILIO\n"
                       . "----------------------------------------\n"
                       . "Cliente: {$data['dueno_nombre']} ({$data['telefono']})\n"
                       . "Mascota: {$data['mascota_nombre']} ({$data['mascota_raza']})\n"
                       . "Servicio: {$data['servicio']}\n"
                       . "Fecha: {$data['fecha_turno']} ({$data['horario_turno']})\n"
                       . "Direccion: {$data['direccion']}, {$data['barrio_zona']}\n"
                       . "Notas: {$data['notas']}\n";

            odoo(
                'crm.lead',
                'create',
                [[
                    'name'         => "Turno Peluqueria: {$data['mascota_nombre']} - {$data['fecha_turno']}",
                    'partner_id'   => $partner_id,
                    'contact_name' => $data['dueno_nombre'],
                    'phone'        => $data['telefono'],
                    'description'  => $lead_desc,
                    'company_id'   => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            $processed++;
        } elseif ($action === 'create_wholesale_order') {
            // 1. Registrar Cliente
            $partner_id = odoo(
                'res.partner',
                'create',
                [[
                    'name'       => $data['dueno_nombre'],
                    'phone'      => $data['telefono'],
                    'street'     => $data['direccion'],
                    'company_id' => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            // 2. Resumen
            $summary_lines = [];
            foreach ($data['items'] as $it) {
                $subtotal = $it['qty'] * $it['price'];
                $summary_lines[] = "- {$it['qty']}x {$it['name']} - $" . number_format($subtotal, 0, ',', '.');
            }
            $detail = implode("\n", $summary_lines);

            $lead_desc = "PEDIDO MAYORISTA ALMITAS PELUDAS\n"
                       . "----------------------------------------\n"
                       . "Cliente: {$data['dueno_nombre']} ({$data['telefono']})\n"
                       . "Direccion: {$data['direccion']}\n\n"
                       . "Productos:\n$detail\n\n"
                       . "TOTAL ESTIMADO: $" . number_format($data['total_amount'], 0, ',', '.') . "\n";

            odoo(
                'crm.lead',
                'create',
                [[
                    'name'            => "Pedido Mayorista: {$data['dueno_nombre']} - $" . number_format($data['total_amount'], 0, ',', '.'),
                    'partner_id'      => $partner_id,
                    'contact_name'    => $data['dueno_nombre'],
                    'phone'           => $data['telefono'],
                    'planned_revenue' => $data['total_amount'],
                    'description'     => $lead_desc,
                    'company_id'      => COMPANY['Almitas Peludas'],
                ]],
                [],
                COMPANY['Almitas Peludas']
            );

            $processed++;
        }
    } catch (Throwable $e) {
        $failed++;
        $item['error'] = $e->getMessage();
        $item['attempts'] = ($item['attempts'] ?? 0) + 1;
        
        // Conservar en cola si tiene menos de 5 reintentos
        if ($item['attempts'] < 5) {
            $remaining_queue[] = $item;
        }
    }
}

file_put_contents($queue_file, json_encode(array_values($remaining_queue), JSON_PRETTY_PRINT));
echo "Procesados con exito: $processed | Fallidos/Reintentando: $failed\n";
