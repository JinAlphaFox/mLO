<header>
    <h1><a href="index.php">My Ludothèque Online</a></h1>
    <div class="connexion">
    <?php if (isset($_SESSION['LOGGED_USER'])) : ?>
        <span><?php echo $_SESSION['LOGGED_USER']['pseudo']; ?></span>
        <span><a class="nav-link" href="logout.php">Se déconnecter</a></span>
    <?php else : ?>
        <span><a  class="nav-link" href="login.php">Se connecter</a></span>
        <span><a class="nav-link" href="inscription.php">S'inscrire</a></span>
    <?php endif; ?>
    </div>
</header>