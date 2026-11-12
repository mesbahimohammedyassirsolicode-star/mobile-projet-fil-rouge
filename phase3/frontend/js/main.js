import { api } from './api.js';
import { escapeHtml, showToast } from './utils.js';

const configs = {
    categories: {
        label: 'Categories',
        path: 'categories',
        columns: ['nom', 'description'],
        fields: [
            ['nom', 'Name', 'text', true],
            ['description', 'Description', 'textarea', false],
        ],
    },
    destinations: {
        label: 'Destinations',
        path: 'destinations',
        columns: ['nom', 'ville', 'region', 'pays'],
        fields: [
            ['nom', 'Name', 'text', true], ['description', 'Description', 'textarea', true],
            ['ville', 'City', 'text', true], ['region', 'Region', 'text', false],
            ['pays', 'Country', 'text', true], ['image', 'Image URL', 'text', false],
            ['latitude', 'Latitude', 'number', false], ['longitude', 'Longitude', 'number', false],
        ],
    },
    lieux: {
        label: 'Tourist places',
        path: 'lieux',
        columns: ['nom', 'adresse', 'id_destination', 'id_categorie'],
        fields: [
            ['nom', 'Name', 'text', true], ['description', 'Description', 'textarea', true],
            ['adresse', 'Address', 'text', false], ['prix_entree', 'Entry price', 'number', false],
            ['horaires', 'Opening hours', 'text', false], ['image', 'Image URL', 'text', false],
            ['id_destination', 'Destination ID', 'number', true], ['id_categorie', 'Category ID', 'number', true],
        ],
    },
    activites: {
        label: 'Activities',
        path: 'activites',
        columns: ['nom', 'duree', 'prix', 'niveau'],
        fields: [
            ['nom', 'Name', 'text', true], ['description', 'Description', 'textarea', true],
            ['duree', 'Duration (minutes)', 'number', false], ['prix', 'Price', 'number', false],
            ['niveau', 'Level', 'text', false], ['image', 'Image URL', 'text', false],
            ['id_destination', 'Destination ID', 'number', true], ['id_categorie', 'Category ID', 'number', true],
        ],
    },
    utilisateurs: {
        label: 'Users',
        path: 'utilisateurs',
        columns: ['nom', 'prenom', 'email', 'telephone'],
        fields: [
            ['nom', 'Last name', 'text', true], ['prenom', 'First name', 'text', true],
            ['email', 'Email', 'email', true], ['mot_de_passe', 'Password', 'password', false],
            ['telephone', 'Phone', 'text', false], ['date_naissance', 'Birth date', 'date', false],
            ['photo', 'Photo URL', 'text', false],
        ],
    },
    'favoris-lieux': {
        label: 'Place favorites',
        path: 'favoris-lieux',
        columns: ['id_utilisateur', 'id_lieu', 'date_ajout'],
        fields: [['id_utilisateur', 'User ID', 'number', true], ['id_lieu', 'Place ID', 'number', true]],
    },
    'favoris-activites': {
        label: 'Activity favorites',
        path: 'favoris-activites',
        columns: ['id_utilisateur', 'id_activite', 'date_ajout'],
        fields: [['id_utilisateur', 'User ID', 'number', true], ['id_activite', 'Activity ID', 'number', true]],
    },
    'avis-lieux': {
        label: 'Place reviews',
        path: 'avis-lieux',
        columns: ['note', 'commentaire', 'id_utilisateur', 'id_lieu'],
        fields: [
            ['note', 'Rating (1-5)', 'number', true], ['commentaire', 'Comment', 'textarea', false],
            ['id_utilisateur', 'User ID', 'number', true], ['id_lieu', 'Place ID', 'number', true],
        ],
    },
    'avis-activites': {
        label: 'Activity reviews',
        path: 'avis-activites',
        columns: ['note', 'commentaire', 'id_utilisateur', 'id_activite'],
        fields: [
            ['note', 'Rating (1-5)', 'number', true], ['commentaire', 'Comment', 'textarea', false],
            ['id_utilisateur', 'User ID', 'number', true], ['id_activite', 'Activity ID', 'number', true],
        ],
    },
    voyages: {
        label: 'Trips',
        path: 'voyages',
        columns: ['titre', 'date_debut', 'date_fin', 'budget'],
        fields: [
            ['titre', 'Title', 'text', true], ['date_debut', 'Start date', 'date', true],
            ['date_fin', 'End date', 'date', true], ['budget', 'Budget', 'number', false],
            ['description', 'Description', 'textarea', false], ['id_utilisateur', 'User ID', 'number', true],
            ['id_destination', 'Destination ID', 'number', true],
        ],
    },
    'etapes-lieux': {
        label: 'Place trip steps',
        path: 'etapes-lieux',
        columns: ['date_etape', 'heure_debut', 'heure_fin', 'ordre', 'id_voyage', 'id_lieu'],
        fields: [
            ['date_etape', 'Date', 'date', true], ['heure_debut', 'Start time', 'time', false],
            ['heure_fin', 'End time', 'time', false], ['ordre', 'Order', 'number', true],
            ['notes', 'Notes', 'textarea', false], ['id_voyage', 'Trip ID', 'number', true],
            ['id_lieu', 'Place ID', 'number', true],
        ],
    },
    'etapes-activites': {
        label: 'Activity trip steps',
        path: 'etapes-activites',
        columns: ['date_etape', 'heure_debut', 'heure_fin', 'ordre', 'id_voyage', 'id_activite'],
        fields: [
            ['date_etape', 'Date', 'date', true], ['heure_debut', 'Start time', 'time', false],
            ['heure_fin', 'End time', 'time', false], ['ordre', 'Order', 'number', true],
            ['notes', 'Notes', 'textarea', false], ['id_voyage', 'Trip ID', 'number', true],
            ['id_activite', 'Activity ID', 'number', true],
        ],
    },
};

