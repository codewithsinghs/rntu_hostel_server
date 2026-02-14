@extends('resident.layout')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h4>Resident Checkout</h4>
            <button class="btn btn-primary" id="btnCreate">
                New Checkout
            </button>
        </div>

        <div id="checkoutTable">
            <div class="text-muted">Loading...</div>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="checkoutModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="modalTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="checkoutForm">
                    <div class="modal-body">

                        <input type="hidden" name="id" id="id">

                        <div class="mb-3">
                            <label>Resident</label>
                            <select name="resident_id" class="form-control"></select>
                        </div>

                        <div class="mb-3">
                            <label>Requested Exit Date</label>
                            <input type="date" name="requested_exit_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="refund_expected" id="refund_expected">
                            <label class="form-check-label">
                                Refund Expected
                            </label>
                        </div>

                        <div id="refundFields" class="border rounded p-3 d-none">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input class="form-control" name="account_holder" placeholder="Account Holder">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input class="form-control" name="bank_name" placeholder="Bank Name">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input class="form-control" name="account_number" placeholder="Account Number">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input class="form-control" name="ifsc_code" placeholder="IFSC Code">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button class="btn btn-primary" id="saveBtn">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.Routes = {
            checkoutIndex: "{{ route('checkouts.index') }}",
            checkoutStore: "{{ route('checkouts.store') }}",
            checkoutShow: id => `{{ route('checkouts.show', ':id') }}`.replace(':id', id),
            checkoutUpdate: id => `{{ route('checkouts.update', ':id') }}`.replace(':id', id),
            checkoutDelete: id => `{{ route('checkouts.destroy', ':id') }}`.replace(':id', id),
            checkoutFinalExit: id => `{{ route('checkouts.finalExit', ':id') }}`.replace(':id', id),
        };



        /* ---------------- GLOBAL API ---------------- */
        const Api = {
            get: url => $.get(url),
            post: (url, data) => $.ajax({
                url,
                method: 'POST',
                data,
                processData: false,
                contentType: false
            }),
            put: (url, data) => $.ajax({
                url,
                method: 'POST',
                data,
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                processData: false,
                contentType: false
            }),
            delete: url => $.ajax({
                url,
                method: 'DELETE'
            })
        };

        /* ---------------- FORM HELPER ---------------- */
        const FormHelper = {
            clear(form) {
                $(form).find('.is-invalid').removeClass('is-invalid');
                $(form).find('.invalid-feedback').remove();
            },
            inject(form, errors) {
                Object.keys(errors).forEach(k => {
                    const el = $(form).find(`[name="${k}"]`);
                    el.addClass('is-invalid')
                        .after(`<div class="invalid-feedback">${errors[k][0]}</div>`);
                });
            }
        };

        /* ---------------- CHECKOUT MODULE ---------------- */
        const CheckoutModule = {

            init() {
                this.loadTable();
                this.bind();
                this.loadResidents();
            },

            bind() {
                $('#btnCreate').click(() => this.open());
                $('#refund_expected').change(e => {
                    $('#refundFields').toggleClass('d-none', !e.target.checked);
                });
                $('#saveBtn').click(e => {
                    e.preventDefault();
                    this.save();
                });
            },

            loadTable() {
                // Api.get('api.resident.leaves.index').done(res => {
                Api.get(Routes.checkoutIndex).done(res => {
                    let html = `<table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>`;
                    res.data.forEach(r => {
                        html += `
                <tr>
                    <td>${r.id}</td>
                    <td>${r.resident_name}</td>
                    <td>${r.status}</td>
                    <td>
                        <button class="btn btn-sm btn-info"
                            onclick="CheckoutModule.view(${r.id})">View</button>
                        <button class="btn btn-sm btn-warning"
                            onclick="CheckoutModule.edit(${r.id})">Edit</button>
                        <button class="btn btn-sm btn-danger"
                            onclick="CheckoutModule.remove(${r.id})">Delete</button>
                        <button class="btn btn-sm btn-success"
                            onclick="CheckoutModule.finalExit(${r.id})">Final Exit</button>
                    </td>
                </tr>`;
                    });
                    html += '</table>';
                    $('#checkoutTable').html(html);
                });
            },

            loadResidents() {
                Api.get(Routes.checkoutIndex).done(res => {
                    let opt = '<option value="">Select Resident</option>';
                    res.data.forEach(r => {
                        opt += `<option value="${r.id}">${r.name}</option>`;
                    });
                    $('[name="resident_id"]').html(opt);
                });
            },

            open(data = null) {
                $('#modalTitle').text(data ? 'Edit Checkout' : 'New Checkout');
                $('#checkoutForm')[0].reset();
                FormHelper.clear('#checkoutForm');

                if (data) {
                    Object.keys(data).forEach(k => {
                        $(`[name="${k}"]`).val(data[k]);
                    });
                    $('#refund_expected').trigger('change');
                }

                $('#checkoutModal').modal('show');
            },

            view(id) {
                Api.get(Routes.checkoutShow).done(res => {
                    this.open(res.data);
                    $('#checkoutForm input, #checkoutForm textarea, #checkoutForm select')
                        .prop('disabled', true);
                });
            },

            edit(id) {
                // Api.get(`/api/checkouts/${id}`).done(res => {
                Api.get(Routes.checkoutShow).done(res => {
                    this.open(res.data);
                    $('#checkoutForm input, #checkoutForm textarea, #checkoutForm select')
                        .prop('disabled', false);
                });
            },

            save() {
                FormHelper.clear('#checkoutForm');
                const id = $('[name="id"]').val();
                const data = new FormData($('#checkoutForm')[0]);

                const req = id ?
                    // Api.put(`/api/checkouts/${id}`, data) :
                    // Api.post('/api/checkouts', data);
                    Api.put(Routes.checkoutUpdate(id), data) :
                    Api.post(Routes.checkoutStore, data);

                req.done(res => {
                    Swal.fire('Success', res.message, 'success');
                    $('#checkoutModal').modal('hide');
                    this.loadTable();
                }).fail(xhr => {
                    if (xhr.status === 422) {
                        FormHelper.inject('#checkoutForm', xhr.responseJSON.errors);
                    }
                    Swal.fire('Error', 'Fix validation errors', 'error');
                });
            },

            remove(id) {
                Swal.fire({
                    title: 'Delete?',
                    showCancelButton: true
                }).then(r => {
                    if (r.isConfirmed) {
                        Api.delete(Routes.checkoutShow).done(() => {
                            Swal.fire('Deleted', '', 'success');
                            this.loadTable();
                        });
                    }
                });
            },
            checkoutFinalExit

            finalExit(id) {
                Swal.fire({
                    title: 'Confirm Final Exit?',
                    text: 'Room & bed will be released',
                    icon: 'warning',
                    showCancelButton: true
                }).then(r => {
                    if (r.isConfirmed) {
                        // Api.post(`/api/checkouts/${id}/final-exit`, {})
                        Api.post(Routes.checkoutFinalExit(id), {})
                            .done(res => {
                                Swal.fire('Completed', res.message, 'success');
                                this.loadTable();
                            });
                    }
                });
            }
        };

        $(document).ready(() => CheckoutModule.init());
    </script>
@endpush
