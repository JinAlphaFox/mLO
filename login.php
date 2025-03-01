<?php
session_start();
require_once 'config.php';

$postData = $_POST;

if (isset($postData['mail']) &&  isset($postData['password'])) {
    if (!filter_var($postData['mail'], FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Il faut un mail valide pour soumettre le formulaire.';
    } else {
        $usersStatement = $mysqlClient->prepare('SELECT * FROM users WHERE mail = :mail');
        $usersStatement->execute(['mail' => $postData['mail']]);
        $user = $usersStatement->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($postData['password'], $user['password'])) {
                $_SESSION['LOGGED_USER'] = [
                    'mail' => $user['mail'],
                    'pseudo' => $user['pseudo'],
                ];
        } else {
            $errorMessage = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myLudothequeOnline</title>
</head>
<body>
    <?php require_once(__DIR__ . '/header.php'); ?>
    <main>
        <?php require_once(__DIR__ . '/myLudo.php'); ?>
        <?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
            <form action="login.php" method="POST">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="mail" class="form-label">Email</label>
                    <input type="mail" class="form-control" id="mail" name="mail" aria-describedby="mail-help" placeholder="you@exemple.com">
                    <div id="mail-help" class="form-text">L'mail utilisé lors de la création de compte.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        <?php else : ?>
            <div class="alert alert-success" role="alert">
                Bonjour <?php echo $_SESSION['LOGGED_USER']['pseudo']; ?> et bienvenue sur le site !<br />
                <a href="index.php">Retour à la page d'accueil</a>
                <?php header('Location: index.php'); ?>
            </div>
        <?php endif; ?>
        <?php require_once(__DIR__ . '/gamesList.php'); ?>
    </main>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>