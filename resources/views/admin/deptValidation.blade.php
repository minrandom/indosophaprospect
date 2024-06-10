@extends('layout.backend.app',[
'title' => 'Hospital Department Validation',
'pageTitle' =>'Hospital Department Validation',
])

@push('css')
<style>
  .tooltip-inner {
    max-width: 300px;
    /* Set the maximum width of the tooltip */
    width: auto;
    /* Allow the tooltip to expand horizontally if needed */
  }
</style>



@endpush

@section('content')
<div class="notify"></div>

<div class="card">
  <div class="card-body">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Validation Department</div>

            <div class="card-body">
              <form id="deptValidForm">
                @csrf

                <div class="form-group">
                  <label for="submitdate">Tanggal Validasi</label>
                  <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}">
                </div>

                <div class="form-group">
                  <label for="thecreators">Validation By</label>
                  <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
                  <input readonly type="" required="" id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
                </div>

                <div class="form-group">
                  <label for="cr8province">Provinsi</label>
                  <select id="cr8province" name="cr8province" class="form-control" required="">

                  </select>
                </div>

                <div class="form-group">
                  <label for="cr8hospital">Rumah Sakit</label>

                  <select id="cr8hospital" name="cr8hospital" class="form-control" required="">

                  </select>
                </div>
                <div class="form-group ">
                  <label for="Dept">DEPARTMENT :</label>
                  <div class="deptarea" id="deptarea">
                  <div class="col-md-10">
                    <div class ="form-row ">
                    
                       <span class="align-middle">Pilih Rumah Sakit Untuk List Departemen</span>
                     
                   
                      

                    </div>
                  </div>
                  </div>
                </div>
                




                <div class="modal-footer">

                  <button type="submit" class="btn btn-primary btn-store" id="btn-store" >Update</button>
                  </form>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>


  </div>
</div>


@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script type="text/javascript">
  $(function() {
    var id = $(this).attr("id")
    
    $.ajax({
      url: "{{ route('admin.deptvalidationc') }}",
      method: "GET",
      success: function(response) {

        $("#cr8hospital").val("");

        var today = new Date();

        var provinceSelect = $("#cr8province");
        populateSelectFromDatalist('cr8province', response.province, "Pilih Provinsi");

        var hosinput = $("#cr8hospital");

        function fetchHospitals2(provinceId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
            method: "GET",
            success: function(response) {

              populateSelectFromDatalist('cr8hospital', response.hosopt, "Pilih Rumah Sakit");

            }
          });
        }

        provinceSelect.on("change", function() {
          var selectedProvinceId = $(this).val();
          fetchHospitals2(selectedProvinceId);
        });

        function deptValid(hospitalId){
          $.ajax({
            url: "{{ route('admin.getDeptValid', ['hospitalId' => ':hospitalId']) }}".replace(':hospitalId', hospitalId),
            method: "GET",
            success: function(response) {
              let deptArea = document.getElementById('deptarea');
              deptArea.innerHTML = '';


              response.alldept.forEach(dept => {
                // Create the HTML structure for each department
                let deptDiv = document.createElement('div');
                deptDiv.classList.add('form-row');
                
                let nameCol = document.createElement('div');
                nameCol.classList.add('col-md-6');
                nameCol.innerHTML = `<span class="align-middle">${dept.name}</span>`;
                
                let selectCol = document.createElement('div');
                selectCol.classList.add('col-md-6');
                let select = document.createElement('select');
                select.id = `stat${dept.id}`;
                select.name = `stat${dept.id}`;
                select.classList.add('form-control');
                
                
                let option1 = document.createElement('option');
                option1.value = 1;
                option1.textContent = 'ADA';
                if (dept.stats > 0) {
                    option1.selected = true;
                }



                let option2 = document.createElement('option');
                option2.value = 0;
                option2.textContent = 'TIDAK ADA';
                if (dept.stats == 0) {
                    option2.selected = true;
                }


                select.appendChild(option1);
                select.appendChild(option2);
                
                selectCol.appendChild(select);
                deptDiv.appendChild(nameCol);
                deptDiv.appendChild(selectCol);
                
                deptArea.appendChild(deptDiv);
              
            });

            }
          });
        }
        
        hosinput.on("change", function() {
          var selectedhosId= $(this).val();
          deptValid(selectedhosId);
          
        });
       




      }
    })
  });


  // Create 
  function submitForm(url, successMessage) {
    var formData = $("#deptValidForm").serialize();

    $.ajax({
      url: url,
      method: "POST",
      data: formData,
      success: function() {
        //$("#create-modal").modal("hide");
        //$('.data-table').DataTable().ajax.reload();
        //$("#deptValidForm")[0].reset();
        flash("success", successMessage);
        document.querySelector(".notify").scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    });
  };


  $('#btn-store').on('click', function(e) {
        e.preventDefault();
        var clickedButton = $(document.activeElement);

        if (clickedButton.is('#btn-store')) {
            submitForm("{{ route('admin.deptvalid') }}", "Data berhasil divalidasi");
        } 
    });







  //delete

  // $('body').on("click",".btn-delete",function(){
  //     var id = $(this).attr("id")
  //     $(".btn-destroy").attr("id",id)
  //     $("#destroy-modal").modal("show")
  // });

  // $(".btn-destroy").on("click",function(){
  //     var id = $(this).attr("id")

  //     $.ajax({
  //         url: "/admin/province/"+id,
  //         method: "DELETE",
  //         success:function(){
  //             $("#destroy-modal").modal("hide")
  //             $('.data-table').DataTable().ajax.reload();
  //             flash('success','Data berhasil dihapus')
  //         }
  //     });
  // })

  function flash(type, message) {
    $(".notify").html(`<div class="alert alert-` + type + ` alert-dismissible fade show" role="alert">
                              ` + message + `
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>`)
  }
</script>
@endpush