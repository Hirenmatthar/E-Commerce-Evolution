$(document).ready(function() {
    $('.login_btn').click(function(e) {
      e.preventDefault(); // Prevent form submission

      var username = $('input[name="username"]').val();
      var password = $('input[name="password"]').val();

      $.ajax({
        url: 'your-validation-url', // Replace with your server-side validation URL
        type: 'POST', // or 'GET' based on your implementation
        data: {
          username: username,
          password: password
        },
        success: function(response) {
          // Handle the response based on validation result
          if (response.valid) {
            // Successful validation, perform appropriate actions (e.g., redirect)
          } else {
            // Invalid login, display error message or take necessary steps
          }
        },
        error: function() {
          // Handle any errors that occur during the AJAX request
        }
      });
    });
  });
