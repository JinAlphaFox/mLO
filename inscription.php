<?php
session_start();
require_once 'config.php';

$postData = $_POST;


// Validation du formulaire
if (isset($postData['mail']) &&  isset($postData['pseudo']) &&  isset($postData['password'])) {
    if (!filter_var($postData['mail'], FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Il faut un mail valide pour soumettre le formulaire.';
    } else {
        $usersStatement = $mysqlClient->prepare('SELECT * FROM users WHERE mail = :mail');
        $usersStatement->execute(['mail' => $postData['mail']]);
        $user = $usersStatement->fetchAll();
        if($user) {
            $errorMessage = sprintf(
                'Cet email est déjà enregistré : %s',
                $postData['mail']
            );
        } else {
            $hashed_password = password_hash($postData['password'], PASSWORD_DEFAULT);
            $sqlQuery = 'INSERT INTO users(mail, pseudo, password, created_at) VALUES (:mail, :pseudo, :password, :created_at)';
            $insertUser = $mysqlClient->prepare($sqlQuery);
            $insertUser->execute([
                'mail' => $postData['mail'],
                'pseudo' => $postData['pseudo'],
                'password' => $hashed_password,
                'created_at'=> date('Y-m-d'),
            ]);
            $_SESSION['LOGGED_USER'] = [
                'mail' => $postData['mail'],
                'pseudo' => $postData['pseudo'],
            ];
            if (!isset($_SESSION['LOGGED_USER'])) {
                $errorMessage = sprintf(
                    'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                    $postData['mail'],
                    strip_tags($postData['password'])
                );
            }
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
            <form action="inscription.php" method="POST">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="mail" class="form-label">Email</label>
                    <input type="mail" class="form-control" id="mail" name="mail" aria-describedby="mail-help" placeholder="you@exemple.com">
                    <div id="mail-help" class="form-text">L'email servira lors de la connexion.</div>
                </div>
                <div class="mb-3">
                    <label for="pseudo" class="form-pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" aria-describedby="pseudo-help" placeholder="PataPon">
                    <div id="pseudo-help" class="form-text">Votre pseudo servira à vous retrouver sur le site.</div>
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