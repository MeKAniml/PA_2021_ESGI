<?php
	include "header.php";

	if ( $_SESSION["auth"]==true ){
	  header('Location:index.php');
	  exit;
	}
?>
<div class="container">
  <div class="row">
    <div class="box shadow border col-md-4 p-3 mt-5">
        <h1 class="text-green" style="font-family: Roboto" > PLAY </h1>
        <h4> S'inscrire </h4>

				<?php if(isset($_SESSION["listOfErrors"])){ ?>

		<div class="alert alert-danger" >
			<?php

				foreach ($_SESSION["listOfErrors"] as $error) {
					echo "<li>".$error;
				}

				unset($_SESSION["listOfErrors"]);
			?>
		</div>

	<?php } ?>

        <form action="register.php" method="POST">

					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Open modal for @mdo</button>

	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <form>
	          <div class="mb-3">
	            <label for="recipient-name" class="col-form-label">Recipient:</label>
	            <input type="text" class="form-control" id="recipient-name">
	          </div>
	          <div class="mb-3">
	            <label for="message-text" class="col-form-label">Message:</label>
	            <textarea class="form-control" id="message-text"></textarea>
	          </div>
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Send message</button>
	      </div>
	    </div>
	  </div>
	</div>
        </form>
      </div>
  </div>
</div>


<?php
	include "footer.php";
?>
