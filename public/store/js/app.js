function initializeCharts() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [1200, 1900, 1500, 2500, 2200, 3000],
                    borderColor: '#52b788',
                    backgroundColor: 'rgba(82, 183, 136, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Cattle', 'Feed', 'Medicine', 'Needs'],
                datasets: [{
                    data: [35, 25, 20, 20],
                    backgroundColor: [
                        '#2d6a4f',
                        '#52b788',
                        '#95d5b2',
                        '#d8f3dc'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

function showToast(message, type = 'success') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${type === 'success' ? '<i class="bi bi-check-circle-fill me-1"></i>' : '<i class="bi bi-exclamation-triangle-fill me-1"></i>'}
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        container.style.marginTop = '42px';
        document.body.appendChild(container);
    }
    
    container.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = container.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
