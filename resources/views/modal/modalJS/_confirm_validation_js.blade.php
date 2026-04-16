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



