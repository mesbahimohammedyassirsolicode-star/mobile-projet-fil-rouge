const apiUrl = '../backend/api/chambres.php';
const categoryNames = { 1: 'Simple', 2: 'Double', 3: 'Suite' };
const form = document.querySelector('#chambre-form');
const categoryFilter = document.querySelector('#category-filter');
const tableBody = document.querySelector('#chambres-body');
const message = document.querySelector('#message');

function showMessage(text, isError = false) {
    message.textContent = text;
    message.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
}

function addCell(row, value) {
    const cell = document.createElement('td');
    cell.className = 'px-3 py-3';
    cell.textContent = value;
    row.appendChild(cell);
}

async function loadChambres() {
    const selectedCategory = categoryFilter.value;
    const url = selectedCategory ? `${apiUrl}?category_id=${selectedCategory}` : apiUrl;

    try {
        const response = await fetch(url);
        const chambres = await response.json();
        if (!response.ok) throw new Error(chambres.message || 'Impossible de charger les chambres.');

        tableBody.replaceChildren();
        if (chambres.length === 0) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 4;
            cell.className = 'px-3 py-8 text-center text-slate-500';
            cell.textContent = 'Aucune chambre dans cette catégorie pour le moment.';
            row.appendChild(cell);
            tableBody.appendChild(row);
            return;
        }

        chambres.forEach((room) => {
            const row = document.createElement('tr');
            addCell(row, room.id);
            addCell(row, room.numero);
            addCell(row, `${Number(room.prix).toLocaleString('fr-FR')} DH`);
            addCell(row, categoryNames[room.category_id] || 'Inconnue');
            tableBody.appendChild(row);
        });
    } catch (error) {
        showMessage(error.message, true);
    }
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const values = new FormData(form);
    const room = {
        numero: values.get('numero').trim(),
        prix: Number(values.get('prix')),
        category_id: Number(values.get('category_id')),
    };

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(room),
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Erreur lors de l’ajout.');

        showMessage(result.message);
        form.reset();
        await loadChambres();
    } catch (error) {
        showMessage(error.message, true);
    }
});

categoryFilter.addEventListener('change', loadChambres);
loadChambres();
