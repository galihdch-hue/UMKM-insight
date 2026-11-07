(function () {
    const green = '#159447', light = '#91d99c', pale = '#d9f2de', dark = '#087a32';
    const gridColor = 'rgba(110,130,115,.13)';

    function chart(id, config) {
        const el = document.getElementById(id);
        if (!el || !window.Chart) return;
        return new Chart(el, {
            ...config,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#123d22', padding: 10 }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#7b887e' } },
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: '#7b887e' } }
                },
                ...config.options
            }
        });
    }

    window.umkmCharts = {
        dashboard(data) {
            chart('productChart', {
                type: 'bar',
                data: {
                    labels: data.productLabels,
                    datasets: [{
                        label: 'Harga Produk (Rp)',
                        data: data.productValues,
                        backgroundColor: green,
                        borderRadius: 7,
                        maxBarThickness: 28
                    }]
                },
                options: { plugins: { legend: { display: false } } }
            });
            chart('categoryChart', {
                type: 'doughnut',
                data: {
                    labels: data.categoryLabels,
                    datasets: [{
                        data: data.categoryValues,
                        backgroundColor: [dark, green, '#69c879', '#bcebc2'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 15 }
                        }
                    },
                    cutout: '66%',
                    scales: {}
                }
            });
        },
        analytics(data) {
            chart('salesChart', {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Harga Produk (Rp)',
                        data: data.prices || data.sales,
                        backgroundColor: green,
                        borderRadius: 7,
                        maxBarThickness: 38
                    }]
                },
                options: { plugins: { legend: { display: false } } }
            });
            chart('salesCategoryChart', {
                type: 'doughnut',
                data: {
                    labels: data.categories,
                    datasets: [{
                        data: data.categoryValues,
                        backgroundColor: [dark, green, '#69c879', '#bcebc2'],
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 14 }
                        }
                    },
                    scales: {}
                }
            });
        }
    };

    window.umkmMaps = {
        mini(id, points) {
            const node = document.getElementById(id);
            if (!node || !window.L) return;
            const valid = (points || []).filter(p => p.lat && p.lng);
            const first = valid[0] || { lat: -6.25, lng: 107.15 };
            const map = L.map(id).setView([first.lat, first.lng], valid.length ? 6 : 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const coords = [];
            valid.forEach(p => {
                coords.push([p.lat, p.lng]);
                L.marker([p.lat, p.lng])
                    .addTo(map)
                    .bindPopup(`<b>${escapeHtml(p.name || 'Produk UMKM')}</b><br>${escapeHtml(p.info || '')}`);
            });

            if (coords.length > 1) {
                L.polyline(coords, { color: '#087a32', weight: 3, dashArray: '6, 6' }).addTo(map);
                map.fitBounds(coords, { padding: [24, 24] });
            }
        }
    };

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[c]));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const shell = document.querySelector('.app-shell');
        if (localStorage.getItem('umkm_dark') === '1') {
            shell?.classList.add('dark');
        }
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            shell?.classList.toggle('dark');
            localStorage.setItem('umkm_dark', shell?.classList.contains('dark') ? '1' : '0');
        });
    });
})();
