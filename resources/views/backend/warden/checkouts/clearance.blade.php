<div class="modal fade" id="clearanceModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    Warden Clearance Inspection
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Resident Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div id="clearanceResidentInfo"></div>
                    </div>
                </div>

                <!-- Subscription Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-semibold">
                        Issued Subscriptions / Accessories
                    </div>

                    <div class="card-body">
                        <form id="clearanceForm">
                            @csrf
                            <input type="hidden" name="checkout_id" id="clearance_checkout_id">

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Refundable</th>
                                            <th>Status</th>
                                            <th>Penalty</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="clearanceTableBody"></tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="mt-3 text-end">
                                <h6>Total Penalty: ₹ <span id="totalPenalty">0</span></h6>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-danger"
                        onclick="submitClearance('rejected')">
                    Reject
                </button>

                <button class="btn btn-success"
                        onclick="submitClearance('approved')">
                    Approve Clearance
                </button>
            </div>

        </div>
    </div>
</div>
