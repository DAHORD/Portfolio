<?php
// On vérifie si le formulaire a été soumis en utilisant la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // === Adresses e-mail ===
    $destinataire = "killian.pub@free.fr"; 
    $expediteur = "killian.pub@free.fr"; 

    // === Récupération et nettoyage des données ===
    // On utilise trim() pour supprimer les espaces inutiles au début et à la fin.
    // On n'utilise PAS htmlspecialchars() pour le corps du message.
    $nom = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // === Validation des données ===
    if (empty($nom) || empty($email) || empty($message)) {
        echo "Erreur : Tous les champs sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erreur : L'adresse email n'est pas valide.";
        exit;
    }

    // === Construction de l'e-mail (plus sécurisé) ===
    // On nettoie le sujet pour éviter les injections d'en-têtes
    $sujet = "Nouveau message de " . str_replace(["\r", "\n"], '', $nom);

    // Corps du message (les apostrophes et guillemets seront corrects ici)
    $contenu_email = "Vous avez reçu un nouveau message depuis votre formulaire de contact.\n\n";
    $contenu_email .= "Nom : " . $nom . "\n";
    $contenu_email .= "Email : " . $email . "\n\n";
    $contenu_email .= "Message :\n" . $message . "\n";

    // === Création des en-têtes de l'e-mail ===
    // On utilise le nom nettoyé dans l'en-tête "From" pour la sécurité
    $nom_header = str_replace(["\r", "\n"], '', $nom);
    $headers = "From: " . $nom_header . " <" . $expediteur . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // === Envoi de l'e-mail ===
    if (mail($destinataire, $sujet, $contenu_email, $headers)) {
        // Si l'envoi a réussi
        echo "<h1>Message envoyé avec succès !</h1>";
        echo "<p>Merci de m'avoir contacté. Je vous répondrai dans les plus brefs délais.</p>";
        echo '<a href="index.html" style="color: #3B82F6;">Retour au site</a>';
    } else {
        // Si l'envoi a échoué
        echo "<h1>Erreur lors de l'envoi.</h1>";
        echo "<p>Désolé, une erreur est survenue. Veuillez réessayer plus tard ou me contacter via un autre moyen.</p>";
        echo '<a href="index.html" style="color: #EC4899;">Retour au site</a>';
    }

} else {
    // Accès direct au fichier non autorisé
    echo "<h1>Accès non autorisé.</h1>";
    echo '<p>Veuillez utiliser le formulaire de contact.</p>';
    echo '<a href="index.html" style="color: #8B5CF6;">Retour au site</a>';
}
?>