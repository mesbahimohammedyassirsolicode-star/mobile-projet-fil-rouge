export function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-50 rounded-lg bg-slate-900 px-4 py-3 text-sm text-white shadow-xl';
    toast.textContent = message;
    document.body.append(toast);
    window.setTimeout(() => toast.remove(), 3500);
}

export function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
