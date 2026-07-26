import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import './nexo-ui.js'; // registers the shared chrome components on alpine:init
import './nexo-beacon.js'; // cookieless pageview ping (no-op unless the beacon metas are present)

window.Alpine = Alpine;

Alpine.start();

const linksList = document.getElementById('links-list');

if (linksList) {
    Sortable.create(linksList, {
        handle: '[data-drag-handle]',
        animation: 150,
        onEnd: () => {
            const links = [...linksList.querySelectorAll('[data-link-id]')].map(
                (el) => Number(el.dataset.linkId),
            );

            fetch(linksList.dataset.reorderUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ links }),
            });
        },
    });
}
