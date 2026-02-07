// Système de notifications Toast élégant
class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Créer le conteneur de toasts s'il n'existe pas
        if (!document.getElementById('toastContainer')) {
            this.createContainer();
        }
        this.container = document.getElementById('toastContainer');
    }

    createContainer() {
        const containerHTML = `
            <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-4"></div>
        `;
        document.body.insertAdjacentHTML('beforeend', containerHTML);
    }

    show(options = {}) {
        const defaults = {
            type: 'success', // success, error, warning, info
            title: 'Notification',
            message: 'Opération réussie',
            duration: 5000,
            icon: 'check-circle',
            progress: true
        };

        const config = { ...defaults, ...options };

        // Définir les couleurs et icônes selon le type
        const typeConfig = {
            success: {
                bg: 'from-green-600 to-emerald-600',
                icon: 'check-circle',
                iconBg: 'from-green-500 to-emerald-500'
            },
            error: {
                bg: 'from-red-600 to-pink-600',
                icon: 'exclamation-triangle',
                iconBg: 'from-red-500 to-pink-500'
            },
            warning: {
                bg: 'from-yellow-600 to-orange-600',
                icon: 'exclamation',
                iconBg: 'from-yellow-500 to-orange-500'
            },
            info: {
                bg: 'from-blue-600 to-indigo-600',
                icon: 'info-circle',
                iconBg: 'from-blue-500 to-indigo-500'
            }
        };

        const toastType = typeConfig[config.type] || typeConfig.success;

        const toastHTML = `
            <div class="toast-item transform translate-x-full transition-all duration-500 ease-out max-w-md">
                <div class="bg-gradient-to-r ${toastType.bg} rounded-2xl shadow-2xl border border-white/20 backdrop-blur-xl overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <!-- Icône -->
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br ${toastType.iconBg} rounded-xl flex items-center justify-center">
                                <i class="fas fa-${config.icon || toastType.icon} text-white"></i>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-bold text-lg">${config.title}</h4>
                                <p class="text-white/80 text-sm mt-1">${config.message}</p>
                            </div>
                            
                            <!-- Bouton fermer -->
                            <button type="button" class="toast-close flex-shrink-0 w-6 h-6 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors duration-200">
                                <i class="fas fa-times text-white/80 text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Barre de progression -->
                        ${config.progress ? `
                        <div class="mt-3">
                            <div class="toast-progress bg-white/20 rounded-full h-1 overflow-hidden">
                                <div class="bg-white rounded-full h-full transition-all duration-100 linear" style="width: 100%; transition-duration: ${config.duration}ms;"></div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        // Ajouter le toast au conteneur
        this.container.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = this.container.lastElementChild;

        // Animation d'entrée
        setTimeout(() => {
            toastElement.classList.remove('translate-x-full');
            toastElement.classList.add('translate-x-0');
        }, 10);

        // Gérer la fermeture
        const closeToast = () => {
            toastElement.classList.remove('translate-x-0');
            toastElement.classList.add('translate-x-full');
            setTimeout(() => {
                toastElement.remove();
            }, 500);
        };

        // Bouton fermer
        const closeBtn = toastElement.querySelector('.toast-close');
        closeBtn.addEventListener('click', closeToast);

        // Auto-fermeture
        if (config.duration > 0) {
            setTimeout(closeToast, config.duration);
        }

        return toastElement;
    }

    // Méthodes pratiques
    success(message, title = 'Succès') {
        return this.show({ type: 'success', title, message });
    }

    error(message, title = 'Erreur') {
        return this.show({ type: 'error', title, message });
    }

    warning(message, title = 'Attention') {
        return this.show({ type: 'warning', title, message });
    }

    info(message, title = 'Information') {
        return this.show({ type: 'info', title, message });
    }

    // Afficher les messages flash Laravel
    showFlashMessages() {
        const messages = [
            { type: 'success', selector: '.alert-success' },
            { type: 'error', selector: '.alert-error' },
            { type: 'warning', selector: '.alert-warning' },
            { type: 'info', selector: '.alert-info' }
        ];

        messages.forEach(({ type, selector }) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                const message = element.textContent.trim();
                if (message) {
                    this.show({ type, message });
                    element.remove(); // Supprimer l'élément original
                }
            });
        });
    }
}

// Fonctions globales pour faciliter l'utilisation
let toast;

document.addEventListener('DOMContentLoaded', function() {
    toast = new ToastNotification();
    
    // Afficher automatiquement les messages flash
    setTimeout(() => {
        toast.showFlashMessages();
    }, 100);
});

// Fonctions globales
function showSuccessToast(message, title = 'Succès') {
    toast.success(message, title);
}

function showErrorToast(message, title = 'Erreur') {
    toast.error(message, title);
}

function showWarningToast(message, title = 'Attention') {
    toast.warning(message, title);
}

function showInfoToast(message, title = 'Information') {
    toast.info(message, title);
}
