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

    @if($prev)
    <li class="page-item">
      <div class="card-body">
        <a class="page-link" href="{{ route('admin.prospecteditdata', $prev) }}">&laquo;Previous Prospect</a>
        <p class="card-text">{{$prevprospect->hospital->name}}({{$prevprospect->province->name}})</br>
          {{$prevprospect->config->name}}
        </p>
      </div>
    </li>
    @else
    <li class="page-item disabled">
      <div class="card-body">
        <a class="page-link" href="#" aria-disabled="true">&laquo;Previous Prospect</a>
        <p class="card-text">No More Prev Prospect</p>
      </div>

    </li>
    @endif



    @if ($next)
    <li class="">
      <div class="card-body">
        <a class="page-link" href="{{ route('admin.prospecteditdata', $next) }}">Next Prospect&raquo;</a>
        <p class="card-text">{{$nextprospect->hospital->name}}({{$nextprospect->province->name}})</br>
          {{$nextprospect->config->name}}
      </div>

    </li>
    @else
    <li class="page-item disabled">
      <div class="card-body">
        <a class="page-link" href="#" aria-disabled="true">Next Prospect&raquo;</a>
        <p class="card-text">No More Next Prospect</p>
      </div>

    </li>
    @endif

  </ul>
</nav>

<div class="inner-container fixed-card">
  <div class="row ">
    <div id="productData" data-url="{{ route('product.getProducts') }}"></div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Rumah Sakit</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$prospect->hospital->name}}({{$prospect->province->name}}) </div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Departemen</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$prospect->department->name}}</div>
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
                Product</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800">{{$prospect->config->name}}<br>( {{$prospect->qty}} {{$prospect->config->uom}} ) </div>
              <br>
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Nilai Prospect</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($prospect->submitted_price,0, ',-', '.') }} </div>


            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Temperatur</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{!! $tempe !!} {{ number_format($prospect->review->chance * 100, 0) }}%</div></br>

              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                ETA PO Date</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{date('d-M-Y', strtotime($prospect->eta_po_date))}}</div>

            </div>
            <div class="col-auto">
              <i class="fas fa-percent fa-2x text-gray-500"></i>
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
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$prospect->prospect_no}}</div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Last Review At</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                @if($reviewdata)
                    {{ date('d-M-Y', strtotime($reviewdata->created_at)) }}
                @else
                    <span class='btn-danger'>Belum Pernah diUpdate</span>
                @endif
              </div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Last Column Update</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$colUpdate}}</div>
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

    <!-- 
    <div class="col-xl-10 col-md-6 mb-4">
        <div class="alert alert-light">
    <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn-updatereview">
        <div class="light-alert-content">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Next Action Needed
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @if($prospect->review->next_action)
                            {{$prospect->review->next_action}}
                        @else
                            Silahkan Update Review
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </a>
    </div>
    </div>Earnings (Monthly) Card Example -->

  </div>
</div>


