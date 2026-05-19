@extends('layout.backend.app', ['title' => 'Installbase Task'])

@section('content')
<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card shadow border-0 mb-4" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3">Installbase Task Update</h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
      <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>
    </div>
  </div>

  <form method="POST" action="{{ route('missions.task.installbase.update', $task->id) }}">
    @csrf

    <div class="card shadow border-0" style="border-radius:1rem;">
      <div class="card-body">

        <div class="row">
          <div class="col-md-6 mb-2">
            <div class="small text-muted">IB Code</div>
            <div class="font-weight-bold">{{ $installbase->installbase_code ?? '-' }}</div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="small text-muted">Province</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->hospital)->province)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">City</div>
            <div class="font-weight-bold">{{ optional($installbase->hospital)->city ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Hospital</div>
            <div class="font-weight-bold">{{ optional($installbase->hospital)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Department</label>
            <select name="department" id="department" class="form-control">
              <option value="">{{ old('department', $installbase->department) }}</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">PIC to Recall</label>
            <input type="text" name="pic_to_recall" class="form-control"
                   value="{{ old('pic_to_recall', $installbase->pic_to_recall) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Department Phone</label>
            <input type="text" name="department_phone" class="form-control"
                   value="{{ old('department_phone', $installbase->department_phone) }}">
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Brand</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->product)->brand)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Category</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->product)->category)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Model / Type</div>
            <div class="font-weight-bold">{{ optional($installbase->product)->model_type ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Serial Number</label>
            <input type="text" name="serial_number" class="form-control"
                   value="{{ old('serial_number', $installbase->serial_number) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Installation Date</label>
            <input type="date" name="installation_date" class="form-control"
                   value="{{ old('installation_date', $installbase->installation_date ? \Carbon\Carbon::parse($installbase->installation_date)->format('Y-m-d') : '') }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Equipment Status</label>
            <select name="installstatus" id="installstatus" class="form-control">

            </select>
          </div>

          <div class="col-md-6 mb-3">
              <label class="small text-muted">End Of Warranty</label>
              <input type="date" name="end_of_warranty" class="form-control"
              value="{{ old('end_of_warranty', $installbase->end_of_warranty ? \Carbon\Carbon::parse($installbase->end_of_warranty)->format('Y-m-d') : '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="small text-muted">Warranty Status</label>
              <select name="warranty_status" id="warranty_status" class="form-control">

              </select>
            </div>

            <div class="card shadow-sm border-0 mb-3" style="border-radius:1rem;">
                <div class="card-body">
                    <div class="h6 text-uppercase mb-3">Equipment Photo</div>

                    <video id="equipmentCameraPreview"
                        autoplay
                        playsinline
                        style="width:100%; max-width:420px; border-radius:12px; display:none;">
                    </video>

                    <canvas id="equipmentPhotoCanvas" style="display:none;"></canvas>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-primary" id="btnOpenEquipmentCamera">
                            Open Back Camera
                        </button>

                        <button type="button" class="btn btn-sm btn-success" id="btnCaptureEquipmentPhoto" style="display:none;">
                            Capture Photo
                        </button>

                        <button type="button" class="btn btn-sm btn-secondary" id="btnCloseEquipmentCamera" style="display:none;">
                            Close Camera
                        </button>
                    </div>

                        <div class="mt-3" id="equipmentPhotoResult">
                            @if(!empty($installbase->label_photo))
                                <img src="{{ $installbase->label_photo }}"
                                    style="max-width:220px; border-radius:10px;"
                                            alt="Label Photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        <input type="hidden" name="label_photo" id="label_photo">
        <input type="hidden" name="ib_id" value="{{ $installbase->id }}">
        <div class="card-footer bg-white text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update Installbase</button>
        </div>
        </div>

    <div class="row">
        <div class="col-12 mb-3">
            <label class="small text-muted">Report / Notes</label>
            <textarea name="report_result"
                    class="form-control"
                    rows="4"
                    placeholder="Write task result / notes here...">{{ old('report_result', $task->report_result) }}</textarea>
        </div>
    </div>
  </form>

</div>
@endsection


@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/jquery/jquery.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 var id = $("input[name='ib_id']").val();
 $('[data-toggle="tooltip"]').tooltip();
       $.ajax({
        url: "{{ route('update.installbase', ['installbase' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {
            console.log(response);
            var deptSelect = $("#department");
            editHosPopulateSelect(deptSelect, response.deptdata,response.installbase.department, {width: '100%'});
            // if(response.installbase.department){
            // deptSelect.append(new Option(response.installbase.department, response.installbase.department, true, true));
            // }
            console.log(response.equipmentStatus);

            var statusSelect = $("#installstatus");
            editHosPopulateSelect(statusSelect, response.equipmentStatus, response.installbase.installbase_status, {width: '100%'});

            var warrantySelect = $("#warranty_status");
            editHosPopulateSelect(warrantySelect, response.warrantyStatus, response.installbase.maintenance_status, {width: '100%'});



        }




        })



});
</script>

<script>
$(function () {
    let equipmentStream = null;

    const video = document.getElementById('equipmentCameraPreview');
    const canvas = document.getElementById('equipmentPhotoCanvas');

    async function openEquipmentCamera() {
        try {
            equipmentStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: "environment" } // back camera
                },
                audio: false
            });

            video.srcObject = equipmentStream;

            $('#equipmentCameraPreview').show();
            $('#btnCaptureEquipmentPhoto').show();
            $('#btnCloseEquipmentCamera').show();

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Camera Error',
                text: 'Cannot access back camera. Please allow camera permission.'
            });
        }
    }

    function closeEquipmentCamera() {
        if (equipmentStream) {
            equipmentStream.getTracks().forEach(track => track.stop());
            equipmentStream = null;
        }

        $('#equipmentCameraPreview').hide();
        $('#btnCaptureEquipmentPhoto').hide();
        $('#btnCloseEquipmentCamera').hide();
    }

    function captureEquipmentPhoto() {
        if (!video.videoWidth || !video.videoHeight) {
            Swal.fire('Error', 'Camera is not ready yet.', 'error');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const photoData = canvas.toDataURL('image/png');

        uploadEquipmentPhoto(photoData);
    }

    function uploadEquipmentPhoto(photoData) {
        $('#label_photo').val(res.photo_url);

            $('#equipmentPhotoResult').html(`
                <div class="mb-2">
                    <img src="${res.photo_url}"
                        style="max-width:220px;border-radius:10px;">
                </div>

                <div class="small text-success">
                    Photo ready to submit.
                </div>
            `);

        $.ajax({
            url: "{{ route('installbase.equipmentPhoto.upload', $installbase->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                photo_data: photoData
            },
            success: function (res) {
                if (!res.success) {
                    Swal.fire('Failed', res.message || 'Upload failed.', 'error');
                    return;
                }

                $('#equipmentPhotoResult').html(`
                    <img src="${res.photo_url}"
                         style="max-width:220px; border-radius:10px;"
                         alt="Equipment Photo">
                `);

                Swal.fire({
                    icon: 'success',
                    title: 'Uploaded',
                    text: 'Equipment photo uploaded successfully.'
                });

                closeEquipmentCamera();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Failed', 'Cannot upload equipment photo.', 'error');
            }
        });
    }

    $('#btnOpenEquipmentCamera').on('click', openEquipmentCamera);
    $('#btnCaptureEquipmentPhoto').on('click', captureEquipmentPhoto);
    $('#btnCloseEquipmentCamera').on('click', closeEquipmentCamera);
});
</script>
