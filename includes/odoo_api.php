<?php
/**
 * ITDelivery — Odoo 19 Enterprise JSON-RPC Client & Multi-Company Directory
 */

define('ODOO_URL',     getenv('ODOO_URL')     ?: 'https://itdelivery.odoo.com');
define('ODOO_DB',      getenv('ODOO_DB')      ?: 'karioka-karioka-33462739');
define('ODOO_UID',     (int)(getenv('ODOO_UID') ?: 5));
define('ODOO_API_KEY', getenv('ODOO_API_KEY') ?: 'd62315a2e15c6f5b560b3aeae3e2d9051993b8d1');

/**
 * Catálogo de Empresas y Tenancies Principales
 * Cohoo actúa como canal principal de aceleración y salida comercial para unidades de negocio.
 * Excluidos: Estación 392 y Yesisanaciones.
 */
const COMPANY = [
    'ITDelivery'           => 1,  // Consultoría IT & ERP Matriz
    'Karioka'              => 2,  // Productora Musical & Eventos
    'Almitas Peludas'      => 3,  // Cuidado & Estética Canina
    'LoopLab'              => 4,  // EdTech & Cursos de Inglés
    'Electroivan'          => 5,  // Electromecánica & Servicios
    'Cohoo'                => 6,  // Outlet & Acelerador Comercial Principal
    'Cursos del Oeste'     => 7,  // Plataforma E-learning / Ser-Nac
    'Peirano'              => 8,  // Turnera & Soluciones Logísticas
    'Nacucchio Sosa Tango' => 9,  // Escuela de Baile & Contenido
    'Juana Sanchez'        => 10,
    'El Palacio Vintage'   => 11,
    'Root Hardware'        => 12,
    'SEHYP Ascensores'     => 13,
    'Piel Impresa'         => 14,
];

function odoo(string $model, string $method, array $args = [], array $kwargs = [], int $company = 1): mixed
{
    if ($company > 0) {
        $kwargs['context'] = array_merge(
            $kwargs['context'] ?? [],
            ['allowed_company_ids' => [$company]]
        );
    }

    $payload = json_encode([
        'jsonrpc' => '2.0',
        'method'  => 'call',
        'id'      => uniqid('itd_', true),
        'params'  => [
            'service' => 'object',
            'method'  => 'execute_kw',
            'args'    => [
                ODOO_DB,
                ODOO_UID,
                ODOO_API_KEY,
                $model,
                $method,
                $args,
                $kwargs,
            ],
        ],
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init(ODOO_URL . '/jsonrpc');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        throw new RuntimeException("cURL error: $err");
    }

    $res = json_decode($raw, true);

    if (isset($res['error'])) {
        $msg = $res['error']['data']['message'] ?? $res['error']['message'] ?? 'Odoo error desconocido';
        throw new RuntimeException("Odoo error: $msg");
    }

    return $res['result'];
}

/**
 * Obtiene los productos/servicios activos del catálogo desde Odoo 19
 */
function odoo_get_catalog(int $company = 1, int $limit = 20): array
{
    try {
        return odoo(
            'product.template',
            'search_read',
            [[['sale_ok', '=', true]]],
            [
                'fields' => ['id', 'name', 'list_price', 'description_sale', 'categ_id'],
                'limit'  => $limit,
                'order'  => 'name asc'
            ],
            $company
        );
    } catch (Throwable $e) {
        error_log("Error fetching Odoo catalog: " . $e->getMessage());
        return [];
    }
}
