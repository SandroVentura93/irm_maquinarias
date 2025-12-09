// Idempotent UI enhancements for irm_maquinarias
(function () {
    if (window.__irmUIInit) return;
    window.__irmUIInit = true;

    const ensureStyle = (id, css) => {
        if (document.getElementById(id)) return;
        const style = document.createElement('style');
        style.id = id;
        style.textContent = css;
        document.head.appendChild(style);
    };

    const initMenu = () => {
        const menuHeaders = document.querySelectorAll('.menu-item h5');
        if (!menuHeaders.length) return;

        menuHeaders.forEach(header => {
            // Prevent attaching handler twice
            if (header.dataset._uiInit === '1') return;
            header.dataset._uiInit = '1';

            header.addEventListener('click', function () {
                const menuItem = this.parentElement;
                const submenu = this.nextElementSibling;
                if (!submenu) return;

                const isOpen = menuItem.classList.contains('open');

                // Cerrar todos los otros menús
                document.querySelectorAll('.menu-item').forEach(item => {
                    if (item !== menuItem) {
                        item.classList.remove('open');
                        const s = item.querySelector('.submenu');
                        if (s) s.style.display = 'none';
                    }
                });

                // Toggle del menú actual
                if (isOpen) {
                    menuItem.classList.remove('open');
                    submenu.style.display = 'none';
                } else {
                    menuItem.classList.add('open');
                    submenu.style.display = 'block';
                    submenu.style.animation = 'slideDown 0.3s ease-out';
                }
            });
        });
    };

    const initHoverEffects = () => {
        const navLinks = document.querySelectorAll('.nav-link, .submenu a');
        navLinks.forEach(link => {
            if (link.dataset._uiHover === '1') return;
            link.dataset._uiHover = '1';
            link.addEventListener('mouseenter', function () {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });
    };

    // Global loading overlay
    window.showGlobalLoading = function (message = 'Cargando...') {
        if (document.getElementById('globalLoading')) return;
        const loading = document.createElement('div');
        loading.id = 'globalLoading';
        loading.innerHTML = `
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(15, 23, 42, 0.95);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                backdrop-filter: blur(8px);
            ">
                <div style="
                    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                    padding: 3rem 4rem;
                    border-radius: 20px;
                    text-align: center;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                ">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border: 4px solid #e2e8f0;
                        border-top-color: #4f46e5;
                        border-radius: 50%;
                        animation: irm-spin 1s linear infinite;
                        margin: 0 auto 1.5rem;
                    "></div>
                    <p style="
                        margin: 0;
                        font-weight: 600;
                        font-size: 1.1rem;
                        color: #1e293b;
                    ">${message}</p>
                </div>
            </div>
        `;
        document.body.appendChild(loading);
    };

    window.hideGlobalLoading = function () {
        const loading = document.getElementById('globalLoading');
        if (loading) {
            loading.remove();
        }
    };

    // Toasts (evitar duplicados)
    window.showToast = function (message, type = 'info') {
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#0ea5e9'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const containerId = 'irm-toast-container';
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.style.cssText = 'position: fixed; top: 90px; right: 30px; z-index: 10001; display:flex; flex-direction:column; gap:10px;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.style.cssText = `
            background: white;
            color: #1e293b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            border-left: 4px solid ${colors[type] || colors.info};
            font-family: 'Inter', sans-serif;
            animation: irm-slideInRight 0.3s ease-out;
        `;
        toast.innerHTML = `
            <i class="fas ${icons[type] || icons.info}" style="color: ${colors[type] || colors.info}; font-size: 1.5rem;"></i>
            <span style="flex: 1; font-weight: 500;">${message}</span>
            <button style="background:none;border:none;color:#64748b;cursor:pointer;font-size:1.2rem;padding:0;width:24px;height:24px;display:flex;align-items:center;justify-content:center;">&times;</button>
        `;
        toast.querySelector('button').addEventListener('click', () => toast.remove());
        container.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    };

    // Smooth scroll
    const initSmoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            if (anchor.dataset._uiScroll === '1') return;
            anchor.dataset._uiScroll = '1';
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    };

    // Auto-close Bootstrap alerts (safe check)
    const initAutoCloseAlerts = () => {
        const alerts = document.querySelectorAll('.alert');
        if (!alerts.length) return;
        alerts.forEach(alert => {
            if (alert.dataset._uiAlertInit === '1') return;
            alert.dataset._uiAlertInit = '1';
            // solo si bootstrap.Alert está disponible
            if (window.bootstrap && window.bootstrap.Alert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => bsAlert.close(), 5000);
                }, 100);
            } else {
                // fallback: cerrar manualmente
                setTimeout(() => alert.remove(), 5000);
            }
        });
    };

    // Agregar estilos comunes (una sola vez)
    ensureStyle('irm-ui-animations', `
        @keyframes irm-spin { to { transform: rotate(360deg); } }
        @keyframes irm-slideInRight {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
    `);

    // Ejecutar cuando el DOM esté listo
    const runInit = () => {
        initMenu();
        initHoverEffects();
        initSmoothScroll();
        initAutoCloseAlerts();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }

})();