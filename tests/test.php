<?php

var_dump($_FILES);

echo "<br>wrong way";
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script>
            $(document).ready(function () {

                $("#testForm").change(function (event) {

                    //stop submit the form, we will post it manually.
                    event.preventDefault();

                    // Get form
                    var form = $('#testForm')[0];

                    // Create an FormData object
                    var data = new FormData(form);

                    // If you want to add an extra field for the FormData
                    data.append("CustomField", "This is some extra data, testing");

                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "test.php",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        success: function (data) {

                            $("#result").text(data);
                            console.log("SUCCESS : ", data);
                            $("#btnSubmit").prop("disabled", false);

                        },
                        error: function (e) {

                            $("#result").text(e.responseText);
                            console.log("ERROR : ", e);
                            $("#btnSubmit").prop("disabled", false);

                        }
                    });

                });

            });
        </script>
    </head>
    <body>
        <form enctype="multipart/form-data" id="testForm">
        <input type="file" name="uploadFile" id="uploadFile" onchange="onUpdate()">

    </body>
</html>