  <!-- Navbar -->
  <div class="topnav">
    <!-- Logo -->
    <a class="logo-container"href="index.php">
      <img src="img/BigFunckingProject.png" alt="Logo BFP">
    </a>

    <a href="index.php">Home</a>
      <a href="cam.php">Caméras</a>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
      <a href="chauffage.php">Chauffage</a>
      <a href="data.php">Données</a>
      <div class="login-container">
        <form action="logout.php" method="post">
          <button type="submit">Déconnexion</button>
        </form>
      </div>
    <?php else: ?>
      <div class="login-container">
        <form action="login.php" method="post">
          <input type="text" placeholder="Username" name="Getusername" required>
          <input type="password" placeholder="Password" name="Getpsw" required>
          <button type="submit">Connexion</button>
        </form>
        <div id="errorMessage" style="display:none; color:red; text-align:center;">
          Nom d’utilisateur ou mot de passe incorrect.
        </div>
      </div>

    <?php endif; ?>
  </div>
  <h1><?=$title?></h1>
<div id="mySidenav" class="sidenav">
  <a href="#" id="blog">Blog</a>
  <a href="#" id="projects">Projects</a>
  <a href="#" id="contact">Contact</a>
  <a href="#" id="theme">
    <label for="darkModeToggle">Thème </label>
      <label class="switch">
        <input type="checkbox" id="darkModeToggle">
        <span class="slider round"></span>
    </label>
  </a>
</div>