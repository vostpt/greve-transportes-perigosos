@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('External Auth List') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table id="ext_auth_list" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Key') }}</th>
                        <th>{{ __('Secret') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Key') }}</th>
                        <th>{{ __('Secret') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Confirmar Ação - <span id="action_title">Titulo da ação</span></h5>
            </div>
            <div class="modal-body">
                <p><span id="action_description">Descrição da ação</span></p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="modal_form" class="ui form" action="{{ route('externalauth.delete') }}">
                    @csrf
                    <input id="ext_auth_id" type="hidden" name="id" value="0" />
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    function copyStringToClipboard (str) {
       // Create new element
       var el = document.createElement('textarea');
       // Set value (string to be copied)
       el.value = str;
       // Set non-editable to avoid focus and move outside of view
       el.setAttribute('readonly', '');
       el.style = {position: 'absolute', left: '-9999px'};
       document.body.appendChild(el);
       // Select text inside element
       el.select();
       // Copy text to clipboard
       document.execCommand('copy');
       // Remove temporary element
       document.body.removeChild(el);
    }
    function deleteExtAuth(id) {
        $('#modal_form').attr('action', '{{ route('externalauth.delete') }}');
        $('#action_title').html("Eliminar Autenticação Externa nº"+id);
        $('#action_description').html("Esta ação irá eliminar a autenticação externa nº"+id+", esta não poderá ser novamente utilizada para atualizar bombas de combustivel.");
        $("#ext_auth_id").val(id);
        $('.modal').modal('show');
    }
    $(document).ready(function() {
        $('#ext_auth_list').DataTable( {
            "ajax": { 
                "url": "{{ route('externalauth.fetch.all') }}",
                "dataSrc": function (json) {
                    json.data.forEach((element,index) => {
                        json.data[index]["secret"] = '<a href="#" onclick="copyStringToClipboard(\''+json.data[index]["secret"]+'\')">Copiar</a>';
                        json.data[index]["actions"] = '<a href="#" onclick="deleteExtAuth('+json.data[index]["id"]+')"><i class="fas fa-trash"></i></a>';
                    });
                    return json.data;
                }
            },   
            "columns": [
                { "data": "id" },
                { "data": "key" },
                { "data": "secret" },
                { "data": "brand" },
                { "data": "actions" }
            ]   
        });
    });

</script>
@endsection