const pageResources = {
    'categories.html': 'categories',
    'destinations.html': 'destinations',
    'places.html': 'lieux',
    'activities.html': 'activites',
    'users.html': 'utilisateurs',
    'voyages.html': 'voyages',
};
const pageName = window.location.pathname.split('/').pop();
const requestedResource = new URLSearchParams(window.location.search).get('resource');
const state = {
    resource: configs[requestedResource] ? requestedResource : (pageResources[pageName] || 'categories'),
    page: 1,
    perPage: 10,
    search: '',
    rows: [],
    pagination: {},
};
const app = document.querySelector('#app');

function renderShell() {
    app.innerHTML = `
        <div class="flex min-h-screen flex-col md:flex-row">
            <aside class="w-full bg-slate-900 text-white md:min-h-screen md:w-64">
                <div class="border-b border-slate-700 px-5 py-5"><p class="text-xs uppercase tracking-widest text-cyan-300">Smart Tourism</p><h1 class="mt-1 text-xl font-bold">Admin dashboard</h1></div>
                <nav id="navigation" class="grid grid-cols-2 gap-1 p-3 md:block"></nav>
            </aside>
            <main class="min-w-0 flex-1 p-4 md:p-8">
                <div id="content"></div>
            </main>
        </div>
        <div id="modal"></div>
    `;
    renderNavigation();
    renderContent();
}

function renderNavigation() {
    const navigation = document.querySelector('#navigation');
    navigation.innerHTML = Object.entries(configs).map(([key, config]) => `
        <button data-resource="${key}" class="nav-item mb-1 w-full rounded-lg px-3 py-2 text-left text-sm transition hover:bg-slate-800 ${key === state.resource ? 'bg-cyan-600' : ''}">${config.label}</button>
    `).join('');
    navigation.querySelectorAll('[data-resource]').forEach((button) => {
        button.addEventListener('click', () => {
            state.resource = button.dataset.resource;
            state.page = 1;
            state.search = '';
            renderNavigation();
            renderContent();
            loadRows();
        });
    });
}

