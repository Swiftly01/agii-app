// Photo grid for the listing form: validation, previews, removal and drag to reorder
(() => {
    class PhotoGrid {
        constructor(root) {
            this.root = root;
            this.grid = root.querySelector('[data-photos-grid]');
            this.input = root.querySelector('[data-photos-input]');
            this.addButton = root.querySelector('[data-photos-add]');
            this.max = Number(root.dataset.max);
            this.maxBytes = Number(root.dataset.maxMb) * 1024 * 1024;
            this.mimes = JSON.parse(root.dataset.mimes);
            this.files = [];
            this.onError = () => {};
            this.addButton.addEventListener('click', () => this.input.click());
            this.input.addEventListener('change', () => { this.add([...this.input.files]); this.input.value = ''; });
        }

        add(incoming) {
            const rejected = [];
            incoming.forEach((file) => {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!this.mimes.includes(ext)) return rejected.push(`${file.name} is not a supported format`);
                if (file.size > this.maxBytes) return rejected.push(`${file.name} exceeds ${this.root.dataset.maxMb} Mb`);
                if (this.files.length >= this.max) return rejected.push(`You can add up to ${this.max} photos`);
                this.files.push(file);
            });
            this.onError(rejected[0] || '');
            this.render();
        }

        remove(index) {
            this.files.splice(index, 1);
            this.render();
        }

        move(from, to) {
            this.files.splice(to, 0, this.files.splice(from, 1)[0]);
            this.render();
        }

        clear() {
            this.files = [];
            this.render();
        }

        render() {
            this.grid.querySelectorAll('.lf-photo').forEach((node) => node.remove());
            this.files.forEach((file, index) => this.addButton.before(this.tile(file, index)));
            this.addButton.hidden = this.files.length >= this.max;
        }

        tile(file, index) {
            const tile = Object.assign(document.createElement('div'), { className: 'lf-photo', draggable: true });
            const img = Object.assign(new Image(), { src: URL.createObjectURL(file), alt: '' });
            img.onerror = () => { img.remove(); tile.insertAdjacentHTML('afterbegin', '<i class="bi bi-image"></i>'); };
            tile.append(img);
            tile.insertAdjacentHTML('beforeend', `<button type="button" class="lf-photo__remove" aria-label="Remove">&times;</button>${index === 0 ? '<span class="lf-photo__badge">Title</span>' : ''}`);
            tile.querySelector('.lf-photo__remove').addEventListener('click', () => this.remove(index));
            tile.addEventListener('dragstart', (event) => { event.dataTransfer.setData('text/plain', String(index)); tile.classList.add('is-dragging'); });
            tile.addEventListener('dragend', () => tile.classList.remove('is-dragging'));
            tile.addEventListener('dragover', (event) => event.preventDefault());
            tile.addEventListener('drop', (event) => { event.preventDefault(); this.move(Number(event.dataTransfer.getData('text/plain')), index); });
            return tile;
        }
    }

    window.ListingForm = window.ListingForm || {};
    window.ListingForm.PhotoGrid = PhotoGrid;
})();
