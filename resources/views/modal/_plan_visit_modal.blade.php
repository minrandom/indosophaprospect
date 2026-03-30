@php
    $role = strtolower(auth()->user()->role ?? '');
    $isFs = $role === 'fs';
@endphp

<script>window.isFs = @json($isFs);</script>


<div class="modal fade" id="planVisitModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Plan Visit</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="pv_hospital_id">
        <input type="hidden" id="pv_task_ids">

        <div class="mb-2">
          <div class="small text-muted">Hospital</div>
          <div class="font-weight-bold" id="pv_hospital_name">-</div>
        </div>

        <div class="mb-3">
          <div class="small text-muted">Selected Tasks</div>
          <div class="font-weight-bold" id="pv_task_count">0</div>
        </div>

        <div class="form-group">
          <label class="small text-uppercase">Scheduled At</label>
          <input type="date" id="pv_scheduled_at" class="form-control" required>
        </div>

        <div class="form-group">
        <label class="small text-uppercase">Schedule Time</label>
        <select id="pv_schedule_time" class="form-control" required>
            <option value="">Select Time</option>
            <option value="08:00">08:00</option>
            <option value="10:00">10:00</option>
            <option value="12:00">12:00</option>
            <option value="14:00">14:00</option>
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
            <option value="20:00">20:00</option>
        </select>
        </div>

        <div class="form-group">
        <label class="small text-uppercase">Duration</label>
        <select id="pv_schedule_duration" class="form-control" required>
            <option value="">Select Duration</option>
            <option value="120">2 Hours</option>
            <option value="240">4 Hours</option>
            <option value="360">6 Hours</option>
            <option value="480">8 Hours</option>
        </select>
        </div>

        @if(!$isFs)
          <div class="form-group">
            <label class="small text-uppercase">PIC</label>
            <select id="pv_pic_user_id" class="form-control">
              <option value="">Select PIC</option>
              @foreach($users ?? [] as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
        @elseif($isFs)
          <input type="hidden" id="pv_pic_user_id" value="{{ auth()->id() }}">
          <div class="form-group">
            <label class="small text-uppercase">PIC</label>
            <select id="pv_pic_user_id" class="form-control">
              <option value="">Select PIC</option>
              @foreach($users ?? [] as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
        @endif

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnSubmitPlanVisit" class="btn btn-success">
          Create Visit
        </button>
      </div>
    </div>
  </div>
</div>
