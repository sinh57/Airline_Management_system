// Theme Toggle Functionality
(function() {
    'use strict';
    
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // Create theme toggle button if it doesn't exist
    function createThemeToggle() {
        const toggle = document.createElement('button');
        toggle.className = 'theme-toggle';
        toggle.innerHTML = savedTheme === 'dark' ? '☀️' : '🌙';
        toggle.setAttribute('aria-label', 'Toggle dark mode');
        toggle.setAttribute('title', 'Toggle dark mode');
        
        toggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            toggle.innerHTML = newTheme === 'dark' ? '☀️' : '🌙';
        });
        
        document.body.appendChild(toggle);
    }
    
    // Initialize theme toggle when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createThemeToggle);
    } else {
        createThemeToggle();
    }
})();

// Loading Indicator Functions
function showLoading(message = 'Processing...') {
    // Remove existing loading overlay if any
    const existing = document.querySelector('.loading-overlay');
    if (existing) {
        existing.remove();
    }
    
    // Create loading overlay
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
        <div class="loading-spinner"></div>
        <span class="loading-text">${message}</span>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}

function hideLoading() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
        document.body.style.overflow = '';
    }
}

// Enhanced Alert Function
function showAlert(message, type = 'info', duration = 5000) {
    // Remove existing alerts
    const existing = document.querySelector('.alert-container');
    if (existing) {
        existing.remove();
    }
    
    // Create alert container
    const container = document.createElement('div');
    container.className = 'alert-container';
    container.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
    `;
    
    // Create alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.style.cssText = `
        margin-bottom: 10px;
        animation: slideIn 0.3s ease;
    `;
    
    alert.innerHTML = `
        <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
        <button onclick="this.parentElement.parentElement.remove()" style="float: right; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
    `;
    
    container.appendChild(alert);
    document.body.appendChild(container);
    
    // Auto-remove after duration
    setTimeout(() => {
        if (container && container.parentNode) {
            container.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => container.remove(), 300);
        }
    }, duration);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
