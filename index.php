<?php
// ---------------- PHP: HANDLE FORM SUBMISSION ----------------
$success = false;
$applicationId = "APP" . date("ymdHis");   // simple application ID

// Initialise variables
$name = $email = $phone = $gender = $dob = "";
$address = $city = $state = $course = "";
$tenth = $twelfth = $skills = $statement = "";
$hostel = "No";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and sanitize
    $name      = htmlspecialchars(trim($_POST["fullName"] ?? ""));
    $email     = htmlspecialchars(trim($_POST["email"] ?? ""));
    $phone     = htmlspecialchars(trim($_POST["phone"] ?? ""));
    $gender    = htmlspecialchars(trim($_POST["gender"] ?? ""));
    $dob       = htmlspecialchars(trim($_POST["dob"] ?? ""));
    $address   = htmlspecialchars(trim($_POST["address"] ?? ""));
    $city      = htmlspecialchars(trim($_POST["city"] ?? ""));
    $state     = htmlspecialchars(trim($_POST["state"] ?? ""));
    $course    = htmlspecialchars(trim($_POST["course"] ?? ""));
    $tenth     = htmlspecialchars(trim($_POST["tenth"] ?? ""));
    $twelfth   = htmlspecialchars(trim($_POST["twelfth"] ?? ""));
    $skills    = htmlspecialchars(trim($_POST["skills"] ?? ""));
    $statement = htmlspecialchars(trim($_POST["statement"] ?? ""));
    $hostel    = isset($_POST["hostel"]) ? "Yes" : "No";
    $agree     = isset($_POST["agree"]);

    // Simple validation – basic required fields
    if ($name && $email && $phone && $gender && $dob && $city && $course && $agree) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Application Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery (as asked in assignment) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
                         sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top left, #a855f7, #6366f1, #22c55e);
        }

        .card {
            width: 95%;
            max-width: 720px;
            background: #ffffff;
            border-radius: 24px;
            padding: 30px 30px 26px;
            box-shadow: 0 22px 46px rgba(15, 23, 42, 0.25);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 8px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
        }

        .app-id {
            font-size: 12px;
            color: #6b7280;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #4338ca;
            margin: 12px 0 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 16px;
        }

        .field-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #4c1d95;
            margin-bottom: 5px;
        }

        .input-field,
        .select-field,
        textarea {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border-radius: 10px;
            border: 1.6px solid #e5e7eb;
            background: #f9fafb;
            outline: none;
            transition: all 0.18s ease;
        }

        .input-field:focus,
        .select-field:focus,
        textarea:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.16);
        }

        textarea {
            min-height: 70px;
            resize: vertical;
        }

        .inline {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .inline input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        .btn-submit {
            width: 100%;
            border: none;
            padding: 13px 16px;
            border-radius: 999px;
            background: #4338ca;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.05em;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 16px 34px rgba(67, 56, 202, 0.45);
            transition: background 0.12s ease, transform 0.12s ease,
                        box-shadow 0.12s ease;
        }

        .btn-submit:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 10px 22px rgba(79, 70, 229, 0.35);
        }

        .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-bottom: 8px;
            text-align: center;
        }

        /* ------------ SUCCESS POPUP / MODAL ------------- */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 20;
        }

        .overlay.active {
            display: flex;
        }

        .modal {
            background: #ffffff;
            border-radius: 18px;
            width: 90%;
            max-width: 480px;
            padding: 24px 26px 22px;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.5);
        }

        .tick-circle {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 3px solid #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .tick-circle span {
            font-size: 24px;
            color: #22c55e;
        }

        .modal h2 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .modal p.small {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .details {
            font-size: 14px;
            line-height: 1.55;
            margin-bottom: 16px;
        }

        .details span.label {
            font-weight: 600;
        }

        .modal button {
            border: none;
            border-radius: 999px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            background: #4f46e5;
            color: #ffffff;
            cursor: pointer;
            float: right;
        }

        @media (max-width: 640px) {
            .card {
                padding: 24px 18px 22px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="header-row">
        <h1 class="title">Student Application Form</h1>
        <div class="app-id">Application ID: <strong><?php echo $applicationId; ?></strong></div>
    </div>
    <p class="subtitle">Fill in your details below to apply for admission to our institute.</p>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && !$success): ?>
        <p class="error-text">Please fill all the required fields and accept the declaration.</p>
    <?php endif; ?>

    <form id="registrationForm" method="post" action="">
        <!-- PERSONAL INFORMATION -->
        <div class="section-title">Personal Information</div>
        <div class="grid-2">
            <div class="field-group">
                <label for="fullName">Full Name</label>
                <input
                    type="text"
                    id="fullName"
                    name="fullName"
                    class="input-field"
                    placeholder="Enter your full name"
                    value="<?php echo $name; ?>"
                    required>
            </div>

            <div class="field-group">
                <label for="dob">Date of Birth</label>
                <input
                    type="date"
                    id="dob"
                    name="dob"
                    class="input-field"
                    value="<?php echo $dob; ?>"
                    required>
            </div>

            <div class="field-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="select-field" required>
                    <option value="">--Select--</option>
                    <option value="Female" <?php if ($gender=="Female") echo "selected"; ?>>Female</option>
                    <option value="Male" <?php if ($gender=="Male") echo "selected"; ?>>Male</option>
                    <option value="Other" <?php if ($gender=="Other") echo "selected"; ?>>Other</option>
                    <option value="Prefer not to say" <?php if ($gender=="Prefer not to say") echo "selected"; ?>>Prefer not to say</option>
                </select>
            </div>

            <div class="field-group">
                <label for="phone">Phone Number</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="input-field"
                    placeholder="+91 XXXXX XXXXX"
                    value="<?php echo $phone; ?>"
                    required>
            </div>

            <div class="field-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="input-field"
                    placeholder="your@email.com"
                    value="<?php echo $email; ?>"
                    required>
            </div>

            <div class="field-group">
                <label for="hostel">Hostel Required</label>
                <div class="inline">
                    <input type="checkbox" id="hostel" name="hostel" <?php if ($hostel=="Yes") echo "checked"; ?>>
                    <span>Yes, I would like to apply for hostel accommodation.</span>
                </div>
            </div>
        </div>

        <!-- ADDRESS -->
        <div class="section-title">Contact Address</div>
        <div class="grid-2">
            <div class="field-group">
                <label for="address">Address</label>
                <textarea
                    id="address"
                    name="address"
                    placeholder="House no., street, area"
                ><?php echo $address; ?></textarea>
            </div>

            <div>
                <div class="field-group">
                    <label for="city">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        class="input-field"
                        placeholder="City"
                        value="<?php echo $city; ?>"
                        required>
                </div>
                <div class="field-group">
                    <label for="state">State</label>
                    <input
                        type="text"
                        id="state"
                        name="state"
                        class="input-field"
                        placeholder="State"
                        value="<?php echo $state; ?>">
                </div>
            </div>
        </div>

        <!-- ACADEMIC DETAILS -->
        <div class="section-title">Academic Details</div>
        <div class="grid-2">
            <div class="field-group">
                <label for="tenth">10th Percentage / CGPA</label>
                <input
                    type="text"
                    id="tenth"
                    name="tenth"
                    class="input-field"
                    placeholder="e.g. 92.4"
                    value="<?php echo $tenth; ?>">
            </div>

            <div class="field-group">
                <label for="twelfth">12th / Diploma Percentage</label>
                <input
                    type="text"
                    id="twelfth"
                    name="twelfth"
                    class="input-field"
                    placeholder="e.g. 88.5"
                    value="<?php echo $twelfth; ?>">
            </div>
        </div>

        <!-- COURSE & SKILLS -->
        <div class="section-title">Programme & Skills</div>
        <div class="grid-2">
            <div class="field-group">
                <label for="course">Course Applying For</label>
                <select id="course" name="course" class="select-field" required>
                    <option value="">--Select--</option>
                    <option value="B.Tech Computer Engineering" <?php if ($course=="B.Tech Computer Engineering") echo "selected"; ?>>B.Tech Computer Engineering</option>
                    <option value="B.Tech Information Technology" <?php if ($course=="B.Tech Information Technology") echo "selected"; ?>>B.Tech Information Technology</option>
                    <option value="B.Tech Electronics" <?php if ($course=="B.Tech Electronics") echo "selected"; ?>>B.Tech Electronics</option>
                    <option value="BCA" <?php if ($course=="BCA") echo "selected"; ?>>BCA</option>
                    <option value="MCA" <?php if ($course=="MCA") echo "selected"; ?>>MCA</option>
                </select>
            </div>

            <div class="field-group">
                <label for="skills">Key Skills</label>
                <textarea
                    id="skills"
                    name="skills"
                    placeholder="e.g. C++, Java, Web Design, Python..."
                ><?php echo $skills; ?></textarea>
            </div>
        </div>

        <!-- STATEMENT & DECLARATION -->
        <div class="section-title">Statement & Declaration</div>
        <div class="field-group">
            <label for="statement">Why do you want to join this course?</label>
            <textarea
                id="statement"
                name="statement"
                placeholder="Briefly describe your interest in this programme..."
            ><?php echo $statement; ?></textarea>
        </div>

        <div class="field-group">
            <label>Declaration</label>
            <div class="inline">
                <input type="checkbox" id="agree" name="agree" required>
                <span>I hereby declare that the information provided above is true and correct to the best of my knowledge.</span>
            </div>
        </div>

        <button type="submit" class="btn-submit">Submit Application</button>
    </form>
</div>

<!-- ------------- SUCCESS MODAL ------------- -->
<div class="overlay <?php if ($success) echo 'active'; ?>" id="successOverlay">
    <div class="modal">
        <div class="tick-circle"><span>&#10003;</span></div>
        <h2>Application Submitted!</h2>
        <p class="small">Thank you for applying. Your application has been recorded with the following details:</p>

        <div class="details">
            <div><span class="label">Application ID:</span> <?php echo $applicationId; ?></div>
            <div><span class="label">Name:</span> <?php echo $name; ?></div>
            <div><span class="label">Email:</span> <?php echo $email; ?></div>
            <div><span class="label">Phone:</span> <?php echo $phone; ?></div>
            <div><span class="label">Gender:</span> <?php echo $gender; ?></div>
            <div><span class="label">DOB:</span> <?php echo $dob; ?></div>
            <div><span class="label">City:</span> <?php echo $city; ?>, <?php echo $state; ?></div>
            <div><span class="label">Course:</span> <?php echo $course; ?></div>
            <?php if ($tenth || $twelfth): ?>
                <div><span class="label">Academics:</span>
                    10th: <?php echo $tenth ?: '-'; ?>,
                    12th/Diploma: <?php echo $twelfth ?: '-'; ?>
                </div>
            <?php endif; ?>
            <div><span class="label">Hostel Required:</span> <?php echo $hostel; ?></div>
            <?php if ($skills): ?>
                <div><span class="label">Skills:</span> <?php echo nl2br($skills); ?></div>
            <?php endif; ?>
            <?php if ($statement): ?>
                <div><span class="label">Statement:</span> <?php echo nl2br($statement); ?></div>
            <?php endif; ?>
        </div>

        <button id="okBtn">OK</button>
        <div style="clear: both;"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // allow digits, plus and spaces only in phone
        $("#phone").on("input", function () {
            this.value = this.value.replace(/[^0-9+ ]/g, "");
        });

        // allow only numbers, dot and % in percentage fields
        $("#tenth, #twelfth").on("input", function () {
            this.value = this.value.replace(/[^0-9.%]/g, "");
        });

        // Close success popup, reset form for new application
        $("#okBtn").on("click", function () {
            $("#successOverlay").removeClass("active");
            $("#registrationForm")[0].reset();
        });
    });
</script>

</body>
</html>