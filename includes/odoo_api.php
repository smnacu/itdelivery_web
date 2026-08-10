<?php
/**
 * ITDelivery — Odoo 19 Enterprise JSON-RPC Client & Multi-Company Directory
 */

// Cargar variables de entorno locales si existen en storage protegido
$env_file = __DIR__ . '/../storage/.env.php';
if (file_exists($env_file)) {
    include_once $env_file;
}

define('ODOO_URL',     getenv('ODOO_URL')     ?: (defined('ENV_ODOO_URL') ? ENV_ODOO_URL : 'https://itdelivery.odoo.com'));
define('ODOO_DB',      getenv('ODOO_DB')      ?: (defined('ENV_ODOO_DB') ? ENV_ODOO_DB : 'karioka-karioka-33462739'));
define('ODOO_UID',     (int)(getenv('ODOO_UID') ?: (defined('ENV_ODOO_UID') ? ENV_ODOO_UID : 5)));
define('ODOO_API_KEY', getenv('ODOO_API_KEY') ?: (defined('ENV_ODOO_API_KEY') ? ENV_ODOO_API_KEY : ''));

/**
 * Catálogo de Emprendimientos Personales Prioritarios y Tenancies Odoo
 * 
 * PRIORIDAD ALTA (Atención & Desarrollo Estratégico):
 * 1. Almitas Peludas   (Estética & Cuidado Canino)
 * 2. ITDelivery        (Consultoría IT, ERP & Automatización Matriz)
 * 3. LoopLab           (EdTech / Cursos de Inglés con IA)
 * 4. Electroivan       (Electromecánica & Servicios)
 * 5. Cursos del Oeste  (Capacitación / Ser-Nac)
 * 6. Karioka           (Productora Musical & Eventos)
 * 7. Essenza di Sole   (Cosmética & Cuidado Personal)
 * 8. Cohoo             (Outlet Comercial + Tratado Envases Plásticos Comuna 12)
 */
const COMPANY = [
    // ── Catálogo Real de Compañías en Odoo 19 Enterprise ──────────────────────
    'Karioka'              => 1,  // Productora Musical & Eventos (Karioka.ok)
    'ITDelivery'           => 2,  // Matriz Consultoría IT & ERP
    'Cursos del Oeste'     => 3,  // E-learning & Capacitación
    'LoopLab'              => 4,  // Cursos Inglés con IA
    'Santi Nacu'           => 5,  // Servicios Personales & Marca Personal
    'Almitas Peludas'      => 6,  // Estética Canina & Mayorista Morquis
    'Consorcio Escalada'   => 7,  // Consorcio Escalada 4265 Torres 3 y 4
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
