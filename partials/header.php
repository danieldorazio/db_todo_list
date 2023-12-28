<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">HOME</a>
        </li>
      </ul>

      <!-- è visibile soltanto se l'utente è loggato -->
      <?php if (!empty($_SESSION['username']) && !empty($_SESSION['user_id'])) { ?>
        <div class="d-flex me-4">
          <span class="me-4">Ciao <?php echo $_SESSION['username']; ?></span>

          <!-- BOTTONE DI LOGOUT -->
          <form action="logout.php" method="POST">
            <input type="hidden" type="text" value="1" name="logout">
            <button type="submit" class="btn btn-danger">Logout</button>
          </form>
        </div>
      <?php } ?>

    </div>
  </div>
</nav>