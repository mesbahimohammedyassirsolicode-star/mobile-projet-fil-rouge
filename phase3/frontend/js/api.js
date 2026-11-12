const API_BASE_URL = 'http://localhost:8000/api';

async function request(path, options = {}) {
    const response = await fetch(`${API_BASE_URL}${path}`, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...options.headers,
        },
        ...options,
    });

    const payload = await response.json().catch(() => ({
        success: false,
        message: 'The server returned an invalid response.',
    }));

    if (!response.ok || payload.success === false) {
        throw new Error(payload.message || 'The API request failed.');
    }

    return payload;
}

export const api = {
    get: (path) => request(path),
    post: (path, data) => request(path, { method: 'POST', body: JSON.stringify(data) }),
    put: (path, data) => request(path, { method: 'PUT', body: JSON.stringify(data) }),
    delete: (path) => request(path, { method: 'DELETE' }),
};
