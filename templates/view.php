<div class="overlay">
            Wait...
      </div>
<div class="container">
		<div class="search-bar">
                  <h2>Find Courses</h2>
			<form action="#" method="#">
				<div class="row">
                              <div class="col-3">
                                    <label>Select Country</label>
                                    <select class="select_country">
                                          <option value="">Select Country</option>
                                          <?php foreach( $result_qntry as $row_qntry ) : ?>
                                                <option value="<?php echo $row_qntry->id;?>"><?php echo $row_qntry->title;?></option>
                                          <?php endforeach;?>
                                    </select>
                              </div>
                              <div class="col-3">
                                    <label>Select Versity</label>
                                    <select class="select_veristy">
                                          <option value="">Select Versity</option>
                                    </select>
                              </div>
                              <div class="col-3">
                                    <label>Select Level</label>
                                    <select class="select_level">
                                         <option value="">Select Level</option>
                                         <?php foreach( $result_lvl as $row_lvl )  : ?>
                                                <option value="<?php echo $row_lvl->id;?>"><?php  echo $row_lvl->title;?></option>
                                         <?php endforeach;?>
                                         
                                    </select>
                              </div>
					
				</div>
                        <div class="row">
                              <button type="button" class="submit-btn">Search</button>
                        </div>
			</form>
		</div>
            <div class="result-data">
                  <div class="row">
                        <div class="col-12">
                              
                              
                        </div>
                  </div>
                  
            </div>
	</div>