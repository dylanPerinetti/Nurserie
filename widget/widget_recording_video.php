<!-- Nouveau widget pour l'enregistrement, visible uniquement pour les utilisateurs connectés -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
    <div id="recording-widget" class="widget">
      <h2>Enregistrement de la Caméra</h2>
      <div id="controls">
        <button id="on-cam" class="inactive">ON</button>
        <button id="off-cam" class="inactive">OFF</button>
      </div>
      <div id="controls">
        <button id="start-recording" class="inactive">Démarrer l'enregistrement</button>
        <button id="stop-recording" class="inactive">Arrêter l'enregistrement</button>
      </div>
    </div>
    <?php endif; ?>