#!/usr/bin/env python3
import json
import urllib.request

ODOO_URL = "https://itdelivery.odoo.com"
ODOO_DB = "karioka-karioka-33462739"
ODOO_UID = 5
ODOO_API_KEY = "d62315a2e15c6f5b560b3aeae3e2d9051993b8d1"

def odoo_call(model, method, args, kwargs=None, company_id=6):
    if kwargs is None: kwargs = {}
    kwargs["context"] = kwargs.get("context", {})
    if company_id: kwargs["context"]["allowed_company_ids"] = [company_id]
    payload = {
        "jsonrpc": "2.0",
        "method": "call",
        "id": 1,
        "params": {
            "service": "object",
            "method": "execute_kw",
            "args": [ODOO_DB, ODOO_UID, ODOO_API_KEY, model, method, args, kwargs]
        }
    }
    req = urllib.request.Request(f"{ODOO_URL}/jsonrpc", data=json.dumps(payload).encode("utf-8"), headers={"Content-Type": "application/json"})
    with urllib.request.urlopen(req) as resp:
        res = json.loads(resp.read().decode("utf-8"))
        if "error" in res: raise RuntimeError("Odoo Error: " + str(res["error"]))
        return res["result"]

print("🚀 1. Actualizando datos de compañía Almitas Peludas (Company ID 6)...")
company_data = {
    "name": "Almitas Peludas",
    "email": "almitas@itdelivery.com.ar",
    "phone": "+54 11 7238-3603",
    "website": "https://almitas.itdelivery.com.ar",
    "street": "Capital Federal",
    "city": "Buenos Aires",
    "company_details": "Almitas Peludas — Peluquería Canina, Grooming Felino y Cat Sitting a Domicilio en CABA."
}
res_company = odoo_call("res.company", "write", [[6], company_data], company_id=6)
print("✅ Datos de la compañía actualizados:", res_company)

print("\n🚀 2. Creando/Actualizando Plantillas de Interacción en Odoo 19...")

html_confirmacion = """
<div style="background-color: #0f172a; padding: 30px; font-family: 'Inter', sans-serif; color: #f8fafc; border-radius: 16px; max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; border-bottom: 2px solid #fbbf24; padding-bottom: 20px; margin-bottom: 25px;">
        <h1 style="color: #fbbf24; font-family: 'Outfit', sans-serif; margin: 0; font-size: 26px;">🐾 Almitas Peludas</h1>
        <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Grooming Canino, Felino &amp; Cat Sitting a Domicilio</p>
    </div>
    
    <h2 style="color: #f8fafc; font-size: 20px; font-family: 'Outfit', sans-serif;">¡Hola {{ object.partner_name or 'Tutor/a' }}! ✂️✨</h2>
    <p style="color: #cbd5e1; font-size: 15px; line-height: 1.6;">
        Tu solicitud de turno para <strong>{{ object.name or 'tu peludito' }}</strong> fue registrada con éxito en nuestro sistema de turnos.
    </p>
    
    <div style="background-color: rgba(30, 41, 59, 0.85); border-left: 4px solid #10b981; padding: 18px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0; color: #10b981; font-weight: 600; font-size: 15px;">📍 Servicio a Domicilio en CABA</p>
        <p style="margin: 5px 0 0 0; color: #cbd5e1; font-size: 14px;">Nos pondremos en contacto vía WhatsApp para confirmar el horario exacto del profesional.</p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="https://almitas.itdelivery.com.ar" style="background-color: #fbbf24; color: #0f172a; font-weight: 700; padding: 12px 28px; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 15px;">Ver Estado de Turno Online</a>
    </div>

    <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 30px; padding-top: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
        <p>Almitas Peludas — Instagram: <a href="https://instagram.com/almitaspeludas.ok" style="color: #fbbf24; text-decoration: none;">@almitaspeludas.ok</a></p>
    </div>
</div>
"""

html_bienvenida_lead = """
<div style="background-color: #0f172a; padding: 30px; font-family: 'Inter', sans-serif; color: #f8fafc; border-radius: 16px; max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; border-bottom: 2px solid #fbbf24; padding-bottom: 20px; margin-bottom: 25px;">
        <h1 style="color: #fbbf24; font-family: 'Outfit', sans-serif; margin: 0; font-size: 26px;">✨ Bienvenid@ a Almitas Peludas</h1>
        <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Cuidado Amoroso &amp; Estética Profesional a Domicilio en CABA</p>
    </div>
    
    <p style="color: #cbd5e1; font-size: 15px; line-height: 1.6;">
        ¡Hola! Gracias por escribirnos desde <strong>Instagram (@almitaspeludas.ok)</strong>. Queremos que tu peludito pruebe la experiencia sin estrés de nuestro servicio a domicilio.
    </p>

    <div style="background-color: rgba(251, 191, 36, 0.1); border: 1px solid #fbbf24; padding: 20px; border-radius: 12px; text-align: center; margin: 25px 0;">
        <h3 style="color: #fbbf24; margin: 0 0 8px 0; font-family: 'Outfit', sans-serif;">🎁 Beneficio Exclusivo Redes Social</h3>
        <p style="color: #f8fafc; font-size: 18px; font-weight: 700; margin: 0;">15% OFF en tu Primer Combo Grooming o Cat Sitting</p>
        <p style="color: #94a3b8; font-size: 13px; margin-top: 5px;">Mencioná el código: <strong>ALMITAS-INSTAGRAM</strong> al reservar.</p>
    </div>

    <div style="text-align: center; margin-top: 25px;">
        <a href="https://almitas.itdelivery.com.ar?utm_source=instagram&utm_medium=email_welcome" style="background-color: #fbbf24; color: #0f172a; font-weight: 700; padding: 12px 28px; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 15px;">Reservar Turno Online Con Descuento</a>
    </div>

    <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 30px; padding-top: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
        <p>Almitas Peludas | CABA (Palermo, Belgrano, Recoleta, Caballito)</p>
    </div>
</div>
"""

models = odoo_call("ir.model", "search", [[["model", "=", "crm.lead"]]], company_id=6)
model_id = models[0] if models else False

templates = [
    {
        "name": "Almitas Peludas - Confirmación de Turno & Grooming",
        "subject": "🐾 ¡Turno Confirmado! - Almitas Peludas Grooming & Pet Care",
        "body_html": html_confirmacion
    },
    {
        "name": "Almitas Peludas - Bienvenida Lead Instagram & Promo 15% OFF",
        "subject": "✨ Bienvenid@ a Almitas Peludas - Tu descuento especial para tu peludito 🐱🐶",
        "body_html": html_bienvenida_lead
    }
]

for t in templates:
    existing = odoo_call("mail.template", "search", [[["name", "=", t["name"]]]], company_id=6)
    if existing:
        odoo_call("mail.template", "write", [existing, {"subject": t["subject"], "body_html": t["body_html"]}], company_id=6)
        print(f"🔄 Plantilla actualizada ID {existing[0]}: {t['name']}")
    else:
        new_t = {
            "name": t["name"],
            "subject": t["subject"],
            "body_html": t["body_html"],
        }
        if model_id:
            new_t["model_id"] = model_id
        tid = odoo_call("mail.template", "create", [[new_t]], company_id=6)
        print(f"✨ Plantilla creada ID {tid}: {t['name']}")

print("\n🎉 Configuración de plantillas de interacción en Odoo 19 finalizada con éxito.")
