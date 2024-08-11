@extends('layout.backend.app',[
'title' => 'Prospect Detail',
'pageTitle' =>'Prospect Detail',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endpush

@section('content')



<!-- Tasks Card Example -->
<nav aria-label="...">
  <ul class="pagination justify-content-between">

    

  </ul>
</nav>

<div class="inner-container fixed-card">
  <div class="row ">
    <div id="productData" data-url=></div>

      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Creator</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['creator'] }}</div></br>

              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Created At</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse($allp[0]->created_at)->format('d - F - Y') }}</div>

            </div>
            <div class="col-auto">
              <i class="fas fa-percent fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>




    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Rumah Sakit</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800" id="RSPROV">{{ $coredata['hospital'] }}</div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Departemen</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['department'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-hospital-user fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>






    <!-- Earnings (Annual) Card Example   //number_format($prp->config->price_include_ppn, 0, ',-', '.')-->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Business Unit</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"> {{ $coredata['unit'] }}</div>
              <br>
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Category</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['category'] }} </div>


            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4 " id="nopros">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                Nomor Prospect</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allp[0]->prospect_no }}</div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Person In Charge</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
              {{ $coredata['personInCharge'] }}
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-alt fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>




  </div>
  <div class="notify" id="thealert"></div>
  <div class="row">
  
  </div>
</div>


<div class="outer-container">
  <div class="row">
  <div class="col-lg-12">
  <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" role='button' data-target="#collapseQ1" aria-expanded="true" aria-controls="collapseQ1">
          Prospect Quarter 1
        </button>
      </h5>
    </div>

    <div id="collapseQ1" class="collapse multi-collapse"   aria-labelledby="headingOne" >
      <div class="card w-100">
      <div class="card-body p-0">
      
          {!! $showdata[0] !!}
      </div>
      <div class="card-body p-0">
  <div class="d-flex justify-content-center align-items-center w-100">
    <label for="pic" class="col-sm-3 col-form-label font-weight-bold text-right">Status :</label>
    {!! $statusdata[0]!!}
  </div>
</div>

      </div>

    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ2" aria-expanded="false" aria-controls="collapseQ2">
        Prospect Quarter 2
        </button>
      </h5>
    </div>
    <div id="collapseQ2" class="collapse multi-collapse" aria-labelledby="headingTwo" >
      <div class="card-body">
      {!! $showdata[1] !!}   
    </div>
    <div class="card-body p-0">
  <div class="d-flex justify-content-center align-items-center w-100">
    <label for="pic" class="col-sm-3 col-form-label font-weight-bold text-right">Status :</label>
    {!! $statusdata[1]!!}
  </div>
</div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ3" aria-expanded="false" aria-controls="collapseQ3">
        Prospect Quarter 3
        </button>
      </h5>
    </div>
    <div id="collapseQ3" class="collapse multi-collapse" aria-labelledby="headingThree" >
      <div class="card-body">
      {!! $showdata[2] !!}  
    </div>
    <div class="card-body p-0">
  <div class="d-flex justify-content-center align-items-center w-100">
    <label for="pic" class="col-sm-3 col-form-label font-weight-bold text-right">Status :</label>
    {!! $statusdata[2]!!}
  </div>
</div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ4" aria-expanded="false" aria-controls="collapseQ4">
        Prospect Quarter 4
        </button>
      </h5>
    </div>
    <div id="collapseQ4" class="collapse multi-collapse" aria-labelledby="headingFour" >
      <div class="card-body">
      {!! $showdata[3] !!}
    </div>
    <div class="card-body p-0">
  <div class="d-flex justify-content-center align-items-center w-100">
    <label for="pic" class="col-sm-3 col-form-label font-weight-bold text-right">Status :</label>
    {!! $statusdata[3]!!}
  </div>
</div>
    </div>
  </div>
