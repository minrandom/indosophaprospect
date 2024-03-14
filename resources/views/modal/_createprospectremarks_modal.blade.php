<div class="modal fade" id="prospectremarks-modal" tabindex="-1" role="dialog" aria-labelledby="prospectremarks-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="prospectremarks-modalLabel">Create Remarks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createRemForm">
    
        <div class="form-group">
            <label for="submitdate">Tanggal Input</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="thecreators">Created By</label>
            <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input type="hidden" required="" id="creatorrole" name="creatorrole" class="form-control" value="{{Auth::user()->role}}">
            <input type="hidden" required="" id="prospectid" name="prospectid" class="form-control" value="">
            <input readonly type="" required="" id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
          </div>
          
          <div class="form-group">
          <label for="cr8type">Tipe Remarks</label>
          
          <select id="cr8type" name="cr8type" class="form-control " required="" >
           
          </select>
         
          </div>

          <div class="form-group">
            <label for="cr8colupdate">Pilih Kolom untuk diUpdate</label>
             <select id="cr8colupdate" name="cr8colupdate" class="form-control" required=""  >
            
          </select>
        </div>
        <div class="form-group">
            <label for="cr8messages">Pesan Tambahan</label>
             <input type='' placeholder='' id="cr8messages" name="cr8messages" class="form-control" required=""  title='' >
          </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-inputrem" id="btn-inputrem" >Input Remarks</button>
        </form>
      </div>
    </div>
  </div>
</div>