# Guide de Création PowerPoint Automatique

## 🎯 Étape 1: Créer la Présentation

### Ouvrir PowerPoint
1. Lancez PowerPoint
2. Choisissez "Nouvelle présentation"
3. Enregistrez immédiatement sous: `demo_systeme_stock.pptx`

### Configuration du Design
1. **Onglet Création** → **Thèmes** → Choisissez un thème bleu professionnel
2. **Onglet Création** → **Mise en page** → Choisissez "Titre et contenu"

## 📸 Étape 2: Captures d'Écran Automatiques

### Script PowerShell pour captures automatiques
```powershell
# Script PowerShell pour captures d'écran automatiques
Add-Type -AssemblyName System.Windows.Forms
Add-Type -AssemblyName System.Drawing

function Take-Screenshot {
    param($path)
    $bounds = [System.Windows.Forms.Screen]::PrimaryScreen.Bounds
    $bmp = New-Object System.Drawing.Bitmap $bounds.width, $bounds.height
    $graphics = [System.Drawing.Graphics]::FromImage($bmp)
    $graphics.CopyFromScreen($bounds.Location, [System.Drawing.Point]::Empty, $bounds.size)
    $bmp.Save($path, [System.Drawing.Imaging.ImageFormat]::Png)
    $graphics.Dispose()
    $bmp.Dispose()
}

# URLs à capturer (ouvrez ces pages dans votre navigateur)
$urls = @(
    "http://127.0.0.1:8000/login",
    "http://127.0.0.1:8000/dashboard", 
    "http://127.0.0.1:8000/products",
    "http://127.0.0.1:8000/products/create",
    "http://127.0.0.1:8000/movements",
    "http://127.0.0.1:8000/alerts",
    "http://127.0.0.1:8000/api/documentation"
)

Write-Host "Ouvrez chaque URL dans votre navigateur puis appuyez sur ESPACE pour capturer..."
foreach($url in $urls) {
    Write-Host "URL: $url"
    Read-Host "Appuyez sur ESPACE pour capturer cette page"
    Take-Screenshot -path "screenshot_$($urls.IndexOf($url)).png"
}
```

### Captures manuelles rapides
1. **Ouvrez votre application** sur `http://127.0.0.1:8000`
2. **Utilisez** `Windows + Shift + S` pour chaque capture
3. **Sauvegardez** dans un dossier `captures/`

## 🎨 Étape 3: Création Automatique des Slides

### Utiliser le modèle inclus
1. Copiez le contenu de `PRESENTATION_POWERPOINT_AUTOMATIQUE.md`
2. **Ouvrez** PowerPoint
3. **Coller** le texte dans chaque slide

### Template PowerPoint à télécharger
Je vais créer un fichier PowerPoint template avec:
- 20 slides pré-configurés
- Espaces pour captures d'écran
- Animations prêtes
- Design professionnel

## 🎬 Étape 4: Export Automatique en Vidéo

### Méthode 1: PowerPoint Natif
1. **Fichier** → **Exporter** → **Créer une vidéo**
2. **Paramètres**:
   - Qualité: Full HD (1080p)
   - Utiliser les minutages et animations: Oui
   - Durée: 10 minutes
3. **Créer la vidéo**

### Méthode 2: Script PowerShell d'automatisation
```powershell
# Script pour exporter PowerPoint en vidéo automatiquement
$ppt = New-Object -ComObject PowerPoint.Application
$presentation = $ppt.Presentations.Open("C:\laragon\www\stock\demo_systeme_stock.pptx")
$presentation.CreateVideo("C:\laragon\www\stock\demo_video.mp4")
$presentation.Close()
$ppt.Quit()
```

## 🚀 Étape 5: Finalisation

### Ajouter une narration
1. **Onglet Insertion** → **Audio** → **Enregistrer l'audio**
2. **Enregistrez** votre voix pour chaque slide
3. **Synchronisez** avec les animations

### Musique de fond (optionnelle)
1. **Téléchargez** musique libre de droits
2. **Insérez** → **Audio** → **Audio sur l'ordinateur**
3. **Réglez** le volume à 10-15%

## ⚡ Création Express (5 minutes)

### Si vous avez peu de temps:
1. **Ouvrez** PowerPoint
2. **Insérez** → **Album photo** → Sélectionnez vos captures
3. **Choisissez** le thème "Photo album"
4. **Ajoutez** des titres simples
5. **Exportez** directement en vidéo

## 📋 Checklist Finale

- [ ] Application lancée et fonctionnelle
- [ ] Captures d'écran prêtes
- [ ] PowerPoint créé avec 20 slides
- [ ] Captures insérées dans les slides
- [ ] Animations ajoutées
- [ ] Narration enregistrée (optionnel)
- [ ] Vidéo exportée en MP4
- [ ] Vidéo testée et fonctionnelle

## 🎯 Résultat Final

Vous aurez une vidéo professionnelle de 10 minutes montrant:
- Interface complète de votre application
- Fonctionnalités principales
- API documentation
- Avantages techniques
- Conclusion percutante

**Temps total estimé**: 30-45 minutes
**Qualité**: Professionnelle
**Format**: MP4 Full HD

Voulez-vous que je crée le fichier PowerPoint template directement ?
