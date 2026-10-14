// Mobile navigation and the analytics consent bar. No framework needed here.
const toggle = document.querySelector('[data-nav-toggle]');
const menu = document.querySelector('[data-nav-menu]');

if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        menu.classList.toggle('hidden', open);
    });
}

const CONSENT_KEY = 'analytics-consent';
const consentBar = document.querySelector('[data-consent-bar]');

const readConsent = () => {
    try {
        return localStorage.getItem(CONSENT_KEY);
    } catch {
        return null;
    }
};

const loadAnalytics = () => {
    const id = document.querySelector('meta[name="ga4-id"]')?.content;
    if (!id || window.gtag) return;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function gtag() {
        window.dataLayer.push(arguments);
    };
    window.gtag('js', new Date());
    window.gtag('config', id, { anonymize_ip: true });
    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(id)}`;
    document.head.appendChild(script);
};

if (document.querySelector('meta[name="ga4-id"]')) {
    const requireConsent = document.querySelector('meta[name="ga4-consent"]')?.content === '1';
    const consent = readConsent();
    if (!requireConsent || consent === 'granted') {
        loadAnalytics();
    } else if (consent === null && consentBar) {
        consentBar.classList.remove('hidden');
    }
}

consentBar?.querySelectorAll('[data-consent]').forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.dataset.consent;
        try {
            localStorage.setItem(CONSENT_KEY, value);
        } catch {
            // Storage blocked: the choice applies to this page view only.
        }
        consentBar.classList.add('hidden');
        if (value === 'granted') loadAnalytics();
    });
});
