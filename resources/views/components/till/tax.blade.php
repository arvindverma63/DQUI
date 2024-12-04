
  <!-- Modal -->
  <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="taxModalLabel">Add Tax</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="input-group mb-3">
                <span class="input-group-text">%</span>
                <input type="number" step="0.01" class="form-control" id="taxInput" aria-label="">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary text-white" id="addTax" data-bs-dismiss="modal">Save changes</button>
        </div>
      </div>
    </div>
  </div>
