<!-- Boîte de dialogue de suppression réutilisable -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50" style="display: none !important;">
    <div class="bg-gradient-to-br from-red-900 via-pink-900 to-purple-900 rounded-3xl p-8 border border-white/20 shadow-2xl max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
            </div>
            
            <h3 class="text-2xl font-black text-white mb-4">
                Confirmer la suppression
            </h3>
            
            <p class="text-gray-300 mb-6" id="deleteMessage">
                Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.
            </p>
            
            <div class="flex justify-center space-x-4">
                <button onclick="closeDeleteModal()" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <i class="fas fa-times mr-2"></i>Annuler
                </button>
                <button onclick="confirmDelete()" class="bg-gradient-to-r from-red-600 to-pink-600 text-white px-6 py-3 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-red-500/50">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// S'assurer que le JavaScript est disponible globalement
window.showDeleteModal = function(id, message, formId = null) {
    console.log('showDeleteModal appelé avec:', { id, message, formId });
    
    // Fermer toute modal existante
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    window.currentDeleteId = id;
    window.currentDeleteForm = formId;
    
    const messageEl = document.getElementById('deleteMessage');
    if (messageEl) {
        messageEl.textContent = message;
    }
    
    if (modal) {
        modal.style.display = 'flex';
    }
};

window.closeDeleteModal = function() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
    window.currentDeleteId = null;
    window.currentDeleteForm = null;
};

window.confirmDelete = function() {
    console.log('confirmDelete appelé');
    
    if (window.currentDeleteForm) {
        const form = document.getElementById(window.currentDeleteForm);
        if (form) {
            form.submit();
            return;
        }
    }
    
    if (window.currentDeleteId) {
        const form = document.getElementById(`deleteForm-${window.currentDeleteId}`);
        if (form) {
            form.submit();
            return;
        }
    }
};

// Initialiser les écouteurs d'événements
document.addEventListener('DOMContentLoaded', function() {
    console.log('Delete modal initialisé');
    
    // Fermer la modal en cliquant à l'extérieur
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeDeleteModal();
            }
        });
    }
    
    // Fermer la modal avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && (window.currentDeleteId || window.currentDeleteForm)) {
            window.closeDeleteModal();
        }
    });
});
</script>
