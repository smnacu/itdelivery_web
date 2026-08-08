<?php
/**
 * ITDelivery — Componente Bot Flotante "Analógico" (Asistente de Decisión & Rápido Contacto)
 * Basado en las soluciones de Electro Iván & 392 con integración a Odoo CRM y WhatsApp.
 */
$whatsapp_phone = getenv('WHATSAPP_PHONE') ?: '5491144179028';
?>
<!-- Botón Flotante Activador -->
<div id="itd-bot-launcher" onclick="toggleItdBot()" aria-label="Abrir Asistente Virtua" role="button">
    <div class="bot-launcher-icon">🤖</div>
    <div class="bot-launcher-badge">1</div>
</div>

<!-- Ventana Flotante del Bot -->
<div id="itd-bot-window" class="itd-bot-hidden" role="dialog" aria-labelledby="itd-bot-title">
    <div class="itd-bot-header">
        <div class="itd-bot-avatar">
            🤖
            <span class="itd-status-dot"></span>
        </div>
        <div class="itd-bot-info">
            <div id="itd-bot-title" class="itd-bot-name">Asistente ITDelivery</div>
            <div class="itd-bot-status">En línea — Guía de Soluciones</div>
        </div>
        <button type="button" class="itd-bot-close" onclick="toggleItdBot()" aria-label="Cerrar asistente">&times;</button>
    </div>

    <div class="itd-bot-body" id="itd-bot-chat-body">
        <!-- Mensaje de bienvenida inicial -->
        <div class="itd-msg itd-msg-bot">
            ¡Hola! 👋 Soy el asistente de **ITDelivery**. ¿Qué solución o servicio estás buscando para tu empresa?
        </div>

        <!-- Menú de Opciones (Árbol Analógico) -->
        <div class="itd-bot-options" id="itd-bot-main-menu">
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('odoo')">
                <span>⚡</span> Odoo 19 Enterprise & ERP
            </button>
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('ai')">
                <span>🤖</span> IA & Agentes Autónomos
            </button>
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('cloud')">
                <span>☁️</span> Cloud Architecture & Tunnels
            </button>

            <button type="button" class="itd-opt-btn" onclick="selectBotOption('dev')">
                <span>💻</span> Software Engineering & Apps
            </button>
            <button type="button" class="itd-opt-btn itd-opt-wa" onclick="selectBotOption('whatsapp')">
                <span>💬</span> Contacto Directo WhatsApp
            </button>
        </div>
    </div>

    <div class="itd-bot-footer">
        <button type="button" class="itd-footer-btn" onclick="resetBotMenu()">🔄 Menú Principal</button>
        <button type="button" class="itd-footer-btn itd-footer-cta" onclick="goToCrmForm('')">📝 Formulario CRM</button>
    </div>
</div>

<style>
/* Estilos del Bot Flotante Analógico */
#itd-bot-launcher {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2f81f7 0%, #1f6feb 100%);
    box-shadow: 0 8px 24px rgba(47, 129, 247, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 9999;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

#itd-bot-launcher:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 30px rgba(47, 129, 247, 0.6);
}

.bot-launcher-icon {
    font-size: 1.7rem;
}

.bot-launcher-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #3fb950;
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 800;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #090d16;
}

#itd-bot-window {
    position: fixed;
    bottom: 96px;
    right: 24px;
    width: 380px;
    max-width: calc(100vw - 32px);
    height: 520px;
    background: rgba(13, 17, 26, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    z-index: 10000;
    overflow: hidden;
    transition: opacity 0.25s ease, transform 0.25s ease;
}

#itd-bot-window.itd-bot-hidden {
    opacity: 0;
    pointer-events: none;
    transform: translateY(20px) scale(0.95);
}

