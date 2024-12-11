<div class="modal fade" wire:ignore.self id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">
                    Удаление профиля пользователя
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="closeDeleteModal">
                </button>
            </div>
            <div class="modal-body">
                <p>Вы действидельно хотите удалить профиль пользователя {{ $item_delete_name }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                    wire:click="closeDeleteModal()">Закрыть</button>
                <button class="btn btn-danger" wire:click="deleteItem()">Удалить</button>
            </div>
        </div>
    </div>
</div>
