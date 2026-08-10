# Documento Fundacional & Guía de Branding — ITDelivery

> **ITDelivery** — Firma de Consultoría IT, Arquitectura Cloud, Agentes de Inteligencia Artificial y Holding Tecnológico Matriz.

---

## 1. Definición & Posicionamiento Estratégico

**ITDelivery** se establece como la firma de ingeniería de software, arquitectura de sistemas y consultoría ERP de referencia. Opera en doble rol:
1. **Firma Consultora Externa**: Proveedora de soluciones de alto impacto en implementación de Odoo 19 Enterprise, infraestructura Cloudflare/DevOps y automatización con IA para empresas cliente.
2. **Matriz Tecnológica del Grupo**: Núcleo digital y ERP matriz (Tenant 2 en Odoo) que brinda soporte técnico, hosting y desarrollo a los emprendimientos del ecosistema.

### Misión
Diseñar e implementar arquitecturas digitales resilientes, automatizadas y escalables que eliminen la fricción operativa y potencien el crecimiento comercial.

### Visión
Liderar la integración de Inteligencia Artificial operativa (agentes autónomos MCP) y sistemas ERP Enterprise en el mercado regional, basando cada desarrollo en cimientos sólidos, seguridad estricta y código de máxima calidad.

---

## 2. Los 4 Pilares de Servicio

```
                        ┌────────────────────────────────────────┐
                        │              ITDELIVERY                │
                        └───────────────────┬────────────────────┘
                                            │
        ┌───────────────────┬───────────────┴───────────────┬───────────────────┐
        ▼                   ▼                               ▼                   ▼
┌──────────────┐   ┌────────────────┐             ┌──────────────────┐   ┌──────────────────┐
│   ODOO 19    │   │  IA & AGENTES  │             │   CLOUD & DEVOPS │   │   SOFTWARE ENG.  │
│  ENTERPRISE  │   │   AUTÓNOMOS    │             │   INFRASTRUCTURE │   │   DEVELOPMENT    │
└──────────────┘   └────────────────┘             └──────────────────┘   └──────────────────┘
```

1. **⚡ ERP Odoo 19 Enterprise**:
   - Implementación llave en mano del ERP matriz.
   - Desarrollo de módulos personalizados y conectores JSON-RPC / REST.
   - Localización argentina, facturación electrónica y flujos multi-company.

2. **🤖 IA & Agentes Autónomos**:
   - Integración de LLMs (locales y cloud) para optimizar procesos de negocio.
   - Implementación del protocolo **MCP (Model Context Protocol)** y servidores Engram para memoria persistente.
   - Automatización de análisis de datos y asistentes conversacionales analógicos/guiados.

3. **☁️ Arquitectura Cloud & DevOps**:
   - Configuración perimetral con **Cloudflare Zero Trust Tunnels** y SSL encriptado.
   - Hosting de alta disponibilidad (Ferozo / Odoo.sh / AWS).
   - Pipelines CI/CD y despliegues automáticos por SSH / Git.

4. **💻 Software Engineering & APIs**:
   - Desarrollo Fullstack (PHP 8+, Node.js, Python, Flutter, React).
   - Microservicios aislados, diseño de APIs RESTful y arquitectura limpia/hexagonal.

---

## 3. Arquitectura Multi-Company (Holding Tecnológico)

ITDelivery administra y respalda tecnológicamente el portfolio multi-tenancy en Odoo 19 Enterprise:

| Compañía / Marca | Rubro / Enfoque | Tenancy Odoo |
|---|---|---|
| **ITDelivery** | Consultoría IT, ERP Matriz, Cloud & IA | Company ID: 2 (Matriz) |
| **Almitas Peludas** | Estética Canina & Mayorista Morquis | Company ID: 6 |
| **LoopLab** | EdTech / Capacitación en Inglés con IA | Company ID: 4 |
| **Electro Iván** | Servicios Electromecánicos & Taller | Company ID: 1 |
| **Cursos del Oeste** | E-learning & Capacitación Profesional | Company ID: 3 |
| **Cohoo** | Outlet Comercial & Tratado Envases Comuna 12 | Comercial Outlet |
| **Karioka** | Productora Musical & Eventos | Entretenimiento |

---

## 4. Sistema de Diseño (Brand & UI System)

### Paleta de Colores Corporativa
- **Fondo Principal (Obsidian)**: `#090d16` (Profundo, moderno, enfocado).
- **Tarjetas & Contenedores (Glassmorphism)**: `rgba(22, 27, 38, 0.7)` con borde `rgba(255, 255, 255, 0.08)`.
- **Azul Primario (Tech Blue)**: `#2f81f7` — Representa precisión, confianza e ingeniería.
- **Verde Acento (Success/Emerald)**: `#3fb950` — Estado activo, crecimiento y conversiones.
- **Violeta IA (Purple Glow)**: `#a371f7` — Innovación, LLMs y agentes autónomos.
- **Texto Principal**: `#f0f6fc` | **Texto Secundario**: `#9198a1`.

### Tipografía & Jerarquía
- **Fuente Principal**: `Inter` (Google Fonts), sans-serif.
- **Ponderaciones**: `300` (Light), `400` (Regular), `600` (SemiBold), `800` (ExtraBold para Hero & Titles).

---

## 5. Principios Operativos & Filosofía Tecnológica

1. **Patrón Odoo-Centric**: Cero dependencia de terceros. Los leads se capturan directamente en `crm.lead` de Odoo 19 via JSON-RPC y las comunicaciones utilizan Deep Links (`wa.me`) y plantillas SMTP propias (`mail.template`).
2. **Blindaje & Seguridad Integrada**: Filtros Honeypot invisibles, Time-traps anti-bot, Rate-Limiting por sesión y encabezados HTTP strictly (`X-Frame-Options`, `X-Content-Type-Options`).
3. **Despliegues Automáticos por Git SSH**: Cero FTPs manuales. Flujo estructurado: `git commit` -> `git push origin main` -> Deploy automático en Ferozo mediante SSH Deploy Keys.
4. **Cimientos sobre Inmediatez (Solid Foundations)**: Patrones de diseño limpios, estructuras escalables y documentación previa antes de codificar.
