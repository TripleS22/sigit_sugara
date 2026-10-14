/*
 * React Bits "islands".
 *
 * Pages are rendered by Blade so every word is in the HTML for search engines.
 * Elements marked with data-island="Name" are progressively enhanced with an
 * animated React Bits component once they scroll near the viewport. Each
 * component is code-split, so a page only downloads the components it uses.
 *
 *   data-island="BlurText"        component name (see registry below)
 *   data-props='{"text":"..."}'   JSON props
 *   data-island-wrap              pass the element's server HTML as children
 *
 * React itself is loaded lazily too, so the entry script stays tiny and never
 * blocks the first paint.
 *
 * Decorative motion is skipped when the visitor prefers reduced motion; the
 * server-rendered fallback stays in place.
 */
const registry = {
    Aurora: { load: () => import('../reactbits/Aurora.jsx'), motion: true },
    BlurText: { load: () => import('../reactbits/BlurText.jsx'), motion: true },
    RotatingText: { load: () => import('../reactbits/RotatingText.jsx'), motion: true },
    ShinyText: { load: () => import('../reactbits/ShinyText.jsx'), motion: true },
    CountUp: { load: () => import('../reactbits/CountUp.jsx'), motion: true },
    SpotlightCard: { load: () => import('../reactbits/SpotlightCard.jsx'), motion: false },
    LogoLoop: { load: () => import('./TechLoop.jsx'), motion: true },
};

const prefersReducedMotion = () => window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;

const parseProps = (el) => {
    try {
        return JSON.parse(el.dataset.props || '{}');
    } catch {
        return {};
    }
};

const mount = async (el) => {
    const entry = registry[el.dataset.island];
    if (!entry || el.dataset.islandMounted) return;
    el.dataset.islandMounted = '1';

    try {
        const [{ createElement }, { createRoot }, module] = await Promise.all([
            import('react'),
            import('react-dom/client'),
            entry.load(),
        ]);
        const Component = module.default;
        const props = parseProps(el);

        let children;
        if (el.hasAttribute('data-island-wrap')) {
            const html = el.innerHTML;
            children = createElement('div', { className: 'contents', dangerouslySetInnerHTML: { __html: html } });
            // The component now draws the card, so the server fallback styling is dropped.
            el.className = 'contents';
        }

        createRoot(el).render(createElement(Component, props, children));
        el.classList.add('island-ready');
    } catch (error) {
        // Keep the server-rendered fallback if a component fails to load.
        delete el.dataset.islandMounted;
        console.error(`Island ${el.dataset.island} failed`, error);
    }
};

export const mountIslands = (root = document) => {
    const reduce = prefersReducedMotion();
    const elements = [...root.querySelectorAll('[data-island]')].filter(
        (el) => !(reduce && registry[el.dataset.island]?.motion),
    );
    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) {
        elements.forEach(mount);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    observer.unobserve(entry.target);
                    mount(entry.target);
                }
            });
        },
        { rootMargin: '200px 0px' },
    );
    elements.forEach((el) => observer.observe(el));
};
