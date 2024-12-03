<div class="modal fade" id="dropsuccess-modal" tabindex="-1" role="dialog" aria-labelledby="dropsuccess-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dropsuccess-modalLabel">Update Status Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="requestDropSuccessForm">
    
        <div class="form-group">
            <label for="submitdate">Request Data</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="thecreators">Request By</label>
            <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input type="hidden" required="" id="creatorrole" name="creatorrole" class="form-control" value="{{Auth::user()->role}}">
           
            <input readonly type="" required="" id="thecreators" name="thecreators" value="" class="form-control">
          </div>
        
          
          <div class="form-group">
          <label for="prospectCode">Prospect Code</label>
          <input type="hidden" id="idprospect" name="idprospect" class="form-control" >
          <input readonly id="prospectCode" name="prospectCode" class="form-control " >     
          </div>

          <div class="form-group">
          <label for="RS">Rumah Sakit</label>
          <input readonly id="RS" name="RS" class="form-control " >
          </div>

          <div class="form-group">
          <label for="Config">Config</label>
          <input readonly id="Config" name="Config" class="form-control " >
          </div>
          
     

          <div class="form-group">
          <label for="cr8reason">Reason</label>
          <input readonly id="cr8reason" name="cr8reason" class="form-control " >           
          <textarea 
          readonly
              id="cr8messages" 
              name="cr8messages" 
              class="form-control" 
              minlength="50"
          ></textarea>
          </div>
          
          <div class="form-group">
            <label for="validators">Updated By</label>
        
           
            <input readonly type="" required="" id="validators" name="validators" value="{{ Auth::user()->name }}" class="form-control">
          </div>

          <div class="form-group">
          <label for="updateTo">Set Status</label>
          <select class="updateTo form-control" id="updateTo" name="updateTo">
            <option value="0">Request</option>
            <option value="1">Recorded(APPROVE)</option>
          </select>
          </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-update" id="btn-update" >Update Request</button>
        </form>
      </div>
    </div>
  </div>
</div>