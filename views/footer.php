  <footer class="footer">
    <div class="container">
      <span class="text-muted">&copy; My twitter 2021</span>
    </div>
  </footer>


    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="LoginModalTitle">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" id="loginAlert"></div>
        <form action="">
          <input type="hidden" id="LoginActive" name="LoginActive" value="1">
                  <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email address">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <a id="toggleLogin">Sign Up</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="LoginSignUpButton" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <script>
    $("#toggleLogin").click(function(){
      if($("#LoginActive").val() == "1"){
        $("#LoginActive").val("0"); 
        $("#LoginModalTitle").html("Sign Up");
        $("#LoginSignUpButton").html("Sign Up");
        $("#toggleLogin").html("Login");
      } else{
        $("#LoginActive").val("1"); 
        $("#LoginModalTitle").html("Login");
        $("#LoginSignUpButton").html("Login");
        $("#toggleLogin").html("Sign Up");
      }
    });

    $("#LoginSignUpButton").click(function(){
      $.ajax({
        type:"POST",
        url: "actions.php?action=loginSignup",
        data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&LoginActive=" + $("#LoginActive").val(), 
        success: function(result){
          if (result == "1"){
            window.location.assign("home page");
          }
          else{
            $("#loginAlert").html(result).show();
          }
        }

      });
    });
    $(".toggleFollow").click(function(){
      var id = $(this).attr("data-userId");
      $.ajax({
        type:"POST",
        url: "actions.php?action=toggleFollow",
        data: "userId=" + id, 
        success: function(result){
          if (result == "1"){
            $("a[data-userId'" + id + "']").html("Follow")
          } else if (result == "2"){
            $("a[data-userId'" + id + "']").html("Unfollow")
          }
        }

      });
    });

    $("#footerTweetButton").click(function{
      $.ajax({
        type:"POST",
        url: "actions.php?action=postTweet",
        data: "tweetContent=" + $("#tweetContent").val(), 
        success: function(result){
          if (result == "1"){
            $("#tweetSuccess").show();
            $("#tweetFail").hide();
          } else if (result != "1"){
            $("#tweetFail").html(result).show();
            $("#tweetSuccess").hide();
          }
        }

      });
    });
  </script>

  </body>
</html>