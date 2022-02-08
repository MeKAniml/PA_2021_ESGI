<!--Footer-->
  </div>
    <section class="mt-5">
      <footer class=" footer footerposition text-center bg-light border  ">
        <div class="container p-3">
          <div class="row">

            <!-- Information du site -->
            <div class="col-md-4">
              <h5 class="footercolor">Informations</h5>
              <ul class="list-unstyled">
                <li>
                  <a class="grey" href="/Images/dossier_utilisateur.pdf">Aide & Supports</a>
                </li>
                <li>
                  <a class="grey" style="color: #464e56"href="easteregg.php">Surprise</a>
                </li>
              </ul>
            </div>

            <!-- Menu de navigation -->
            <div class="col-md-4">
              <h5 class="footercolor">Menu</h5>
              <ul class="list-unstyled">
                <li>
                  <a class="grey" href="index.php">Home</a>
                </li>
                <li>
                  <a class="grey" href="index.php#game">Jeux</a>
                </li>
                <li>
                  <a class="grey" href="Game.php">Mini-Jeu</a>
                </li>
                <li>
                  <a class="grey" href="showMatchs.php">Matchs</a>
                </li>
                <li>
                  <a class="grey" href="ShowTeam.php">Equipes</a>
                </li>
              </ul>
            </div>

            <!-- Formulaire de contact -->
            <div class="col-md-4">
              <h5 class="footercolor">Contact</h5>
              <button type="button" class="btn btn-primary mt-3 " data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Nous contacter</button>
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-center" id="exampleModalLabel">Formulaire de Contact</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>




                    <?php
                      if (count($_POST) == 3
                      && !empty($_POST["username"])
                      && !empty($_POST["email"])
                      && !empty($_POST["message"])
                      ) {

                        $username = trim($_POST["username"]);
                        $email = mb_strtolower(trim($_POST["email"]));
                        $message = trim($_POST["message"]);

                        $listOfErrors = [];

                        if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                          echo '<div class="alert alert-danger">Adresse email invalide</div>';
                          $listOfErrors = 'Adresse email invalide';
                        }
                        if (strlen($message)>500) {
                          echo '<div class="alert alert-danger">Votre message doit faire moins de 1000 caractères</div>';
                          $listOfErrors = 'Votre message doit faire moins de 200 caractères';
                        }

                        if (empty($listOfErrors)) {
                          $connection = connectDB();
                          $queryPrepared =  $connection->prepare("INSERT INTO ".PRE."UserMessage (Username, Email, Message) VALUES ( :Username, :Email , :Message);");
                          $queryPrepared->execute(["Username"=>$username, "Email"=>$email, "Message"=>$message]);
                          echo '<div class="alert alert-sucess">Message envoyé</div>';
                        }

                      }
                    ?>

                    <div class="modal-body">
                      <form method="POST">
                        <div class="form-group">
                          <div class="col-auto">
                            <div class="input-group mb-2">
                              <div class="input-group-prepend">
                                <div class="input-group-text">#</div>
                              </div>
                              <input type="text" class="form-control formStylefooter" required="required" name="username" placeholder="Username">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-auto">
                              <input type="email" class="form-control formStylefooter" required="required" name="email" placeholder="name@example.com">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-auto">
                              <textarea class="form-control formStylefooter" name="message" required="required" rows="3" placeholder="Dites nous tout ..."></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                          <button type="Submit" value="Submit" class="btn btn-primary">Envoyer</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!--Copyright-->

        <div class="footer-copyright text-center mb-0 py-3 bg-dark flex-shrink-0 footercolor">
          Copyright © 2021 :
          <img class="mb-2 sizeLogo mx-1 " src="Images/logo + nom blanc.svg" alt="PLAY">
          Tous droits réservés.
        </div>

      </footer>
    </section>

  <!--  <script src="CSS\bootstrap-4.6.0-dist\js\Backoffice.js" charset="utf-8"></script>  -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  </body>
</html>
