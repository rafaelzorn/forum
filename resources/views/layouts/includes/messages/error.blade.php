@if (session('message')['type'] == 'error')
    <div class="alert alert-danger alert-dismissible fade show m-t-15" role="alert">
        {{ session('message')['message'] }}

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
