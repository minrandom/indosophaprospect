<div class="collapse" id="filterCollapse">
  <div class="row mt-4">
    <div class="col-md-3">
      <div class="form-group">
        <label for="provincefilter">Province :</label>
        <select id="provincefilter" name="provincefilter" class="form-control dropdown" required="">
          <option value="0" selected>Show All</option>
        </select>
      </div>
      <div class="form-group">
        <label for="picfilter">PIC :</label>
        <select id="picfilter" name="picfilter" class="form-control dropdown" required="">
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label for="BUfilter">Business Unit :</label>
        <select id="BUfilter" name="BUfilter" class="form-control dropdown" required="">
          <option value="0" selected>Show All</option>
        </select>
      </div>
      <div class="form-group">
        <label for="catfilter">Product Category :</label>
        <select id="catfilter" name="catfilter" class="form-control dropdown" required="">
          <option value="0" selected>Show All</option>
        </select>
      </div>
    </div>


    <div class="col-md-3">
      <div class="form-group">
        <label for="etafilter">ETA PO Date :</label>
        <select id="etafilter" name="etafilter" class="form-control dropdown" required="">
          

        </select>
      </div>
      <div class="form-group">
        <label for="tempefilter">Temperature :</label>
        <select id="tempefilter" name="tempefilter" class="form-control dropdown" required="">
       

        </select>
      </div>
      
    </div>

  
    

    <div class="col-md-3">
      <!--<div class="form-group">
        <label for="sumberinfofilter">Sumber Prospect :</label>
        <select id="sumberinfofilter" name="sumberinfofilter" class="form-control dropdown" required="">
          <option value="0" selected>Show All</option>
        </select>
      </div>-->
      <div class="form-group">
        <label for="sasaran">Target RS :</label>
        <select id="sasaran" name="sasaran" class="form-control dropdown" required="">
          
        </select>
      </div>

      
    </div>

    <div id="noDataMessage" class="col-lg-12 alert alert-danger mt-3" style="display: none;"></div>
  </div>
  <button class="btn btn-secondary btn-sm ml-2q" type="button" id="clear-filter">Clear Filter</button>
</div>