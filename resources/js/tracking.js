// First-party conversion tracking (FR-07). Events are sent with sendBeacon so
// they never block navigation, and mirrored to GA4 when analytics is loaded.
const UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign'];
const STORE_KEY = 'campaign';

const readCampaign = () => {
    try {
        const params = new URLSearchParams(window.location.search);
        const fromUrl = Object.fromEntries(UTM_KEYS.filter((k) => params.get(k)).map((k) => [k, params.get(k)]));
        if (Object.keys(fromUrl).length) {
            sessionStorage.setItem(STORE_KEY, JSON.stringify(fromUrl));
            return fromUrl;
        }
        return JSON.parse(sessionStorage.getItem(STORE_KEY) || '{}');
    } catch {
        return {};
    }
};

const campaign = readCampaign();
const token = document.querySelector('meta[name="csrf-token"]')?.content;

export const track = (event, label = '') => {
    if (!token) return;
    const data = new FormData();
    data.append('_token', token);
    data.append('event', event);
    data.append('label', label.slice(0, 190));
    data.append('path', window.location.pathname);
    Object.entries(campaign).forEach(([key, value]) => data.append(key, value));

    if (navigator.sendBeacon) {
        navigator.sendBeacon('/track', data);
    } else {
        fetch('/track', { method: 'POST', body: data, keepalive: true, credentials: 'same-origin' });
    }

    if (typeof window.gtag === 'function') {
        window.gtag('event', event, { event_label: label, page_path: window.location.pathname });
    }
};

document.addEventListener('click', (event) => {
    const target = event.target.closest('[data-track]');
    if (target) track(target.dataset.track, target.dataset.trackLabel || target.textContent.trim());
});

// Page-level events, e.g. service page views, declared on <body data-track-view="...">.
const view = document.body.dataset.trackView;
if (view) track(view, document.body.dataset.trackViewLabel || document.title);

// Hidden UTM inputs on the lead form so campaign source is stored with the lead.
document.querySelectorAll('form[data-lead-form]').forEach((form) => {
    UTM_KEYS.forEach((key) => {
        const input = form.querySelector(`input[name="${key}"]`);
        if (input && campaign[key]) input.value = campaign[key];
    });
});
