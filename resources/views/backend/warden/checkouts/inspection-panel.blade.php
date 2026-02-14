<div class="modal fade" id="inspectionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Room Clearance Inspection
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Resident Info -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold">Resident Information</h6>
                        <div id="inspectionResidentInfo"></div>
                    </div>
                </div>

                <!-- Subscription Inspection Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-semibold">
                        Issued Accessories & Services
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Refundable</th>
                                        <th>Status</th>
                                        <th>Penalty</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody id="inspectionTableBody"></tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <!-- Overall Remarks -->
                <div class="mt-4">
                    <label class="form-label fw-semibold">
                        Overall Inspection Remark
                    </label>
                    <textarea id="overallRemark" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-danger"
                        onclick="submitInspection('reject')">
                    Reject
                </button>

                <button class="btn btn-success"
                        onclick="submitInspection('approve')">
                    Approve & Proceed
                </button>
            </div>

        </div>
    </div>
</div>
