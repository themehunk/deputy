<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
 
 <form class="form-horizontal" action="#" method="post" name="get_excel">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>Custom Data Add</legend>
                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="tipamount">Total Tip Amount</label>
                            <div class="col-md-4">
                                <input type="text" name="tipamount" id="tipamount" class="input-large" placeholder= "1030">($)
                            </div>
                        </div>

                          <div class="form-group">
                            <label class="col-md-4 control-label" for="rvar">Restaurent Vairable</label>
                            <div class="col-md-4">
                                <input type="text" name="rvar" id="rvar" placeholder= "2/3" class="input-large">
                            </div>
                        </div>

                           <div class="form-group">
                            <label class="col-md-4 control-label" for="kvar">Kitchen Vairable</label>
                            <div class="col-md-4">
                                <input type="text" name="kvar" id="kvar" placeholder= "1/3" class="input-large">
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Get data</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="get_data" class="btn btn-primary button-loading" data-loading-text="Loading...">Get Data</button>
                            </div>
                        </div>
                    </fieldset>
                </form>