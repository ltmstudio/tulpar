<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($shift_edit_id != '')
                            Изменить смену
                        @else
                            Создать новую смену
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="hours">Часы</label>
                            @error('hours')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="number" class="form-control" id="hours" wire:model="hours"
                                placeholder="Введите количество часов">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="state">Состояние</label>
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="state" wire:model="state"
                                placeholder="Введите состояние">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="closeCreateModal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</form>