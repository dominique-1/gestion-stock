// Modal de confirmation pour les suppressions et autres actions
class ConfirmModal {
    constructor() {
        this.modal = null;
        this.init();
    }

    init() {
        // Créer le modal s'il n'existe pas
        if (!document.getElementById('confirmModal')) {
            this.createModal();
        }
        this.modal = document.getElementById('confirmModal');
    }

    createModal() {
        const modalHTML = `
            <div id="confirmModal" class="fixed inset-0 z-50 hidden">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm animate-fade-in"></div>
                
                <!-- Modal -->
                <div class="absolute inset-0 flex items-center justify-center p-4">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl border border-white/20 shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                        <!-- Header -->
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center animate-pulse" id="modalIcon">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white" id="modalTitle">Confirmation</h3>
                                    <p class="text-gray-400 text-sm" id="modalSubtitle">Veuillez confirmer cette action</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="p-6">
                            <p class="text-gray-300 text-lg" id="modalMessage">Êtes-vous sûr de vouloir continuer ?</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="p-6 border-t border-white/10 flex justify-end space-x-4">
                            <button type="button" onclick="confirmModal.hide()" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl border border-white/20 transition-all duration-300 font-semibold">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </button>
                            <button type="button" id="confirmBtn" class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-red-500/50">
                                <i class="fas fa-check mr-2"></i>Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    show(options = {}) {
        const defaults = {
            title: 'Confirmation',
            subtitle: 'Veuillez confirmer cette action',
            message: 'Êtes-vous sûr de vouloir continuer ?',
            icon: 'exclamation-triangle',
            iconColor: 'from-red-500 to-pink-500',
            confirmText: 'Confirmer',
            confirmColor: 'from-red-600 to-pink-600',
            onConfirm: null
        };
        
        const config = { ...defaults, ...options };
        
        // Mettre à jour le contenu
        document.getElementById('modalTitle').textContent = config.title;
        document.getElementById('modalSubtitle').textContent = config.subtitle;
        document.getElementById('modalMessage').textContent = config.message;
        
        // Mettre à jour l'icône
        const iconElement = document.getElementById('modalIcon');
        iconElement.className = `w-12 h-12 bg-gradient-to-br ${config.iconColor} rounded-xl flex items-center justify-center animate-pulse`;
        iconElement.innerHTML = `<i class="fas fa-${config.icon} text-white text-xl"></i>`;
        
        // Mettre à jour le bouton de confirmation
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.innerHTML = `<i class="fas fa-check mr-2"></i>${config.confirmText}`;
        confirmBtn.className = `px-6 py-3 bg-gradient-to-r ${config.confirmColor} hover:opacity-90 text-white rounded-2xl font-semibold transition-all duration-300 shadow-lg`;
        
        // Configurer l'action de confirmation
        confirmBtn.onclick = () => {
            if (config.onConfirm) {
                config.onConfirm();
            }
            this.hide();
        };
        
        // Afficher le modal avec animation
        this.modal.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    hide() {
        const modalContent = document.getElementById('modalContent');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            this.modal.classList.add('hidden');
        }, 300);
    }
}

// Fonctions globales pour différents types de confirmations
function confirmDelete(message, onConfirm) {
    confirmModal.show({
        title: 'Suppression',
        subtitle: 'Action irréversible',
        message: message || 'Êtes-vous sûr de vouloir supprimer cet élément ?',
        icon: 'trash',
        iconColor: 'from-red-500 to-pink-500',
        confirmText: 'Supprimer',
        confirmColor: 'from-red-600 to-pink-600',
        onConfirm: onConfirm
    });
}

function confirmArchive(message, onConfirm) {
    confirmModal.show({
        title: 'Archivage',
        subtitle: 'Action de gestion',
        message: message || 'Êtes-vous sûr de vouloir archiver cet élément ?',
        icon: 'archive',
        iconColor: 'from-blue-500 to-indigo-500',
        confirmText: 'Archiver',
        confirmColor: 'from-blue-600 to-indigo-600',
        onConfirm: onConfirm
    });
}

function confirmSend(message, onConfirm) {
    confirmModal.show({
        title: 'Envoi',
        subtitle: 'Action de communication',
        message: message || 'Êtes-vous sûr de vouloir envoyer ce message ?',
        icon: 'paper-plane',
        iconColor: 'from-green-500 to-emerald-500',
        confirmText: 'Envoyer',
        confirmColor: 'from-green-600 to-emerald-600',
        onConfirm: onConfirm
    });
}

// Initialiser le modal
let confirmModal;
document.addEventListener('DOMContentLoaded', function() {
    confirmModal = new ConfirmModal();
});

// Styles CSS pour les animations
const modalStyles = `
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
`;

// Ajouter les styles au head
const styleSheet = document.createElement('style');
styleSheet.textContent = modalStyles;
document.head.appendChild(styleSheet);