.itd-bot-header {
    background: rgba(22, 27, 38, 0.9);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.itd-bot-avatar {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(47, 129, 247, 0.15);
    border: 1px solid rgba(47, 129, 247, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.itd-status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #3fb950;
    border-radius: 50%;
    border: 2px solid #161b26;
}

.itd-bot-info {
    flex-grow: 1;
}

.itd-bot-name {
    font-weight: 700;
    font-size: 0.95rem;
    color: #f0f6fc;
}

.itd-bot-status {
    font-size: 0.75rem;
    color: #9198a1;
}

.itd-bot-close {
    background: none;
    border: none;
    color: #9198a1;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0 0.4rem;
    line-height: 1;
}

.itd-bot-close:hover {
    color: #f0f6fc;
}

.itd-bot-body {
    flex-grow: 1;
    padding: 1.25rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.itd-msg {
    max-width: 88%;
    padding: 0.85rem 1.1rem;
    border-radius: 14px;
    font-size: 0.9rem;
    line-height: 1.45;
}

.itd-msg-bot {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #f0f6fc;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}

.itd-msg-user {
    background: #2f81f7;
    color: #ffffff;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}

.itd-bot-options {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-top: 0.5rem;
}

.itd-opt-btn {
    background: rgba(22, 27, 38, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #c9d1d9;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    transition: all 0.2s ease;
}

.itd-opt-btn:hover {
    background: rgba(47, 129, 247, 0.15);
    border-color: rgba(47, 129, 247, 0.4);
    color: #ffffff;
    transform: translateX(3px);
}

.itd-opt-wa {
    border-color: rgba(63, 185, 80, 0.3);
    background: rgba(63, 185, 80, 0.1);
    color: #3fb950;
}

.itd-opt-wa:hover {
    background: rgba(63, 185, 80, 0.25);
    border-color: #3fb950;
    color: #ffffff;
}

.itd-action-box {
    margin-top: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.itd-action-link {
    background: #2f81f7;
    color: #ffffff;
    text-align: center;
    padding: 0.65rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    display: block;
    border: none;
    cursor: pointer;
}

.itd-action-link:hover {
    background: #1f6feb;
}

.itd-action-wa {
    background: #23a55a;
}

.itd-action-wa:hover {
    background: #1f924f;
}

.itd-bot-footer {
    padding: 0.85rem 1.25rem;
    background: rgba(22, 27, 38, 0.9);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: space-between;
    gap: 0.5rem;
}

.itd-footer-btn {
    background: none;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #9198a1;
    padding: 0.45rem 0.85rem;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.itd-footer-btn:hover {
    color: #f0f6fc;
    border-color: rgba(255, 255, 255, 0.3);
}

.itd-footer-cta {
    background: rgba(47, 129, 247, 0.15);
    color: #2f81f7;
    border-color: rgba(47, 129, 247, 0.3);
}

.itd-footer-cta:hover {
    background: #2f81f7;
    color: #ffffff;
}
</style>

<script>
const ITD_WA_PHONE = "<?= $whatsapp_phone ?>";

function toggleItdBot() {
    const win = document.getElementById('itd-bot-window');
    win.classList.toggle('itd-bot-hidden');
}

function selectBotOption(type) {
    const chatBody = document.getElementById('itd-bot-chat-body');
    const mainMenu = document.getElementById('itd-bot-main-menu');
    if (mainMenu) mainMenu.style.display = 'none';

    let userLabel = '';
    let botReplyHtml = '';

    switch(type) {
        case 'odoo':
            userLabel = '⚡ Odoo 19 Enterprise & ERP';
            botReplyHtml = `
                <div class="itd-msg itd-msg-bot">
                    Ofrecemos implementación integral de **Odoo 19 Enterprise**, conectores personalizados JSON-RPC, desarrollo de módulos a medida y migración de datos sin interrupciones.
                    <div class="itd-action-box">
                        <button type="button" class="itd-action-link" onclick="goToCrmForm('Implementación ERP Odoo 19 Enterprise')">
                            📝 Enviar solicitud a Odoo CRM
                        </button>
                        <a href="https://wa.me/${ITD_WA_PHONE}?text=Hola!%20Quisiera%20consultar%20por%20servicios%20de%20implementación%20de%20Odoo%2019%20Enterprise." target="_blank" class="itd-action-link itd-action-wa">
                            💬 Consultar por WhatsApp
                        </a>
                    </div>
                </div>
            `;
            break;

        case 'ai':
            userLabel = '🤖 IA & Agentes Autónomos';
            botReplyHtml = `
                <div class="itd-msg itd-msg-bot">
                    Desarrollamos agentes inteligentes, integración de LLMs locales y cloud, protocolo MCP (Model Context Protocol) y automatización avanzada de procesos empresariales.
                    <div class="itd-action-box">
                        <button type="button" class="itd-action-link" onclick="goToCrmForm('Consultoría IA & Agentes Autónomos')">
                            📝 Solicitar propuesta de IA
                        </button>
                        <a href="https://wa.me/${ITD_WA_PHONE}?text=Hola!%20Me%20interesa%20integrar%20Agentes%20de%20IA%20y%20automatizaciones%20en%20mi%20empresa." target="_blank" class="itd-action-link itd-action-wa">
                            💬 Hablar con un especialista por WhatsApp
                        </a>
                    </div>
                </div>
            `;
            break;

        case 'cloud':
            userLabel = '☁️ Cloud Architecture & Tunnels';
            botReplyHtml = `
                <div class="itd-msg itd-msg-bot">
                    Configuramos infraestructuras resilientes: Cloudflare Zero Trust Tunnels, servidores de alta disponibilidad en Ferozo/Odoo.sh, SSL perimetral y monitoreo 24/7.
                    <div class="itd-action-box">
                        <button type="button" class="itd-action-link" onclick="goToCrmForm('Arquitectura Cloud & Cloudflare Tunnels')">
                            📝 Solicitar diagnóstico de Infraestructura
                        </button>
                    </div>
                </div>
            `;
            break;

        case 'dev':
            userLabel = '💻 Software Engineering & Apps';
            botReplyHtml = `
                <div class="itd-msg itd-msg-bot">
                    Construimos sistemas a medida, plataformas web y móviles escalables (PHP, Node.js, Flutter, React) integradas de forma nativa a tus bases de datos.
                    <div class="itd-action-box">
                        <button type="button" class="itd-action-link" onclick="goToCrmForm('Desarrollo de Software a Medida')">
                            📝 Presupuestar desarrollo de software
                        </button>
                    </div>
                </div>
            `;
            break;

        case 'whatsapp':
            userLabel = '💬 Contacto Directo WhatsApp';
            botReplyHtml = `
                <div class="itd-msg itd-msg-bot">
                    Te derivamos directamente a nuestro canal de WhatsApp de **ITDelivery** para atención inmediata.
                    <div class="itd-action-box">
                        <a href="https://wa.me/${ITD_WA_PHONE}?text=Hola%20ITDelivery!%20Vengo%20desde%20el%20asistente%20web%20y%20necesito%20asesoramiento." target="_blank" class="itd-action-link itd-action-wa">
                            📲 Iniciar Chat en WhatsApp
                        </a>
                    </div>
                </div>
            `;
            break;
    }

    // Agregar mensaje del usuario
    const userMsg = document.createElement('div');
    userMsg.className = 'itd-msg itd-msg-user';
    userMsg.innerText = userLabel;
    chatBody.appendChild(userMsg);

    // Agregar respuesta del bot
    setTimeout(() => {
        const botMsgWrap = document.createElement('div');
        botMsgWrap.innerHTML = botReplyHtml;
        chatBody.appendChild(botMsgWrap.firstElementChild);
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 200);
}

function goToCrmForm(serviceName) {
    if (serviceName) {
        const input = document.getElementById('servicio_interes');
        if (input) input.value = serviceName;
    }
    toggleItdBot();
    const contactSec = document.getElementById('contacto');
    if (contactSec) {
        contactSec.scrollIntoView({ behavior: 'smooth' });
    }
}

function resetBotMenu() {
    const chatBody = document.getElementById('itd-bot-chat-body');
    chatBody.innerHTML = `
        <div class="itd-msg itd-msg-bot">
            ¡Hola! 👋 Soy el asistente de **ITDelivery**. ¿Qué solución o servicio estás buscando para tu empresa?
        </div>
        <div class="itd-bot-options" id="itd-bot-main-menu">
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('odoo')"><span>⚡</span> Odoo 19 Enterprise & ERP</button>
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('ai')"><span>🤖</span> IA & Agentes Autónomos</button>
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('cloud')"><span>☁️</span> Cloud Architecture & Tunnels</button>
            <button type="button" class="itd-opt-btn" onclick="selectBotOption('dev')"><span>💻</span> Software Engineering & Apps</button>
            <button type="button" class="itd-opt-btn itd-opt-wa" onclick="selectBotOption('whatsapp')"><span>💬</span> Contacto Directo WhatsApp</button>
        </div>
    `;
}
</script>
