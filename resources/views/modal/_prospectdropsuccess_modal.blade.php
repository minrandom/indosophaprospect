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
            <label for="submitdate">Tanggal Input</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="thecreators">Created By</label>
            <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input type="hidden" required="" id="creatorrole" name="creatorrole" class="form-control" value="{{Auth::user()->role}}">
           
            <input readonly type="" required="" id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
          </div>
          
          <div class="form-group">
          <label for="prospectCode">Prospect Code</label>
          <input type="hidden" id="idprospect" name="idprospect" class="form-control" >
          <input readonly id="prospectCode" name="prospectCode" class="form-control " >
           
         
          </div>

          <div class="form-group">
          <label for="updateTo">Set Status</label>
          
          <input readonly id="updateTo" name="updateTo" class="form-control " >
           
         
          </div>

          <div class="form-group">
            <label for="cr8reason">Pilih Alasan</label>
             <select id="cr8reason" name="cr8reason" class="form-control" required=""  >
            
          </select>
        </div>
        <div class="form-group">
            <label for="cr8messages">Penjelasan</label>
            <textarea 
        id="cr8messages" 
        name="cr8messages" 
        class="form-control" 
        required 
        minlength="50"
        oninvalid="this.setCustomValidity('Minimal 20 karakter diperlukan')"
        oninput="this.setCustomValidity('')"
    ></textarea>
          </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-request" id="btn-request" >Submit Request</button>
        </form>
      </div>
    </div>
  </div>
</div>