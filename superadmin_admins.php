<?php 
    session_start();

    // Check if user is logged in
        if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'superadmin') { 
        require "db_conn.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $user_type = $_POST['user_type'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $suffix = $_POST['suffix'];
        $age = $_POST['age'];
        $birthdate = $_POST['birthdate'];
        $birthplace = $_POST['birthplace'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $contact_number = $_POST['contact_number'];
        $citizenship = $_POST['citizenship'];
        $occupation = $_POST['occupation'];
        $civil_status = $_POST['civil_status'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `users-table` 
                (`id`,`user_type`, `last_name`, `first_name`, `middle_name`, `suffix`, `age`, `birthdate`, `birthplace`, `gender`, `address`, `contact_number`, `citizenship`, `occupation`, `civil_status`, `email`, `password`) 
            VALUES 
                (NULL, '$user_type', '$last_name', '$first_name', '$middle_name', '$suffix', '$age', '$birthdate', '$birthplace', '$gender', '$address', '$contact_number', '$citizenship', '$occupation', '$civil_status', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } 
    /* <<<<---- ADD NOTIFICATIONS HERE --->>> */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="CSS/user.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/barangayicon.ico" />
    <title>Kamias Connect</title>
</head>
<body class="body-bg">
    <section class="main-vh d-flex">
        <section class="container text-start align-items-center m-auto">
            <div class="bg-light p-4 rounded">         
                <button class="btn btn-outline-success" onclick="location.href='/login.php';"><i class="fa-solid fa-arrow-left"></i></button>
                <p class="display-5 text-center">REGISTER AN ADMIN</p>
                <form action="" method="POST" autocomplete="off">
                    <!-- NAME ROW -->
                    <div class="row form-outline mb-4">
                        <div class="col-md-4 mb-1">
                            <label for="last_name" class="form-label text-success fw-bold">Last Name:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="first_name" class="form-label text-success fw-bold">First Name:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="middle_name" class="form-label text-success fw-bold">Middle Name:</label>
                            <input type="text" class="form-control" name="middle_name" id="middle_name">
                        </div>
                        <div class="col-md-1 mb-1">
                            <label for="suffix" class="form-label text-success fw-bold">Suffix:</label>
                            <input type="text" class="form-control" name="suffix" id="suffix">
                        </div>
                    </div>
                    <!-- AGE ROW -->
                    <div class="row form-outline mb-4">
                        <div class="col-md-1 mb-1">
                            <label for="age" class="form-label text-success fw-bold">Age:</label>
                            <input type="text" class="form-control" name="age" id="age">
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="birthdate" class="form-label text-success fw-bold">Birthday:</label>
                            <input type="date" class="form-control" name="birthdate" id="birthdate">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="birthplace" class="form-label text-success fw-bold">Birthplace:</label>
                            <input type="text" class="form-control" name="birthplace" id="birthplace">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="gender" class="form-label text-success fw-bold">Gender:</label>
                            <select class="form-select" name="gender" id="gender" aria-label="Default select example">
                                <option disabled selected>Please select...</option>
                               <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <!-- ADDRESS ROW -->
                    <div class="row form-outline mb-4">
                        <div class="col-md-6 mb-1">
                            <label for="address" class="form-label text-success fw-bold">Address:</label>
                            <input type="text" class="form-control" name="address" id="address">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="contact_number" class="form-label text-success fw-bold">Mobile Number:</label>
                            <input type="text" class="form-control" name="contact_number" id="contact_number">
                        </div>
                    </div>
                    <!-- CITIZENSHIP | OCCUPATION | CIVIL STATUS ROW -->
                    <div class="row form-outline mb-4">                            
                        <div class="col-md-4 mb-1">
                            <label for="citizenship" class="form-label text-success fw-bold">Citizenship:</label>
                            <select class="form-select" name="citizenship" id="citizenship">
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Eswatini">Eswatini</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="North Korea">North Korea</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Kosovo">Kosovo</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Palestine">Palestine</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines" selected>Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value="Saint Lucia">Saint Lucia</option>
                                <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Sudan">South Sudan</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-Leste">Timor-Leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City">Vatican City</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="occupation" class="form-label text-success fw-bold">Occupation:</label>
                            <select class="form-select" name="occupation" id="occupation" style="width: 100%;">                                
                                <option disabled selected>Please select...</option>
                                <option value="Accountant">Accountant</option>
                                <option value="Architect">Architect</option>
                                <option value="Businessman/Businesswoman">Businessman/Businesswoman</option>
                                <option value="Call center agent">Call center agent</option>
                                <option value="Chef/Cook">Chef/Cook</option>
                                <option value="Freelancer">Freelancer</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Driver">Driver</option>
                                <option value="Electrician">Electrician</option>
                                <option value="Farmer">Farmer</option>
                                <option value="Government Employee">Government Employee</option>
                                <option value="IT Professional">IT Professional</option>
                                <option value="Lawyer">Lawyer</option>
                                <option value="Nurse">Nurse</option>
                                <option value="OFW (Overseas Filipino Worker)">OFW (Overseas Filipino Worker)</option>
                                <option value="Police Officer">Police Officer</option>
                                <option value="Salesperson">Salesperson</option>
                                <option value="Student">Student</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Retired">Retired</option>
                                <option value="Unemployed">Unemployed</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="civil_status" class="form-label text-success fw-bold">Civil Status:</label>
                            <select class="form-select" name="civil_status" id="civil_status" aria-label="Default select example">
                                <option disabled selected>Please select...</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Legaly Separated">Legaly Separated</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                    </div> 
                    <!-- EMAIL | PW | RepPW ROW -->
                    <div class="row form-outline mb-4">
                        <div class="col-md-4 mb-1">
                            <label for="email" class="form-label text-success fw-bold">Email Address:</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="col-md-4 mb-1">
                            <label class="form-label text-success fw-bold" for="password">Password: </label>
                            <input type="password" name="password" id="password" class="form-control" />                               
                        </div>
                        <div class="col-md-4 mb-1">
                            <label class="form-label text-success fw-bold" for="registerRepeatPassword">Repeat password: </label>
                            <input type="password" name="registerRepeatPassword" id="registerRepeatPassword" class="form-control" />                                
                        </div>
                    </div>
                    <!-- BUTTON AND USER TYPE -->
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <button type="submit" class="btn btn-outline-success" style="width: 100%;">Submit</button>
                    </div>
                    <div class="d-none"> 
                        <input type="text" class="form-control" name="user_type" id="user_type" value="admin">
                    </div>
                </form>
            </div>
        </section>
    </section>

    <!-- BOOTSTRAP -->    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</body>
</html>
<?php 
} else {
    header("Location: login.php");
    exit();
}
?>