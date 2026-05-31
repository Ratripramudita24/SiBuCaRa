import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('change', async (event) => {
    const select = event.target.closest('[data-worker-assignment-select]');

    if (!select) {
        return;
    }

    const form = select.closest('[data-worker-assignment-form]');
    const status = form?.querySelector('[data-worker-assignment-status]');

    if (!form) {
        return;
    }

    const previousValue = select.dataset.previousValue ?? '';
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const formData = new FormData(form);

    select.disabled = true;

    if (status) {
        status.textContent = 'Menyimpan...';
        status.className = 'mt-1 text-[11px] font-semibold text-blue-600';
    }

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Assignment failed');
        }

        const result = await response.json();

        select.dataset.previousValue = result.assigned_user_id ?? '';

        if (status) {
            status.textContent = result.assigned_user_name ? 'Tersimpan' : 'Menunggu penugasan';
            status.className = 'mt-1 text-[11px] font-semibold text-emerald-600';
        }
    } catch (error) {
        select.value = previousValue;

        if (status) {
            status.textContent = 'Gagal menyimpan';
            status.className = 'mt-1 text-[11px] font-semibold text-red-600';
        }
    } finally {
        select.disabled = false;
    }
});

document.querySelectorAll('[data-worker-assignment-select]').forEach((select) => {
    select.dataset.previousValue = select.value;
});
