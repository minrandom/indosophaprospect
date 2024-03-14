<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create-modalLabel">Create Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createForm">
    
        <div class="form-group">
            <label for="submitdate">Tanggal Input</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="thecreators">Created By</label>
            <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input readonly type="" required="" id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
          </div>
          
          <div class="form-group">
          <label for="cr8source">Sumber Info</label>
          
          <select id="cr8source" name="cr8source" class="form-control " required="" >
           
          </select>
          <input type="" placeholder="Input Nama Event Disini" style="display: none;" id="eventname" name="eventname" class="form-control">

          </div>

          <div class="form-group">
            <label for="cr8infoextra">Informasi Tambahan</label>
             <input type='' placeholder='Misal : " Kebutuhan untuk ruang OK baru " , " Kebutuhan Banyak Ventilator ", dsb' id="cr8infoextra" name="cr8infoextra" class="form-control" required=""  title='ex: "Kebutuhan ruang OK","Kebutuhan instrumen baru"' >
          </div>


          <div class="form-group">
            <label for="cr8province">Provinsi</label>
             <select id="cr8province" name="cr8province" class="form-control" required=""  >
            
          </select>
        </div>

        <div class="form-group">
            <label for="cr8hospital">Rumah Sakit</label>
             
            <select id="cr8hospital" name="cr8hospital" class="form-control" required=""  >

            </select>
          </div>
        <div class="form-group">
            <label for="cr8department">Departement</label>
            <select required="" id="cr8department" name="cr8department" class="form-control"  onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
        </div>

        <div class="form-group">
            <label for="cr8bunit">Business Unit</label>
            <select required="" id="cr8bunit" name="cr8bunit" class="form-control">
            </select>
        </div>
        <div class="form-group">
            <label for="cr8category">Category</label>
            <select required="" id="cr8category" name="cr8category" class="form-control">
            </select>
        </div>
        <div class="form-group">
            <label for="cr8product">Produk</label>
            <select required="" id="cr8product" name="cr8product" class="form-control">
            </select>
        </div>

        <div class="form-group">
            <label for="qtyinput">Quantity</label>
            <input type="number" required="" id="qtyinput" name="qtyinput" min='1' class="form-control" oninput="validateQuantity()">
            <p id="quantityWarning" style="color: red; display: none;">Quantity Minimal angka 1</p>
        </div>

        <div class="form-group">
            <label for="anggarancr8">Anggaran</label>
            <select type="" required="" id="anggarancr8" name="anggarancr8" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>
          
        <div class="form-group">
            <label for="jenisanggarancr8">Jenis Anggaran</label>
            <select  required="" id="jenisanggarancr8" name="jenisanggarancr8" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>

        <div class="form-group">
            <label for="etapodatecr8">Estimasi PO Date</label>
            <input type="date" required="" id="etapodatecr8" name="etapodatecr8" class="form-control" >
           
          </div>
          </br>
          </br>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-draft" id="btn-draft" >Simpan Draft</button>
        <button type="submit" class="btn btn-primary btn-store" id="btn-store" >Kirim Untuk divalidasi</button>
        </form>
      </div>
    </div>
  </div>
</div>