<!DOCTYPE html>
<html lang="en">
<head>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

</head>
<body>
    <div id="wrap">
    <?php include_once("db-table.php"); ?>
        <div class="container">
            <div class="row">
                <form class="form-horizontal" action="#" method="post" name="upload_excel" enctype="multipart/form-data">
                <fieldset>
                        <!-- Form Name -->
                    <legend>Sheet Upload</legend>
                    <?php if(upload_check()){ ?>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Reset Data <small>(New Sheet Upload)</small></label>
                            <div class="col-md-4">
                                <button onclick="return confirmdelete();" type="submit" id="reset" name="Reset" class="btn btn-primary button-loading" data-loading-text="Loading...">Reset</button>
                                <a href="export.php">View List</a>
                            </div>
                        </div>

                        <?php
                    }else{ ?>


     



                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                            </div>
                        </div>
                    <?php } ?>
                    </fieldset>

                </form>
            </div>
        </div>
    </div>


    <script>

function confirmdelete() {
   let isExecuted = confirm("Are you sure to execute this action?");
   return isExecuted;
}


    </script>

</body>
</html>


