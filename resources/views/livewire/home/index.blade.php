<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            *
        @endslot
        @slot('title')
            Главная
        @endslot
    @endcomponent

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="uil uil-exclamation-octagon me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

</div>
