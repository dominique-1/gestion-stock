<!DOCTYPE html>
<html>
<head>
    <title>Test Formulaire Simple</title>
</head>
<body>
    <h1>Test Formulaire Mouvement</h1>
    
    <?php
    session_start();
    
    if ($_POST) {
        echo "<h2>Données reçues :</h2>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        
        // Ajouter à la session
        if (!isset($_SESSION['movements'])) {
            $_SESSION['movements'] = [];
        }
        
        $newMovement = [
            'id' => count($_SESSION['movements']) + 1,
            'product_id' => $_POST['product_id'],
            'type' => $_POST['type'],
            'quantity' => $_POST['quantity'],
            'reason' => $_POST['reason'],
            'moved_at' => $_POST['moved_at'] ?? date('Y-m-d H:i:s'),
        ];
        
        $_SESSION['movements'][] = $newMovement;
        
        echo "<h3>Mouvement ajouté avec succès !</h3>";
        echo "<a href='test-form.php'>Voir la liste</a>";
    } else {
        echo "<h2>Mouvements en session :</h2>";
        if (isset($_SESSION['movements'])) {
            echo "<pre>";
            print_r($_SESSION['movements']);
            echo "</pre>";
        } else {
            echo "Aucun mouvement";
        }
    ?>
    
    <form method="post">
        <h2>Ajouter un mouvement</h2>
        
        <label>Produit:</label><br>
        <select name="product_id" required>
            <option value="">Choisir...</option>
            <option value="1">Laptop Pro 15"</option>
            <option value="2">Moniteur 27"</option>
            <option value="3">Clavier mécanique</option>
        </select><br><br>
        
        <label>Type:</label><br>
        <input type="radio" name="type" value="in" required> Entrée
        <input type="radio" name="type" value="out" required> Sortie<br><br>
        
        <label>Quantité:</label><br>
        <input type="number" name="quantity" min="1" required><br><br>
        
        <label>Date:</label><br>
        <input type="datetime-local" name="moved_at"><br><br>
        
        <label>Motif:</label><br>
        <textarea name="reason"></textarea><br><br>
        
        <button type="submit">Enregistrer</button>
    </form>
    <?php } ?>
</body>
</html>
