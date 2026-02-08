<!-- Boîte de dialogue de suppression simplifiée -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
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
// Variables globales
var deleteModalId = null;
var deleteModalForm = null;

function showDeleteModal(id, message, formId) {
    deleteModalId = id;
    deleteModalForm = formId;
    document.getElementById('deleteMessage').textContent = message;
    
    var modal = document.getElementById('deleteModal');
    var content = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(function() {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    var modal = document.getElementById('deleteModal');
    var content = document.getElementById('deleteModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(function() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        deleteModalId = null;
        deleteModalForm = null;
    }, 300);
}

function confirmDelete() {
    if (deleteModalForm) {
        document.getElementById(deleteModalForm).submit();
    } else if (deleteModalId) {
        document.getElementById('deleteForm-' + deleteModalId).submit();
    }
}

// Fermer la modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    var modal = document.getElementById('deleteModal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});

// Fermer la modal avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && (deleteModalId || deleteModalForm)) {
        closeDeleteModal();
    }
});
</script>