function renderContent() {
    const config = configs[state.resource];
    document.querySelector('#content').innerHTML = `
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div><p class="text-sm font-medium text-cyan-700">Management</p><h2 class="text-3xl font-bold text-slate-900">${config.label}</h2></div>
            <button id="add-record" class="rounded-lg bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700">+ Add record</button>
        </div>
        <section class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Current records</p><p id="stat-total" class="mt-2 text-2xl font-bold text-slate-900">—</p></div>
            <div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Current page</p><p id="stat-page" class="mt-2 text-2xl font-bold text-slate-900">—</p></div>
            <div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">API status</p><p id="stat-status" class="mt-2 text-2xl font-bold text-amber-500">Loading</p></div>
        </section>
        <section class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row">
                <input id="search" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-cyan-500 sm:max-w-sm" placeholder="Search records..." value="${escapeHtml(state.search)}">
                <button id="refresh" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Refresh</button>
            </div>
            <div id="table-container" class="overflow-x-auto"></div>
            <div id="pagination" class="flex items-center justify-between border-t border-slate-200 p-4 text-sm text-slate-600"></div>
        </section>
    `;
    document.querySelector('#add-record').addEventListener('click', () => openModal());
    document.querySelector('#refresh').addEventListener('click', loadRows);
    document.querySelector('#search').addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            state.search = event.target.value.trim();
            state.page = 1;
            loadRows();
        }
    });
}

async function loadRows() {
    const config = configs[state.resource];
    setLoading(true);
    try {
        const query = new URLSearchParams({ page: state.page, per_page: state.perPage });
        if (state.search) query.set('search', state.search);
        const response = await api.get(`/${config.path}?${query}`);
        state.rows = response.data.items;
        state.pagination = response.data.pagination;
        renderTable();
        document.querySelector('#stat-status').textContent = 'Online';
        document.querySelector('#stat-status').className = 'mt-2 text-2xl font-bold text-emerald-500';
    } catch (error) {
        document.querySelector('#table-container').innerHTML = `<div class="p-8 text-center text-red-600">${escapeHtml(error.message)}</div>`;
        document.querySelector('#stat-status').textContent = 'Error';
        showToast(error.message);
    } finally {
        setLoading(false);
    }
}

function renderTable() {
    const config = configs[state.resource];
    document.querySelector('#stat-total').textContent = state.pagination.total ?? 0;
    document.querySelector('#stat-page').textContent = `${state.pagination.page ?? 1} / ${state.pagination.pages || 1}`;
    if (!state.rows.length) {
        document.querySelector('#table-container').innerHTML = '<div class="p-10 text-center text-slate-500">No records found.</div>';
    } else {
        document.querySelector('#table-container').innerHTML = `<table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr>${config.columns.map((column) => `<th class="whitespace-nowrap px-4 py-3">${column.replaceAll('_', ' ')}</th>`).join('')}<th class="px-4 py-3">Actions</th></tr></thead><tbody class="divide-y divide-slate-100">${state.rows.map((row) => `<tr class="hover:bg-slate-50">${config.columns.map((column) => `<td class="max-w-xs truncate whitespace-nowrap px-4 py-3 text-slate-700">${escapeHtml(row[column])}</td>`).join('')}<td class="whitespace-nowrap px-4 py-3"><button data-edit="${row[primaryKey(config.path)]}" class="mr-3 font-medium text-cyan-700 hover:underline">Edit</button><button data-delete="${row[primaryKey(config.path)]}" class="font-medium text-red-600 hover:underline">Delete</button></td></tr>`).join('')}</tbody></table>`;
    }
    document.querySelector('#pagination').innerHTML = `<span>Page ${state.pagination.page ?? 1} of ${state.pagination.pages || 1}</span><div class="flex gap-2"><button id="previous" class="rounded border px-3 py-1 disabled:opacity-40" ${state.page <= 1 ? 'disabled' : ''}>Previous</button><button id="next" class="rounded border px-3 py-1 disabled:opacity-40" ${state.page >= (state.pagination.pages || 1) ? 'disabled' : ''}>Next</button></div>`;
    document.querySelector('#previous').addEventListener('click', () => { state.page -= 1; loadRows(); });
    document.querySelector('#next').addEventListener('click', () => { state.page += 1; loadRows(); });
    document.querySelectorAll('[data-edit]').forEach((button) => button.addEventListener('click', () => openModal(state.rows.find((row) => String(row[primaryKey(config.path)]) === button.dataset.edit))));
    document.querySelectorAll('[data-delete]').forEach((button) => button.addEventListener('click', () => deleteRecord(button.dataset.delete)));
}

