// Custom JavaScript for RepuestoFijo
// Bootstrap JS is loaded from CDN

// Add any custom JavaScript functionality here
console.log('RepuestoFijo app loaded');

// Example: Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});