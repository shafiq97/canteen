<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/login.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <title>Customer Registration | EATERIO</title>
</head>

<body class="d-flex flex-column">
    <header class="navbar navbar-light fixed-top bg-light shadow-sm mb-auto">
        <div class="container-fluid mx-4">
            <a href="index.php">
                <img src="https://www.gmi.edu.my/wp-content/uploads/2019/06/logo-gmi-header.png" width="125" class="me-2" alt="EATERIO Logo">
            </a>
        </div>
    </header>
    <div class="container mt-4"></div>
    <div class="container form-signin mt-auto">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <div id="nfcPrompt" style="display: none;">
            <p class="alert alert-info">Please tap your NFC card</p>
        </div>
        <div id="nfcData"></div>
        <form method="POST" action="add_cust.php" class="form-floating">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-person-plus me-2"></i>Sign Up</h2>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" minlength="5" maxlength="45" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="pwd" placeholder="Password" name="pwd" minlength="8" maxlength="45" required>
                <label for="pwd">Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="cfpwd" placeholder="Confirm Password" minlength="8" maxlength="45" name="cfpwd" required>
                <label for="cfpwd">Confirm Password</label>
                <div id="passwordHelpBlock" class="form-text smaller-font">
                    Your password must be at least 8 characters long.
                </div>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname" required>
                <label for="firstname">First Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lastname" required>
                <label for="lastname">Last Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" placeholder="E-mail" name="email" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating">
                <select class="form-select mb-2" id="gender" name="gender">
                    <option selected value="-">---</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="N">Non-binary</option>
                </select>
                <label for="gender">Your Gender</label>
            </div>
            <div class="form-floating">
                <select class="form-select mb-2" id="type" name="type">
                    <option selected value="-">---</option>
                    <option value="STD">Student</option>
                    <option value="PRT">Parent</option>
                    <option value="INS">Professor</option>
                    <option value="TAS">Teaching Assistant</option>
                    <option value="STF">Faculty Staff</option>
                    <option value="GUE">Visitor</option>
                    <option value="OTH">Other</option>
                </select>
                <label for="gender">Your role</label>
            </div>
            <div class="form-floating mb-2" id="studentListContainer" style="display: none;">
                <select class="form-select" id="studentList">
                    <!-- Student options will be dynamically added here -->
                </select>
                <!-- <label for="studentList">Select Child</label> -->
            </div>

            <div class="form-floating">
                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input " id="tandc" name="tandc" required>
                    <label class="form-check-label small" for="tandc">I agree to the terms and conditions and the
                        privacy policy</label>
                </div>
            </div>
            <button class="w-100 btn btn-success mb-3" type="submit">Sign Up</button>
        </form>
    </div>
    <div class="container mt-4"></div>
    <footer class="footer d-flex flex-wrap justify-content-between align-items-center px-5 py-3 mt-auto bg-secondary text-light">
        <span class="smaller-font">&copy; 2023 German Malaysian Institue<br /><span class="xsmall-font">Rezza</span></span>
        <ul class="nav justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a class="text-light" target="_blank" href=""><i class="bi bi-github"></i></a></li>
        </ul>
    </footer>
    <script>
        $(document).ready(function() {
            $('#studentList').select2({
                placeholder: "Select a student",
                allowClear: true
            });
        });

        document.getElementById('type').addEventListener('change', function() {
            var typeSelect = document.getElementById('type');
            var nfcPrompt = document.getElementById('nfcPrompt');
            if (typeSelect.value === 'STD') {
                nfcPrompt.style.display = 'block';
            } else {
                nfcPrompt.style.display = 'none';
            }
            if (typeSelect.value === 'PRT') {
                loadAndDisplayStudents();
                studentListContainer.style.display = 'block'; // Show student list
            } else {
                studentListContainer.style.display = 'none'; // Hide student list
            }
        });

        function loadAndDisplayStudents() {
            fetch('get_students.php') // Adjust the URL to your PHP script
                .then(response => response.json())
                .then(students => {
                    var studentList = document.getElementById('studentList');
                    studentList.innerHTML = ''; // Clear existing list

                    students.forEach(student => {
                        var option = document.createElement('option');
                        option.value = student.c_id; // Use 'c_id' for the value
                        option.textContent = student.c_username; // Use 'c_username' for the text
                        studentList.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
        }


        function checkForNfcData() {
            fetch('http://localhost:5001/get_uid')
                .then(response => response.json())
                .then(data => {
                    if (data.uid) {
                        // Convert UID to string and remove commas
                        var uidString = String(data.uid).replace(/,/g, '');
                        document.getElementById('username').value = uidString;
                    }
                })
                .catch(error => {
                    console.error('Error fetching NFC data:', error);
                });
        }

        setInterval(checkForNfcData, 1000); // Check every 2 seconds
    </script>

</body>

</html>