function primaryKey(path) {
    const keys = { categories: 'id_categorie', destinations: 'id_destination', lieux: 'id_lieu', activites: 'id_activite', utilisateurs: 'id_utilisateur', 'favoris-lieux': 'id_favori_lieu', 'favoris-activites': 'id_favori_activite', 'avis-lieux': 'id_avis_lieu', 'avis-activites': 'id_avis_activite', voyages: 'id_voyage', 'etapes-lieux': 'id_etape_lieu', 'etapes-activites': 'id_etape_activite' };
    return keys[path];
}

function openModal(row = null) {
    const config = configs[state.resource];
    document.querySelector('#modal').innerHTML = `<div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/60 p-4"><div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-6 shadow-2xl"><div class="mb-5 flex items-center justify-between"><h3 class="text-xl font-bold">${row ? 'Edit' : 'Add'} ${config.label}</h3><button id="close-modal" class="text-2xl text-slate-400">&times;</button></div><form id="record-form" class="grid gap-4 sm:grid-cols-2">${config.fields.map(([name, label, type, required]) => `<label class="${type === 'textarea' ? 'sm:col-span-2' : ''}"><span class="mb-1 block text-sm font-medium text-slate-700">${label}</span>${type === 'textarea' ? `<textarea name="${name}" class="w-full rounded-lg border border-slate-300 px-3 py-2" ${required ? 'required' : ''}>${escapeHtml(row?.[name] ?? '')}</textarea>` : `<input name="${name}" type="${type}" value="${escapeHtml(row?.[name] ?? '')}" class="w-full rounded-lg border border-slate-300 px-3 py-2" ${required ? 'required' : ''}>`}</label>`).join('')}<div class="flex justify-end gap-3 sm:col-span-2"><button type="button" id="cancel-modal" class="rounded-lg border px-4 py-2">Cancel</button><button class="rounded-lg bg-cyan-600 px-4 py-2 font-semibold text-white">Save</button></div></form></div></div>`;
    document.querySelectorAll('#close-modal, #cancel-modal').forEach((button) => button.addEventListener('click', closeModal));
    document.querySelector('#record-form').addEventListener('submit', (event) => submitRecord(event, row));
}

async function submitRecord(event, row) {
    event.preventDefault();
    const config = configs[state.resource];
    const formData = new FormData(event.target);
    const payload = Object.fromEntries([...formData.entries()].filter(([, value]) => value !== ''));
    if (row && payload.mot_de_passe === '') delete payload.mot_de_passe;
    Object.keys(payload).forEach((key) => { if (['id_', 'note', 'duree', 'ordre'].some((prefix) => key.startsWith(prefix)) || ['id_destination', 'id_categorie', 'id_utilisateur', 'id_lieu', 'id_activite', 'id_voyage', 'note', 'duree', 'ordre'].includes(key)) payload[key] = Number(payload[key]); });
    try {
        const id = row?.[primaryKey(config.path)];
        if (id) await api.put(`/${config.path}/${id}`, payload); else await api.post(`/${config.path}`, payload);
        closeModal();
        showToast('Record saved successfully.');
        await loadRows();
    } catch (error) {
        showToast(error.message);
    }
}

async function deleteRecord(id) {
    if (!window.confirm('Delete this record? This action cannot be undone.')) return;
    try {
        await api.delete(`/${configs[state.resource].path}/${id}`);
        showToast('Record deleted successfully.');
        await loadRows();
    } catch (error) {
        showToast(error.message);
    }
}

function closeModal() {
    document.querySelector('#modal').innerHTML = '';
}

function setLoading(loading) {
    const status = document.querySelector('#stat-status');
    if (status && loading) status.textContent = 'Loading';
}

renderShell();
loadRows();
