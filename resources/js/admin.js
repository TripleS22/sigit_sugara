// Small helpers for the CMS: slug generation and confirm-before-delete.
const slugify = (value) =>
    value
        .toString()
        .normalize('NFKD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s-]+/g, '-')
        .replace(/^-+|-+$/g, '');

document.querySelectorAll('[data-slug-source]').forEach((source) => {
    const target = document.querySelector(source.dataset.slugSource);
    if (!target) return;
    let touched = target.value !== '';
    target.addEventListener('input', () => (touched = target.value !== ''));
    source.addEventListener('input', () => {
        if (!touched) target.value = slugify(source.value);
    });
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(button.dataset.copy);
            const original = button.textContent;
            button.textContent = 'Tersalin';
            setTimeout(() => (button.textContent = original), 1500);
        } catch {
            window.prompt('Salin teks berikut:', button.dataset.copy);
        }
    });
});

document.querySelectorAll('[data-counter]').forEach((field) => {
    const output = document.querySelector(field.dataset.counter);
    if (!output) return;
    const update = () => (output.textContent = `${field.value.length} karakter`);
    field.addEventListener('input', update);
    update();
});

// Repeater tables (package features): clone the last row with fresh indexes.
document.querySelectorAll('[data-repeater-add]').forEach((button) => {
    const table = button.closest('section')?.querySelector('[data-repeater] tbody');
    if (!table) return;
    button.addEventListener('click', () => {
        const last = table.lastElementChild;
        if (!last) return;
        const index = table.children.length;
        const row = last.cloneNode(true);
        row.querySelectorAll('input').forEach((input) => {
            input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            if (input.type === 'checkbox') input.checked = true;
            else if (input.type !== 'hidden') input.value = '';
        });
        table.appendChild(row);
        row.querySelector('input')?.focus();
    });
});
