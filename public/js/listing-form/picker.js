// Option pickers for the listing form: full screen searchable page or bottom sheet, single or multiple choice
(() => {
    const el = (tag, className, html = '') => Object.assign(document.createElement(tag), { className, innerHTML: html });
    const escape = (text) => String(text).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    class Picker {
        constructor(trigger) {
            this.trigger = trigger;
            this.config = JSON.parse(trigger.dataset.config);
            this.name = trigger.dataset.name;
            this.defaults = JSON.parse(trigger.dataset.default || '[]').map(String);
            this.holder = trigger.parentElement.querySelector('[data-picker-values]');
            this.selected = [...this.holder.querySelectorAll('input')].map((input) => input.value);
            trigger.addEventListener('click', () => this.open());
            this.sync();
        }

        // Writes the selection to hidden inputs and the trigger label
        sync() {
            this.holder.innerHTML = this.selected.map((value) => `<input type="hidden" name="${this.name}" value="${escape(value)}">`).join('');
            const text = this.selected.length ? this.selected.join(', ') : (this.config.any && !this.config.multiple && this.touched ? 'Any' : '');
            this.trigger.querySelector('[data-picker-value]').textContent = text;
            this.trigger.classList.toggle('is-filled', text !== '');
            this.trigger.closest('[data-field]')?.classList.remove('has-error');
        }

        reset() {
            this.selected = [...this.defaults];
            this.touched = false;
            this.sync();
        }

        open() {
            this.draft = [...this.selected];
            this.config.mode === 'sheet' ? this.openSheet() : this.openPage();
        }

        choose(value) {
            this.selected = value === '' ? [] : [value];
            this.touched = true;
            this.sync();
        }

        // Bottom sheet with radio rows, closes as soon as one is picked
        openSheet() {
            const backdrop = el('div', 'lf-sheet-backdrop');
            const sheet = el('div', 'lf-sheet', `<button type="button" class="lf-sheet__close" aria-label="Close">&times;</button><div class="lf-sheet__title">${escape(this.config.title)}</div>`);
            const close = () => backdrop.remove();

            this.config.options.forEach((option) => {
                const row = el('button', 'lf-sheet__item' + (this.selected.includes(String(option)) ? ' is-selected' : ''), `<span>${escape(option)}</span><span class="lf-sheet__radio"></span>`);
                row.type = 'button';
                row.addEventListener('click', () => { this.choose(String(option)); close(); });
                sheet.append(row);
            });

            sheet.querySelector('.lf-sheet__close').addEventListener('click', close);
            backdrop.addEventListener('click', (event) => event.target === backdrop && close());
            backdrop.append(sheet);
            document.body.append(backdrop);
        }

        // Full screen list with search, optional popular group, and Clear/Select footer for multiple choice
        openPage() {
            const { title, multiple } = this.config;
            const page = el('div', 'lf-page');
            const head = el('div', 'lf-page__head', '<button type="button" class="lf-page__back" aria-label="Back"><i class="bi bi-arrow-left"></i></button>');
            const search = el('input', 'lf-page__search');
            search.type = 'search';
            search.placeholder = `Find ${title.toLowerCase()}...`;
            head.append(search);
            const list = el('div', 'lf-page__list');
            page.append(head, list);

            const close = () => { page.remove(); document.body.style.overflow = ''; };
            const render = () => this.renderList(list, search.value.trim().toLowerCase(), multiple, close, render);

            if (multiple) {
                const foot = el('div', 'lf-page__foot', '<button type="button" class="lf-btn" data-clear>Clear</button><button type="button" class="lf-btn lf-btn--primary" data-select>Select</button>');
                foot.querySelector('[data-clear]').addEventListener('click', () => { this.draft = []; render(); });
                foot.querySelector('[data-select]').addEventListener('click', () => { this.selected = [...this.draft]; this.touched = true; this.sync(); close(); });
                page.append(foot);
            }

            head.querySelector('.lf-page__back').addEventListener('click', close);
            search.addEventListener('input', render);
            document.body.style.overflow = 'hidden';
            document.body.append(page);
            render();
            search.focus({ preventScroll: true });
        }

        renderList(list, query, multiple, close, render) {
            const options = this.config.options.map(String);
            const matches = (option) => option.toLowerCase().includes(query);
            const popular = query ? [] : this.config.popular.map(String).filter((option) => options.includes(option));
            const rest = options.filter((option) => matches(option) && !popular.includes(option));
            const rows = [];

            if (this.config.any && !multiple && matches('any')) rows.push({ value: '', label: 'Any' });
            if (popular.length) rows.push({ group: 'Popular' }, ...popular.map((value) => ({ value, label: value })), { group: 'Other' });
            rows.push(...rest.map((value) => ({ value, label: value })));

            list.innerHTML = '';
            if (rows.every((row) => row.group)) return list.append(el('div', 'lf-page__empty', 'No results found'));

            rows.forEach((row) => {
                if (row.group) return list.append(el('div', 'lf-page__group', row.group));
                const active = row.value === '' ? this.draft.length === 0 : this.draft.includes(row.value);
                const item = el('button', 'lf-page__item' + (active ? ' is-selected' : ''), `<span>${escape(row.label)}</span>${active ? '<i class="bi bi-check-lg"></i>' : ''}`);
                item.type = 'button';
                item.addEventListener('click', () => {
                    if (!multiple) { this.choose(row.value); return close(); }
                    this.draft = this.draft.includes(row.value) ? this.draft.filter((v) => v !== row.value) : [...this.draft, row.value];
                    render();
                });
                list.append(item);
            });
        }
    }

    window.ListingForm = window.ListingForm || {};
    window.ListingForm.initPickers = (root) => [...root.querySelectorAll('[data-picker]')].map((trigger) => new Picker(trigger));
})();
