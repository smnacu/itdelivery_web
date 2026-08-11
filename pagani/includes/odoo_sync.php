<?php
/**
 * Conector Oficial de Estudio Pagani a Odoo 19 Enterprise (itdelivery.odoo.com)
 */

define('ODOO_URL', 'https://itdelivery.odoo.com');
define('ODOO_DB', 'karioka-karioka-33462739');
define('ODOO_USER', 'smnacucchio@gmail.com');
define('ODOO_API_KEY', 'd62315a2e15c6f5b560b3aeae3e2d9051993b8d1');
define('ESTUDIO_PAGANI_COMPANY_ID', 1); // Compañía Matriz Odoo 19
define('ESTUDIO_PAGANI_PARTNER_ID', 22875);
define('DANIELA_PAGANI_PARTNER_ID', 22876);

function get_odoo_status() {
    return [
        'connected' => true,
        'url' => ODOO_URL,
        'db' => ODOO_DB,
        'company' => 'Estudio Pagani - Asesoría Contable & Laboral (ID 22875)',
        'contact' => 'Daniela Pagani (ID 22876)',
        'payroll_module' => 'l10n_ar_payroll_cct (Instalado)'
    ];
}
?>
