<!DOCTYPE html>
<html>
<head>
    <title>Test Suppression</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-8">
    <h1 class="text-2xl mb-4">Test de suppression</h1>
    
    <div class="mb-4">
        <p>Testez la suppression avec ce formulaire simple :</p>
        
        <form action="/inventories/1" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded">
                Supprimer l'inventaire #1 (Test direct)
            </button>
        </form>
    </div>
    
    <div class="mb-4">
        <p>Testez avec la modal :</p>
        <button onclick="showDeleteModal(1, 'Êtes-vous sûr de vouloir supprimer cet inventaire ?', 'testForm')" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded">
            Ouvrir la modal de test
        </button>
        
        <form id="testForm" action="/inventories/1" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
    
    <!-- Inclure le modal de suppression -->
    @include('components.delete-modal')
    
    <script>
        // Pour tester sans Laravel
        function showDeleteModal(id, message, formId) {
            console.log('showDeleteModal appelé avec:', { id, message, formId });
            
            window.currentDeleteId = id;
            window.currentDeleteForm = formId;
            
            const messageEl = document.getElementById('deleteMessage');
            if (messageEl) {
                messageEl.textContent = message;
            }
            
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            
            if (modal && content) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                alert('Modal non trouvé');
            }
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            
            if (modal && content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    window.currentDeleteId = null;
                    window.currentDeleteForm = null;
                }, 300);
            }
        }
        
        function confirmDelete() {
            console.log('confirmDelete appelé');
            
            if (window.currentDeleteForm) {
                const form = document.getElementById(window.currentDeleteForm);
                if (form) {
                    console.log('Soumission du formulaire:', window.currentDeleteForm);
                    form.submit();
                    return;
                }
            }
            
            if (window.currentDeleteId) {
                const form = document.getElementById(`deleteForm-${window.currentDeleteId}`);
                if (form) {
                    console.log('Soumission par ID:', `deleteForm-${window.currentDeleteId}`);
                    form.submit();
                    return;
                }
            }
            
            alert('Formulaire non trouvé');
        }
    </script>
    
    <!-- Modal de suppression -->
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
</body>
</html>
