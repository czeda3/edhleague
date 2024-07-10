<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
  <section class="bg-light py-3 py-md-5">
    <div class="container">
      <?php
        if(isset($_POST["submit"])){
          $firstName = $_POST["firstName"];
          $lastName = $_POST["lastName"];
          $email = $_POST["email"];
          $password = $_POST["password"];
          $confirmPassword = $_POST["confirmPassword"];

          $passwordHash = password_hash($password, PASSWORD_DEFAULT);


          //error check
          $errors = array();
          if(empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)){
            $errors[] = "All fields are required";
          }
          if ($firstName == '' || $lastName == ''){
            $errors[] = "First name and last name can not be empty";
          }
          if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Email is not valid";
          }
          if(strlen($password) < 8){
            $errors[] = "Password must be at least 8 characters long";
          }
          if($password !== $confirmPassword){
            $errors[] = "Password does not match";
          }
          
          require_once "connectdb.php";
          //NOT INJECTION SAFE HERE
          $sql = "SELECT email FROM users WHERE email = '$email';";
          $result = $conn->query($sql);
          if($result->num_rows > 0){
            $errors[] = "Email already exists";
          }

          if(count($errors) > 0){
            foreach($errors as $error){
              echo "<div class='row justify-content-center'>";
              echo "<div class='col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4'>";
                echo "<div class='alert alert-danger'>$error</div>";
              echo "</div>";
              echo "</div>";
            }
          } else {
            $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            $prepareStatement =  mysqli_stmt_prepare($stmt, $sql);
            if($prepareStatement){
              mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $passwordHash);
              mysqli_stmt_execute($stmt);
              echo "<div class='row justify-content-center'>";
              echo "<div class='col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4'>";
                echo "<div class='alert alert-success'>Registration successful!</div>";
              echo "</div>";
              echo "</div>";
            }else{
              echo "<div class='alert alert-danger'>Something went wrong</div>";
              die("Something went wrong");
            }
          }

        }
      ?>
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
          <div class="card border border-light-subtle rounded-3 shadow-sm">
            <div class="card-body p-3 p-md-4 p-xl-5">
              <div class="text-center mb-3">
                <div>LOGO PLACEHOLDER</div>
              </div>
              <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Enter your details to register</h2>
              <form action="registration.php" method="post">
                <div class="row gy-2 overflow-hidden">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" required>
                      <label for="firstName" class="form-label">First Name</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name" required>
                      <label for="lastName" class="form-label">Last Name</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                      <label for="email" class="form-label">Email</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                      <label for="password" class="form-label">Password</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" value="" placeholder="Password" required>
                      <label for="confirmPassword" class="form-label">Confirm password</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                      <label class="form-check-label text-secondary" for="iAgree">
                        I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                      </label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid my-3">
                      <button class="btn btn-primary btn-lg" type="submit" name="submit">Sign up</button>
                    </div>
                  </div>
                  <div class="col-12">
                    <p class="m-0 text-secondary text-center">Already have an account? <a href="#!" class="link-primary text-decoration-none">Sign in</a></p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>