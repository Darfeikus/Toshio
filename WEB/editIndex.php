<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ./index.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home Page</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="jquery.gritter.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

    <!--Header-part-->
    <div id="header">
        <h1><a href="dashboard.html">Submission</a></h1>
    </div>
    <div class="content">
        <!-- notification message -->

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user information -->
        <?php if (isset($_SESSION['username'])) : ?>
            <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
            <p> <a href="../index.php?logout='1'" style="color: red;">logout</a> </p>
        <?php endif ?>
    </div>

    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Edit assignment</a></div>
        </div>
        <!--End-breadcrumbs-->
        <?php
        if (!empty($_SESSION['msg'])) {
            echo '<p class="msg"> ' . $_SESSION['msg'] . '</p>';
            unset($_SESSION['msg']);
        }
        ?>
        <div class="container-fluid">
            <?php
            if (isset($_POST['group'])) {
                $_SESSION['group'] = $_POST['group'];
                if (file_exists('./fileuploads/Classes/' . $_POST['group']))
                    header('Location: editAssignment.php');
                else
                    echo "It seems you can't go there :p";
            }
            ?>
            <form method="post" class="form-vertical" action="">
                <h4>Select the group you whish to check:</h4>

                <?php
                require './fileuploads/Database.php';
                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM `groups` WHERE matricula = ?";
                $result = $pdo->prepare($sql);
                $result->execute(array($_SESSION['username']));

                ?>

                <select name="group" required>
                    <?php while ($row = $result->fetch(PDO::FETCH_NUM)) { ?>
                        <option value="<?php echo $row[0] ?>"><?php echo $row[1]; ?> </option>
                    <?php } ?>
                </select>

                <br><br>
                <input type="submit" value="Go" name="submit" class="btn btn-primary" />
            </form>
            <form action="./index.php" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <h4>Return to home page:</h4>
                    <input type="submit" value="Home" name="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
</body>


</html>

<script>
    var createAllErrors = function() {
        var form = $(this),
            errorList = $("ul.errorMessages", form);

        var showAllErrorMessages = function() {
            errorList.empty();

            // Find all invalid fields within the form.
            var invalidFields = form.find(":invalid").each(function(index, node) {

                // Find the field's corresponding label
                var label = $("label[for=" + node.id + "] "),
                    // Opera incorrectly does not fill the validationMessage property.
                    message = node.validationMessage || 'Invalid value.';

                errorList
                    .show()
                    .append("<li><span>" + label.html() + "</span> " + message + "</li>");
            });
        };

        $("input[type=submit], button:not([type=button])", form)
            .on("click", showAllErrorMessages);

        $("input", form).on("keypress", function(event) {
            var type = $(this).attr("type");
            if (/date|email|month|number|search|tel|text|time|url|week/.test(type) &&
                event.keyCode == 13) {
                showAllErrorMessages();
            }
        });
    };

    $("form").each(createAllErrors);
</script>