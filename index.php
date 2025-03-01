<?php
session_start();
require_once 'config.php';

$ludothequesStatement = $mysqlClient->prepare('SELECT * FROM ludotheques');
$ludothequesStatement->execute();
$ludotheques = $ludothequesStatement->fetchAll();
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
        <div>
            <input type="text" placeholder="Rechercher des Ludothèques ou des Jeux"></input>
            <ul>
                <li>Les Nouvelles</li>
                <li>Les + Suivis</li>
                <li>Les + Commentées</li>
                <li>Au Pif</li>
            </ul>
            <?php foreach ($ludotheques as $ludotheque) {
            ?>
                <p><?php echo $ludotheque['user_id']; ?></p>
            <?php
            }
            ?>
        </div>
        <?php require_once(__DIR__ . '/gamesList.php'); ?>
    </main>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>