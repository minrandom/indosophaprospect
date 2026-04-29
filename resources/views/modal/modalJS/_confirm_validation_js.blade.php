<script>
$(function () {

    $(document).on('submit', '.js-confirm-submit-visit', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Submit Visit?',
            text: 'This visit will be sent for validation.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $(document).on('submit', '.js-confirm-validate-task', function (e) {
        e.preventDefault();
        const form = this;


        Swal.fire({
            title: 'Validate Task?',
            text: 'This task will be marked as validated.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Validate',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $(document).on('submit', '.js-confirm-validate-visit', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Validate Visit?',
            html: `<div>This action will:</div>
                    <ul style="text-align:left;">
                        <li>Update Data</li>
                        <li>Mark tasks and visit as done</li>
                        <li>Create follow-up tasks for unvalidated tasks</li>
                    </ul>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Finalize Visit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});
</script>



@if(session('open_lead_action_selector') && session('task_id'))
<script>
$(function () {
    const taskId = @json(session('task_id'));
    const hospitalTarget = @json(session('hospital_target'));

    // 🔥 default options
    let inputOptions = {
        promo: 'Change to Promo',
        lead_to_prospect: 'Update into Prospect',
        delayed: 'Delayed Lead'
    };

    // 🔥 allow drop only if NOT Key Account / Prioritas
    if (!['Key Account', 'Prioritas'].includes(hospitalTarget)) {
        inputOptions.drop = 'Drop';
    }


    Swal.fire({
        title: 'Choose Lead Action',
        input: 'select',
        inputOptions: inputOptions,
        inputPlaceholder: 'Select action',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'Please choose an action';
            }
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        const action = result.value;

        if (action === 'drop' && ['Key Account', 'Prioritas'].includes(hospitalTarget)) {
            Swal.fire('Not Allowed', 'This lead must be converted to Promo first.', 'warning');
            return;
        }


        if (action === 'drop') {
            window.location.href = "{{ url('missions/task') }}/" + taskId + "/lead-action/drop";
        }

        if (action === 'promo') {
            window.location.href = "{{ url('missions/task') }}/" + taskId + "/lead-action/promo";
        }

        if (action === 'lead_to_prospect') {
            window.location.href = "{{ url('missions/task') }}/" + taskId + "/lead-action/lead_to_prospect";
        }

        if (action === 'delayed') {
            window.location.href = "{{ url('missions/task') }}/" + taskId + "/lead-action/delayed";
        }
    });
});
</script>
@endif



