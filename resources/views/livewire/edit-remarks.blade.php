<!-- livewire/edit-remarks.blade.php -->
<div class="card-body border-top p-9">
    <form wire:submit.prevent="update">
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Remarks</label>
            <div class="col-lg-8 fv-row">
                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input wire:model.defer="attendance.remarks" class="form-check-input" type="radio" name="remarks" value="Sick" checked="checked" />
                    <span class="form-check-label text-gray-600">Sick</span>
                </label>
                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input wire:model.defer="attendance.remarks" class="form-check-input" type="radio" name="remarks" value="Family Matters" />
                    <span class="form-check-label text-gray-600">Family Matters</span>
                </label>
                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input wire:model.defer="attendance.remarks" class="form-check-input" type="radio" name="remarks" value="Late" />
                    <span class="form-check-label text-gray-600">Late</span>
                </label>
                <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input wire:model.defer="attendance.remarks" class="form-check-input" type="radio" name="remarks" value="Others" onclick="toggleRemarksField()" />
                    <span class="form-check-label text-gray-600">Others</span>
                </label>
                @error('attendance.remarks') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button type="submit" data-bs-dismiss="modal" class="btn btn-primary">Save</button>
        <button data-bs-dismiss="modal" type="button" class="btn btn-secondary">Cancel</button>
    </form>
</div>