</div>
  </div>
  </div>
    



  <!-- Modal Edit -->
  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-modalLabel">Edit Info Prospect</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <div class="form-group">
              <label for="submiteddate">Tanggal Validasi</label>
              <input type="hidden" required="" id="data" name="data" class="form-control">

              <input readonly type="" required="" id="submiteddate" name="submiteddate" class="form-control">
            </div>

            <div class="form-group">
              <label for="creatorname">Created By</label>
              <input readonly type="" required="" id="creatorname" name="creatorname" class="form-control">
            </div>

            <div class="form-group">
              <label for="sourceedit">Sumber Info</label>
              <input type="" placeholder="Input Nama Event Disini" style="display: none;" id="namaevent" name="namaevent" class="form-control">
              <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
              <input readonly id="sourceedit" name="sourceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}" onchange="this.size=1" ;>


            </div>
            <div class="form-group">
              <label for="provinceedit">Provinsi</label>

              <input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">

            </div>
            <div class="form-group">
              <label for="hospital">Rumah Sakit</label>
              <input readonly autocomplete="off" type="" required="" id="hospitalname" name="hospitalname" class="form-control">

            </div>
            <div class="form-group">
              <label for="departmentname">Departement</label>
              <select required="" id="departmentname" name="departmentname" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="personincharge">PIC</label>
              <select required="" id="personincharge" name="personincharge" class="form-control">
              </select>
            </div>

            <div class="form-group">
              <label for="addinfo">Info Tambahan</label>
              <input required="" autocomplete="off" id="addinfo" name="addinfo" class="form-control">

            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Edit -->


  <!-- Modal Produk -->
  <div class="modal fade" id="produk-modal" tabindex="-1" role="dialog" aria-labelledby="produk-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="Produk-modalLabel">Edit Info Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="produkForm">

            <div class="form-group">
              <label for="editunit">Business Unit</label>
              <select required="" id="editunit" name="editunit" class="form-control" readonly>
              </select>
            </div>
            <div class="form-group">
              <label for="editcategory">Category</label>
              <select required="" id="editcategory" name="editcategory" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="productlist">Produk</label>
              <select id="productlist" name="productlist" class="form-control" required="">

              </select>
            </div>
            <div class="form-group">
              <label for="qtyitem">Quantity</label>
              <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control">
              <input type="hidden" required="" id="data" name="data" class="form-control">

            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Produk -->
  <!-- Modal Review -->
  <div class="modal fade" id="review-modal" tabindex="-1" role="dialog" aria-labelledby="review-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="review-modalLabel">Edit Review</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="reviewForm">
            <div class="form-group">
              <label for="user_status">User Status</label>
              <input type="hidden" required="" id="data" name="data" class="form-control">

              <select id="user_status" name="user_status" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="purchasing_status">Purchasing Status</label>
              <select id="purchasing_status" name="purchasing_status" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="direksi_status">Direksi Status</label>
              <select id="direksi_status" name="direksi_status" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="anggaran_status">Anggaran Status</label>
              <select id="anggaran_status" name="anggaran_status" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="jenis_anggaran">Jenis Anggaran</label>
              <select id="jenis_anggaran" name="jenis_anggaran" class="form-control">
              </select>
            </div>

            <div class="form-group">
              <label for="etapodate">ETA PO Date</label>
              <input type="date" id="etapodate" name="etapodate" class="form-control">
            </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Review -->


  <!--Modal Chc-->
  <div class="modal fade" id="chc-modal" tabindex="-1" role="dialog" aria-labelledby="chc-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="chc-modalLabel">Edit Review</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="chcForm">


            <div class="form-group">
              <label for="chance">Chance</label>
              <select id="chance" name="chance" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="next_action">Next Action</label>
              <select id="next_action" name="next_action" class="form-control">
              </select>
            </div>




        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End modal chc-->



  <!-- Modal Promosi -->
  <div class="modal fade" id="promosi-modal" tabindex="-1" role="dialog" aria-labelledby="promosi-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="promosi-modalLabel">Update Tahap Promosi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="promosiForm">
            <div class="form-group">
              <label for="first">First Offer</label>
              <input type="date" id="first" name="first" class="form-control">

            </div>
            <div class="form-group">
              <label for="demo">Demo</label>
              <input type="date" id="demo" name="demo" class="form-control">

            </div>
            <div class="form-group">
              <label for="presentation">Presentation</label>
              <input type="hidden" required="" id="data" name="data" class="form-control">

              <input type="date" id="presentation" name="presentation" class="form-control">

            </div>
            <div class="form-group">
              <label for="last">Last Offer</label>
              <input type="date" id="last" name="last" class="form-control">

            </div>




        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-update">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- Modal Promosi -->


@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>


<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Retrieve the alert message from local storage
    
    var conspros = @json($showdata);
    console.log(conspros);
    var core = @json($coredata);
    var cek = @json($allp);
    console.log(core);
    console.log(cek);
    






  });
</script>
@endpush