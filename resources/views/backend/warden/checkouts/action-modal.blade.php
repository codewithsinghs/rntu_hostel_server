<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="actionTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="actionMessage"></p>

                <div id="actionExtraFields" class="d-none">
                    <label class="form-label">Remarks</label>
                    <textarea id="actionRemarks" class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button id="confirmActionBtn" class="btn"></button>
            </div>

        </div>
    </div>
</div>
