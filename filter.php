<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            echo "<h5 class='text-center p-3'><b>Table </b>- ".$dates['start']."-".$dates['last']."
            <a  class='btn btn-outline-primary' href='/deputy/export/export.php'>Final Data</a></h3>";

            echo $table;
            ?>
        </div>
        <hr class="c-3">

        <div class="col-md-4">

            <div class="row">
            <h5>Sheet Filter Calculate</h5>

            <?php
                $rcalulate = tip_caluclation($tip_calulate_arear);
            ?>

            <form class="row g-3" method="POST" action="#">
                <div class="col">
                    <label  class="form-label">Tip Amount($)</label>

                    <div class="input-group mb-3">
                        <input type="number" class="form-control" placeholder="500" name="tip_amount" value="<?php echo $rcalulate['tip_amount']; ?>" />
                        <span class="input-group-text">$</span>
                    </div>

                </div>
                <div class="col">
                    <label class="form-label">Bonus Amount($)</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" placeholder="300" name="bonus_amount"  value="<?php echo $rcalulate['bonus_amount']; ?>" />
                        <span class="input-group-text">$</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" >Notes</label>
                    <textarea class="form-control" rows="3" name="sheet_notes"><?php echo $rcalulate['sheet_notes']; ?></textarea>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" name="calculate">Calculate</button>
                </div>
            </form>
            </div>

            <div class="alert alert-dark" role="alert">
                <h5 class="alert-heading">Tip Amount Calculate</h5>
                <hr>
                TIP/Hour: <b data-tip="<?php echo $rcalulate['per_hour_tip']; ?>" id="tip_amount">$ <?php echo $rcalulate['per_hour_tip']; ?></b><br>

                Bonus/Hour: <b data-bonus="<?php echo $rcalulate['per_hour_bonus']; ?>" id="bonus_amount" >$ <?php echo $rcalulate['per_hour_bonus']; ?></b>
            </div>

            <div class="col-12">
                <button class="btn btn-outline-secondary" onclick="window.print()">
                    <img src="image/printer.svg" /> Print</button>
            </div>

        </div>

        <div class="col-md-1">
        </div>

        <div class="col-md-7">
    <?php $md=5;
    $dis_emp = $dis_al = "";
    $weight = isset($_POST['weight'])?$_POST['weight']:100;

 ?>
 <div class="row">
    <div id="testing-data"></div>
                <form class="row g-3 formfilter" method="POST" action="#" id="filteraj">
                <div class="col">
                    <label  class="form-label"><b>Choose Area Name</b></label>
                    <select id="area-select" class="form-select" name="area[]" multiple aria-label="multiple select" <?php echo $dis_al; ?>>

                        <?php
                        foreach ($area_name as $key => $area) {
                            $selected = (isset($_POST['area']) && in_array($area, $_POST['area']))?'selected':'';

                            echo  "<option value='{$area}' ".$selected .">{$area}</option>";
                        }

                        ?>
                    </select>
                </div>
                    <div class="col">
                        <label class="form-label"><b>Access Level</b> </label>
                        <select id="level-select" class="form-select level-select" multiple aria-label="multiple select" name="level[]" <?php echo $dis_al; ?>>
                            <?php
                            foreach ($access_level_name as $key => $level) {
                                $selected = (isset($_POST['level']) && in_array($level, $_POST['level']))?'selected':'';
                                echo  "<option value='{$level}' ".$selected .">{$level}</option>";
                            }
                            ?>

                        </select>
                        </div> 
                    </div>  <!-- row close -->
                   
                    <div class="col-4" style="display: none;">
                    <label for="inputAddress" class="form-label"><b>Employee Name </b> </label>
                    <select id="employee-select" class="form-select employee-select" multiple aria-label="multiple select" name="employee[]" <?php echo $dis_emp; ?>>
                        <?php
                        foreach ($unique_emp as $key => $name) {
                            $selected = (isset($_POST['employee']) && in_array($key, $_POST['employee']))?'selected':'';
                            echo  "<option value='{$key}' ".$selected .">{$name}</option>";
                        }
                        ?>

                    </select>
                    </div> 

                    <div class="row mb-5">
                            <!-- filter button -->
                            <div class="d-flex bd-highlight mb-3">
                                <div class="p-3 bd-highlight">
                                <button type="submit" class="btn btn-primary" name="filter" id="filter" disabled>Apply Filter</button>

                                </div>
                                <div class="p-3 bd-highlight">
                                <button type="button" class="btn btn-secondary" id="clear_filter" name="clear_filter">Clear All Filters</button>

                                </div>
                             </div>


                                 <!-- apply weight -->
                                 <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <b>Filter Apply</b>
                                        <label for="inputPassword6" class="col-form-label">Weight(%)</label>
                                    </div>
                                    <div class="col-auto">
                                    <input type="number" name="weight" class="form-control" min="0" max="100" id="weight" value="<?php echo $weight; ?>"/>
                                    </div>

                                </div>

                            
                            <!-- clear filter button -->
                            <div class="d-flex bd-highlight mb-3">
                                 <div class="p-2 bd-highlight">
                                 <button type="submit" class="btn btn-primary" name="Weight_filter" id="weight_filter">Apply Weight</button>

                                </div>
                            </div>
                    
                            </div>
                        <div >
                                <?php
                            if(isset($_POST['filter']) || isset($_POST['Weight_filter'])){

                                    $md=3;


                                    if(isset($_POST['area']) || isset($_POST['level'])){
                                        $dis_emp="disabled='disabled'";
                                    }elseif(isset($_POST['employee'])){
                                        $dis_al="disabled='disabled'";
                                    }

                                }
                                ?>
                        </div>
                    

            </form>
        </div>  
        <!-- col md close -->


        
    </div>

    
    <div class="p-5"></div>
   
</div> <!-- /container -->