<div class="outer-container">
  <div class="row">

    <div class="col-lg-6">

      <!-- Basic Card Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Detail Info</h6>

        </div>
        <div class="card-body">
          <div class="col-sm-12">
            <label for="tglvalidasi" class="col-sm-3 col-form-label font-weight-bold">Dibuat Oleh : </label>
            <label for="tglvalidasi" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->creator->name }}</label>
          </div>
          <div class="col-sm-12">
            <label for="tglvalidasi" class="col-sm-3 col-form-label font-weight-bold">Tanggal validasi : </label>
            <label for="tglvalidasi" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ date('d-M-Y', strtotime($prospect->validation_time)) }}</label>
          </div>
          <div class="col-sm-12">
            <label for="sumberinfo" class="col-sm-3 col-form-label font-weight-bold">Sumber Info :</label>
            <label for="sumberinfo" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->prospect_source }}</label>
          </div>
          <div class="col-sm-12">
            <label for="pic" class="col-sm-3 col-form-label font-weight-bold">PIC :</label>
            <label for="pic" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->personInCharge->name }}</label>
          </div>

          <div class="col-sm-12">
            <label for="province" class="col-sm-3 col-form-label font-weight-bold">Area :</label>
            <label for="province" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->province->name }}</label>
          </div>
          <div class="col-sm-12">
            <label for="province" class="col-sm-3 col-form-label font-weight-bold">Rumah Sakit / Departemen :</label>
            <label for="province" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->hospital->name }} ( {{$prospect->department->name}} )</label>
          </div>
          <div class="col-sm-12">
            <label for="province" class="col-sm-3 col-form-label font-weight-bold">Info Lain :</label>
            <label for="province" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->review->comment }}</label>
          </div>
          <div class="col-sm-12">
            @can('admin')
            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-edit">Edit PIC dan Info</a>
            @endcan
            @can('am')
            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-edit">Edit PIC dan Info</a>
            @endcan
            @can('nsm')
            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-edit">Edit PIC dan Info</a>
            @endcan
            @can('prj')
            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-edit">Edit PIC dan Info</a>
            @endcan
          </div>



        </div>
      </div>
      <!-- Basic Card Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
        </div>
        <div class="card-body">
          <div class="col-sm-12">
            <label for="bu" class="col-sm-3 col-form-label font-weight-bold">Business Unit :</label>
            <label for="bu" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->unit->name}}</label>
          </div>
          <div class="col-sm-12">
            <label for="category" class="col-sm-3 col-form-label font-weight-bold">Kategori :</label>
            <label for="category" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->config->category->name }}</label>
          </div>
          <div class="col-sm-12">
            <label for="brand" class="col-sm-3 col-form-label font-weight-bold">Brand/Merk :</label>
            <label for="brand" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->config->brand->name}}</label>
          </div>

          <div class="col-sm-12">
            <label for="config" class="col-sm-3 col-form-label font-weight-bold">Config Name :</label>
            <label for="config" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->config->name }}</label>
          </div>

          <div class="col-sm-12">
            <label for="config_code" class="col-sm-3 col-form-label font-weight-bold">Kode Config :</label>
            <label for="config_code" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->config->config_code }}</label>
          </div>
          <div class="col-sm-12">
            <label for="quantity" class="col-sm-3 col-form-label font-weight-bold">Quantity :</label>
            <label for="quantity" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->qty }} {{ $prospect->config->uom }}</label>
          </div>

          <div class="col-sm-12">
            <label for="Harga" class="col-sm-3 col-form-label font-weight-bold">Harga :</label>

            <label for="Harga" style="color:black" class="col-sm-6 col-form-label font-weight-bold">Rp {{ number_format($prospect->submitted_price, 0, ',-', '.') }}</label>
          </div>
          @can('admin')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updateproduk">Update Produk</a>
          @endcan
          @can('am')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updateproduk">Update Produk</a>
          @endcan
          @can('nsm')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updateproduk">Update Produk</a>
          @endcan
          @can('prj')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updateproduk">Update Produk</a>
          @endcan



        </div>
      </div>







    </div>

    <div class="col-lg-6">

      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Tahap - Tahap Promosi</h6>
        </div>
        <div class="card-body">
          <div class="col-sm-12">
            <label for="firstoffer" class="col-sm-3 col-form-label font-weight-bold">First Offer :</label>
            <label for="firstoffer" style="color:black" class="col-sm-6 col-form-label font-weight-bold">@if ($prospect->review->first_offer_date) {{ date('d-M-Y', strtotime($prospect->review->first_offer_date)) }} @else Belum pernah @endif</label>
          </div>
          <div class="col-sm-12">
            <label for="Demo" class="col-sm-3 col-form-label font-weight-bold">Demo :</label>
            <label for="Demo" style="color:black" class="col-sm-6 col-form-label font-weight-bold">@if($prospect->review->demo_date) {{ date('d-M-Y', strtotime($prospect->review->demo_date)) }} @else Belum pernah @endif</label>
          </div>
          <div class="col-sm-12">
            <label for="presentation" class="col-sm-3 col-form-label font-weight-bold">Presentation :</label>
            <label for="presentation" style="color:black" class="col-sm-6 col-form-label font-weight-bold">@if ($prospect->review->presentation_date) {{ date('d-M-Y', strtotime($prospect->review->presentation_date)) }} @else Belum pernah @endif</label>
          </div>

          <div class="col-sm-12">
            <label for="Last Offer" class="col-sm-3 col-form-label font-weight-bold">Last Offer :</label>
            <label for="Last Offer" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> @if ($prospect->review->last_offer_date)
              {{ date('d-M-Y', strtotime($prospect->review->last_offer_date)) }}
              @else
              Belum pernah
              @endif</label>
          </div>

          @can('admin')
          <div class="col-sm-12">

            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatepromosi">Update Tanggal</a>


          </div>
          @endcan
          @can('am')
          <div class="col-sm-12">

            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatepromosi">Update Tanggal</a>


          </div>
          @endcan
          @can('nsm')
          <div class="col-sm-12">

            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatepromosi">Update Tanggal</a>


          </div>
          @endcan
          @can('prj')
          <div class="col-sm-12">

            <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatepromosi">Update Tanggal</a>


          </div>
          @endcan



        </div>
      </div>
      <!-- Collapsable Card Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Review</h6>
        </div>
        <div class="card-body">
          <div class="col-sm-12">
            <label for="userstatus" class="col-sm-3 col-form-label font-weight-bold">User Status :</label>
            <label for="userstatus" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->review->user_status}}</label>
          </div>
          <div class="col-sm-12">
            <label for="purchasing" class="col-sm-3 col-form-label font-weight-bold">Purchasing :</label>
            <label for="purchasing" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->review->purchasing_status}}</label>
          </div>
          <div class="col-sm-12">
            <label for="direksi" class="col-sm-3 col-form-label font-weight-bold">Direksi :</label>
            <label for="direksi" style="color:black" class="col-sm-6 col-form-label font-weight-bold">{{ $prospect->review->direksi_status}}</label>
          </div>

          <div class="col-sm-12">
            <label for="anggaran" class="col-sm-3 col-form-label font-weight-bold">Anggaran :</label>
            <label for="anggaran" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->review->anggaran_status }}</label>
          </div>
          <div class="col-sm-12">
            <label for="jenisanggaran" class="col-sm-3 col-form-label font-weight-bold">Jenis Anggaran:</label>
            <label for="jenisanggaran" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->review->jenis_anggaran }}</label>
          </div>
          <div class="col-sm-12">
            <label for="etapodate" class="col-sm-3 col-form-label font-weight-bold">ETA PO Date:</label>
            <label for="etapodate" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{date('d-M-Y', strtotime($prospect->eta_po_date))}}</label>
          </div>
          <div class="col-sm-12">
            <label for="Chance" class="col-sm-3 col-form-label font-weight-bold">Chance :</label>
            <label for="Chance" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ number_format($prospect->review->chance * 100, 0)}}%</label>
          </div>
          <div class="col-sm-12">
            <label for="nextaction" class="col-sm-3 col-form-label font-weight-bold">Next Action :</label>
            <label for="nextaction" style="color:black" class="col-sm-6 col-form-label font-weight-bold"> {{ $prospect->review->next_action }}</label>
          </div>

          @can('admin')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatereview">Update Review</a>
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatechance">Update Chance</a>
          @endcan
          @can('am')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatereview">Update Review</a>
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatechance">Update Chance</a>
          @endcan
          @can('nsm')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatereview">Update Review</a>
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatechance">Update Chance</a>
          @endcan
          @can('prj')
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatereview">Update Review</a>
          <a href="javascript:void(0)" id="{{$prospect->id}}" class="btn btn-primary btn-sm ml-2 btn-updatechance">Update Chance</a>
          @endcan

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
    var alertMessage = localStorage.getItem("alertMessage");

    // Clear the alert message from local storage
    localStorage.removeItem("alertMessage");
    // Check if the alert message exists and display it
    if (alertMessage) {
      $("#thealert").html(alertMessage);
      $("#thealert").focus();

      // Scroll to the alert message
      $('html, body').animate({
        scrollTop: $("#nopros").offset().top
      }, 1000); // Adjust the duration as needed (1000ms = 1 second)
    }

    function getLastSegment() {
      var pathArray = window.location.pathname.split('/'); // Split the path by '/'
      return pathArray[pathArray.length - 1]; // Get the last segment
    }

    // Extracted parameter
    var id = getLastSegment();

    console.log('Extracted Parameter:', id);

    /*
    var
    $.ajax({
      url"route()",
      method: "POST",
      success:function(response){
        console.log(response.message);
      }
    })
    */




    $('body').on("click", ".btn-edit", function() {
      //var id = $(this).attr("id")

      $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {

          $("#edit-modal").modal("show");
          $("#submiteddate").val(response.valdate);
          $("#data").val(response.prospect.id);
          $("#creatorname").val(response.prospect.creator.name);
          $("#sourceedit").val(response.prospect.prospect_source);
          $("#provinceedit").val(response.prospect.province.name);
          $("#hospitalname").val(response.prospect.hospital.name);

          var deptSelect = $("#departmentname");
          populateSelectFromDatalist('departmentname', response.depopt, "Pilih Departemen");

          var opsdept = $("<option>").val(response.prospect.department_id).text(response.prospect.department.name).attr("selected", true);
          deptSelect.prepend(opsdept);
          //provinceSelect.empty(); // Clear existing options

          var picSelect = $("#personincharge");
          // Populate dropdown options
          picSelect.empty();
          response.prospect.piclist.forEach(function(pivc) {

            var option = $("<option>").val(pivc.user_id).text(pivc.name)
            if (response.prospect.pic_user_id === pivc.user_id) {
              option.attr("selected", true);
              picSelect.prepend(option);
            } else {
              picSelect.append(option);
            }
          });


          var picSelect = $("#personincharge");

          $("#addinfo").val(response.prospect.lateinfo);


        }
      })
    });

    $("#editForm").on("submit", function(e) {
      e.preventDefault()
      var prospect = $("#data").val()

      $.ajax({
        url: "{{ route('admin.prospect.infoupdaterequest', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
        method: "PATCH",
        data: $(this).serialize(),
        success: function(response) {
          $("#edit-modal").modal("hide");
          localStorage.setItem("alertMessage", response.message);
          
          $('html, body').animate({
            scrollTop: 0
          }, 'slow');
          location.reload();
        }
      })
    });

    //produk

    $('body').on("click", ".btn-updateproduk", function() {
      //var id = $(this).attr("id")

      $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {

          $("#produk-modal").modal("show");
          $("#data").val(response.prospect.id);



          var unitselect = $("#editunit");
          var firstunit = $("<option>").val(response.prospect.unit_id).text(response.prospect.unit.name).attr("selected", true);
          unitselect.prepend(firstunit);

          var catSelect = $("#editcategory");
          var unitdata = response.prospect.unit_id;

          function fetchcat2(unitId) {
            // Make an AJAX call to retrieve hospitals based on provinceId
            $.ajax({
              url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
              method: "GET",
              success: function(data) {
                //catSelect.empty();
                populateSelectFromDatalist('editcategory', data.catopt, "Pilih Kategori Produk");
                
              }
            });
          }


          //var selectedunitId = $(this).val();
          fetchcat2(unitdata);
          var firstCategory = $("<option>").val(response.proscat.id).text(response.proscat.name).attr('selected', true);
                  catSelect.prepend(firstCategory);


          var prodSelect = $("#productlist");
          prodSelect.empty();
          populateSelectFromDatalist('productlist', response.configlist, "Pilih Produk");
          var firstconf = $("<option>").val(response.prospect.config_id).text(response.prospect.config.name).attr('selected', true);
          prodSelect.prepend(firstconf);

          $("#editunit, #editcategory").on("change", function() {
            var selectedBusinessUnitId = $("#editunit").val();
            var selectedCategoryId = $("#editcategory").val();
            var selectformId = "productlist";

            if (selectedBusinessUnitId && selectedCategoryId) {
              populateProductSelect(selectedBusinessUnitId, selectedCategoryId, selectformId);
            } else {

              var productSelect = $("#productlist");
              productSelect.empty();
              productSelect.append('<option value="">- Pilih Produk -</option>');
            }
          });






          $("#qtyitem").val(response.prospect.qty);



        }
      })
    });

    $("#produkForm").on("submit", function(e) {
      e.preventDefault()
      var prospect = $("#data").val()

      $.ajax({
        url: "{{ route('admin.prospect.produpdaterequest', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
        method: "PATCH",
        data: $(this).serialize(),
        success: function(response) {
     
          $("#produk-modal").modal("hide");
          localStorage.setItem("alertMessage", response.message);
          $('html, body').animate({
            scrollTop: 0
          }, 'slow');
          location.reload();




        }
      })
    });



    $('body').on("click", ".btn-updatepromosi", function() {


      $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {

          $("#promosi-modal").modal("show");
          $("#data").val(response.prospect.id);
          $("#first").val(response.prospect.review.first_offer_date);
          $("#last").val(response.prospect.review.last_offer_date);
          $("#presentation").val(response.prospect.review.presentation_date);
          $("#demo").val(response.prospect.review.demo_date);

        }
      })
    });

    $("#promosiForm").on("submit", function(e) {
      e.preventDefault();
      var prospect = $("#data").val();
      var update = 1;
      //console.log(id);
      $.ajax({
        url: "{{ route('admin.prospect.promoteupdate', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
        method: "PATCH",
        data: $(this).serialize(),
        success: function(response) {
          localStorage.setItem("alertMessage", response.message);
          $("#promosi-modal").modal("hide");

          $.ajax({
            url: "{{ route('temperupdate', ['id' => ':id']) }}".replace(':id', id),
            type: 'GET',
            data: {
              update: update
            },
            success: function(response) {
              console.log("success");

              location.reload();
            },
            error: function(xhr, status, error) {
              console.error('There was an error in temper update!', error);
            }
          });

          location.reload();
          location.reload();


        },
        error: function(xhr, status, error) {
          console.error('There was an error in promote update!', error);









        }
      })
    });




    $('body').on("click", ".btn-updatereview", function() {
      var id = $(this).attr("id");

      $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {
          //console.log(response.prospect.id);
          $("#review-modal").modal("show");
          $("#data").val(response.prospect.id);
          var userSelect = $("#user_status");
          userSelect.empty();
          var optionuser = $("<option>").val(response.prospect.review.user_status).text(response.prospect.review.user_status);
          userSelect.append(optionuser);

          response.sourceoption.state.forEach(function(usersts) {
            var option = $("<option>").val(usersts.name).text(usersts.name);
            userSelect.append(option);
          });

          var purchasingSelect = $("#purchasing_status");
          purchasingSelect.empty();

          var optionpurchasing = $("<option>").val(response.prospect.review.purchasing_status).text(response.prospect.review.purchasing_status);
          purchasingSelect.append(optionpurchasing);

          response.sourceoption.state.forEach(function(purchasingsts) {
            var option = $("<option>").val(purchasingsts.name).text(purchasingsts.name);
            purchasingSelect.append(option);
          });

          var direksiSelect = $("#direksi_status");
          direksiSelect.empty();

          var optiondireksi = $("<option>").val(response.prospect.review.direksi_status).text(response.prospect.review.direksi_status);
          direksiSelect.append(optiondireksi);

          response.sourceoption.state.forEach(function(direksists) {
            var option = $("<option>").val(direksists.name).text(direksists.name);
            direksiSelect.append(option);
          });


          var anggaranSelect = $("#anggaran_status");
          anggaranSelect.empty();

          var option1 = $("<option>").val(response.prospect.review.anggaran_status).text(response.prospect.review.anggaran_status);
          anggaranSelect.append(option1);

          response.sourceoption.anggaran.review.forEach(function(anggaransts) {
            var option = $("<option>").val(anggaransts.name).text(anggaransts.name);
            anggaranSelect.append(option);
          });

          var jenisanggaranSelect = $("#jenis_anggaran");
          jenisanggaranSelect.empty();

          var optionjns = $("<option>").val(response.prospect.review.jenis_anggaran).text(response.prospect.review.jenis_anggaran);
          jenisanggaranSelect.append(optionjns);

          response.sourceoption.anggaran.Jenis.forEach(function(jenisanggaransts) {
            var option = $("<option>").val(jenisanggaransts.name).text(jenisanggaransts.name);
            jenisanggaranSelect.append(option);
          });

          var today = new Date();
          // Calculate the date after 30 days from today
          var thirtyDaysFromToday = new Date(today);
          thirtyDaysFromToday.setDate(today.getDate() + 30);
          var todayFormatted = today.toISOString().split('T')[0];
          var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];

          $("#etapodate").val(response.prospect.eta_po_date).attr('min', thirtyDaysFromTodayFormatted);;


        }
      })
    });

    $("#reviewForm").on("submit", function(e) {
      e.preventDefault();
      var prospect = $("#data").val()
      var update = 1;
      console.log(id);
      $.ajax({
        url: "{{ route('admin.prospect.reviewupdate', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
        method: "PATCH",
        data: $(this).serialize(),
        success: function(response) {
          localStorage.setItem("alertMessage", response.message);
          $("#review-modal").modal("hide");

          $.ajax({
            url: "{{ route('temperupdate', ['id' => ':id']) }}".replace(':id', id),
            type: 'GET',
            data: {
              update: update
            },
            success: function(response) {
              console.log("success");

              location.reload();
            },
            error: function(xhr, status, error) {
              console.error('There was an error in temper update!', error);
            }
          });

          location.reload();
          location.reload();


        },
        error: function(xhr, status, error) {
          console.error('There was an error in promote update!', error);

        }
      })
    });




    $('body').on("click", ".btn-updatechance", function() {
      var id = $(this).attr("id")

      $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {

          $("#chc-modal").modal("show");
          $("#data").val(response.prospect.id);

          var userstats = response.prospect.review.user_status;
          var purchasingstats = response.prospect.review.purchasing_status;
          var direksistats = response.prospect.review.direksi_status;



          var anggarancek = response.prospect.review.anggaran_status;
          var etapodatecek = response.prospect.eta_po_date;
          var penawaranpertama = response.prospect.review.first_offer_date;

          var today = new Date();
          // Calculate the date after 30 days from today
          var thirtyDaysFromToday = new Date(today);
          thirtyDaysFromToday.setDate(today.getDate() + 30);
          var todayFormatted = today.toISOString().split('T')[0];
          var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];

          //$("#etapodate").val(response.prospect.eta_po_date).attr('min', thirtyDaysFromTodayFormatted);;
          //$("#chance").val(response.prospect.review.chance);
          var chanceSelect = $("#chance");
          chanceSelect.empty();
          var chancenow = response.prospect.review.chance * 100;

          var originalOptionsChance = [];

          // Store the original chance value
          var originalChance = response.prospect.review.chance;

          function rolchance() {
            var optionchance = $("<option>").val(response.prospect.review.chance).text(chancenow + "%");
            chanceSelect.append(optionchance);

            response.sourceoption.chance.forEach(function(chancests) {
              var option = $("<option>").val(chancests.data).text(chancests.name);
              originalOptionsChance.push(option);
              chanceSelect.append(option);
            });

            //console.log(originalOptionsChance);
          }
          rolchance();

          $("#chance").change(function() {
            var selectedChance = $("#chance").val();


            var oneightyDaysFromToday = new Date(today);
            oneightyDaysFromToday.setDate(today.getDate() + 180);
            var oneightyDaysFromTodayFormatted = oneightyDaysFromToday.toISOString().split('T')[0];
            var onefiftyDaysFromToday = new Date(today);
            onefiftyDaysFromToday.setDate(today.getDate() + 150);
            var onefiftyDaysFromTodayFormatted = onefiftyDaysFromToday.toISOString().split('T')[0];

            //console.log(anggarancek);
            console.log(etapodatecek < onefiftyDaysFromTodayFormatted);
            console.log(penawaranpertama);
            /*if (penawaranpertama == null) {
            if (selectedChance > 0.2) {
              alert("Warning: Silahkaan Update Tanggal First Offer !!");
              chanceSelect.empty();
              rolchance();

            }

          } else
          if (['Belum Ada', 'Belum Tahu', 'Usulan'].includes(anggarancek)) {

            if (selectedChance > 0.4) {
              alert("Warning: Chance Tidak Bisa Lebih dari 40% Saat Anggaran Status masih 'Belum Ada' , 'Usulan' atau 'Belum Tahu'");
              chanceSelect.empty();
              rolchance();

            }
          } else {
              
            if (((['Ada Neutral', 'Ada Saingan'].includes(anggarancek) || etapodatecek > oneightyDaysFromTodayFormatted) )&&(['Belum Tahu','Menolak'].includes(userstats)) && (['Belum Tahu','Menolak'].includes(direksistats)) && (['Belum Tahu','Menolak'].includes(purchasingstats))) {
              if (selectedChance > 0.6) {
                alert("Warning: Chance Tidak Bisa Lebih dari 60% Saat Estimasi Po Date Lebih Dari 6 Bulan atau anggaran bukan 'Ada Sesuai'");
                chanceSelect.empty();
                rolchance();

              }
            } else {
              console.log((['Belum Tahu','Menolak'].includes(userstats)))
              if (anggarancek != 'Ada Sesuai' && etapodatecek > onefiftyDaysFromTodayFormatted && (['Belum Tahu','Menolak'].includes(userstats)) && (['Belum Tahu','Menolak'].includes(direksistats)) && (['Belum Tahu','Menolak'].includes(purchasingstats))) {
                console.log(etapodatecek < onefiftyDaysFromTodayFormatted);
                if (selectedChance > 0.8) {

                  alert("Apakah pihak rumah sakit sudah melakukan pembelian ?");
                  chanceSelect.empty();
                  rolchance();
                }

              }


            }



          }
        });

        if ($("#anggaran_status").val == "Usulan" || $("#anggaran_status").val == "Usulan") {

        }
        */
      })
            var next_actionSelect = $("#next_action");
            next_actionSelect.empty();

            var optionnext_action = $("<option>").val(response.prospect.review.next_action).text(response.prospect.review.next_action);
            next_actionSelect.append(optionnext_action);

            response.sourceoption.naction.forEach(function(next_actionsts) {
              var option = $("<option>").val(next_actionsts.name).text(next_actionsts.name);
              next_actionSelect.append(option);
            });




          
        }
      });
    });





    $("#chcForm").on("submit", function(e) {
      e.preventDefault()
      var prospect = $("#data").val()
      var update = 1;
      console.log(id);
      $.ajax({
        url: "{{ route('admin.prospect.chcupdate', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
        method: "PATCH",
        data: $(this).serialize(),
        success: function(response) {
          localStorage.setItem("alertMessage", response.message);
          $("#chcForm").modal("hide");

          $.ajax({
            url: "{{ route('temperupdate', ['id' => ':id']) }}".replace(':id', id),
            type: 'GET',
            data: {
              update: update
            },
            success: function(response) {
              console.log("success");
              location.reload();
            },
            error: function(xhr, status, error) {
              console.error('There was an error in temper update!', error);
            }
          });
          location.reload();
          location.reload();
        },
        error: function(xhr, status, error) {
          console.error('There was an error in promote update!', error);
        }


      })
    });


  });
</script>
@endpush