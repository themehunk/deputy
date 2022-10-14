
 <form class="form-horizontal" action="#" method="post" name="get_excel">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>Calculate Tip Amount</legend>
                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="tipamount">Total Tip Amount</label>
                            <div class="col-md-4">
                                <input type="text" name="tipamount" id="tipamount" class="input-large" value= "1030">($)
                            </div>
                        </div>

                          <div class="form-group">
                            <label class="col-md-4 control-label" for="rvar">Restaurent Vairable</label>
                            <div class="col-md-4">
                                <input type="text" name="f_rvar" id="f_rvar" class="input-small" value= "2" class="input-large"> /

                                <input type="text" name="s_rvar" id="s_rvar" class="input-small" value= "3" class="input-large">
                            </div>
                        </div>

                           <div class="form-group">
                            <label class="col-md-4 control-label" for="kvar">Kitchen Vairable</label>
                            <div class="col-md-4">
                                <input type="text" name="f_kvar" id="f_kvar" class="input-small" value= "1" class="input-large"> /
                                <input type="text" name="s_kvar" id="s_kvar" class="input-small" value= "3" class="input-large">

                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Get data</label>
                            <div class="col-md-4">
                                <button type="button" id="get_data" name="get_data" class="btn btn-primary" >Get Data</button>
                            </div>
                        </div>
                    </fieldset>
                </form>