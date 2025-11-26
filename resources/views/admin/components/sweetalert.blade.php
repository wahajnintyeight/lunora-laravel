{{-- SweetAlert2 Helper Component for Admin --}}
<script>
// SweetAlert2 Helper Functions for Admin
window.AdminSwal = {
    // Default configuration
    config: {
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        showCancelButton: true,
        showCloseButton: true,
        allowOutsideClick: false,
        allowEscapeKey: true,
        reverseButtons: false,
        focusConfirm: true,
        customClass: {
            popup: 'dark:!bg-neutral-800 dark:!text-neutral-200',
            title: 'dark:!text-neutral-200',
            content: 'dark:!text-neutral-400',
            confirmButton: 'dark:!bg-blue-600 dark:hover:!bg-blue-700',
            cancelButton: 'dark:!bg-neutral-700 dark:hover:!bg-neutral-600 dark:!text-neutral-200'
        }
    },

    // Success alert
    success: function(title, text = '', timer = 3000) {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            timer: timer,
            showConfirmButton: timer === 0,
            toast: timer > 0,
            position: timer > 0 ? 'top-end' : 'center',
            ...this.config
        });
    },

    // Error alert
    error: function(title, text = '') {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            ...this.config
        });
    },

    // Warning alert
    warning: function(title, text = '') {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            ...this.config
        });
    },

    // Info alert
    info: function(title, text = '') {
        return Swal.fire({
            icon: 'info',
            title: title,
            text: text,
            ...this.config
        });
    },

    // Confirm dialog
    confirm: function(title, text = '', confirmText = 'Yes, do it!', cancelText = 'Cancel') {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            focusCancel: true,
            ...this.config
        });
    },

    // Delete confirmation
    delete: function(title = 'Are you sure?', text = "You won't be able to revert this!") {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            focusCancel: true,
            ...this.config
        });
    },

    // Loading indicator
    loading: function(title = 'Loading...') {
        return Swal.fire({
            title: title,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            ...this.config
        });
    },

    // Close any open alert
    close: function() {
        Swal.close();
    }
};

// Auto-detect dark mode and apply to SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const isDark = html.classList.contains('dark');
                if (isDark) {
                    document.documentElement.setAttribute('data-swal2-theme', 'dark');
                } else {
                    document.documentElement.removeAttribute('data-swal2-theme');
                }
            }
        });
    });
    
    observer.observe(html, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Set initial theme
    if (html.classList.contains('dark')) {
        document.documentElement.setAttribute('data-swal2-theme', 'dark');
    }
});
</script>

<style>
/* SweetAlert2 Dark Mode Support */
[data-swal2-theme="dark"] .swal2-popup {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}

[data-swal2-theme="dark"] .swal2-title {
    color: #e2e8f0 !important;
}

[data-swal2-theme="dark"] .swal2-content {
    color: #cbd5e1 !important;
}

[data-swal2-theme="dark"] .swal2-confirm {
    background-color: #2563eb !important;
}

[data-swal2-theme="dark"] .swal2-confirm:hover {
    background-color: #1d4ed8 !important;
}

[data-swal2-theme="dark"] .swal2-cancel {
    background-color: #475569 !important;
    color: #e2e8f0 !important;
}

[data-swal2-theme="dark"] .swal2-cancel:hover {
    background-color: #334155 !important;
}

[data-swal2-theme="dark"] .swal2-close {
    color: #cbd5e1 !important;
}

[data-swal2-theme="dark"] .swal2-close:hover {
    color: #e2e8f0 !important;
}
</style>

