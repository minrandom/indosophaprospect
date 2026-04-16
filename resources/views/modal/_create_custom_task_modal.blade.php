<div class="modal fade" id="createCustomTaskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:1rem;">
            <div class="modal-header">
                <h5 class="modal-title">Create Custom Task</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('missions.customTask.store') }}" class="js-confirm-create-custom-task">
                @csrf

                <div class="modal-body">
                    <div class="row">

                        {{-- Province --}}
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted">Province</label>
                            <select name="province_id"
                                    id="ct_province_id"
                                    class="form-control"
                                    required>
                                <option value="">Select Province</option>

                            </select>
                        </div>
                        {{-- Hospital --}}
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted">Hospital</label>
                            <select name="hospital_id"
                                    id="ct_hospital_id"
                                    class="form-control"
                                    required>
                                <option value="">Select Hospital</option>

                            </select>
                        </div>

                        {{-- Department --}}
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted">Department</label>
                            <select name="department"
                                    id="ct_department"
                                    class="form-control ">

                                <option value="">Select Department</option>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small text-muted">User To Meet</label>
                            <input type="text"
                                   name="user_to_meet"
                                   class="form-control"
                                   placeholder="Input user to meet">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small text-muted">Priority</label>
                            <select name="priority_level" class="form-control select2" required>
                                <option value="">Select Priority</option>
                                <option value="SUPER URGENT">SUPER URGENT</option>
                                <option value="URGENT">URGENT</option>
                                <option value="PENTING">PENTING</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="small text-muted">Task Purpose</label>
                            <textarea name="task_purpose"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Input task purpose"
                                      required></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="small text-muted">Expected Outcome</label>
                            <textarea name="expected_outcome"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Input expected outcome"
                                      required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Custom Task</button>
                </div>
            </form>
        </div>
    </div>
</div>



