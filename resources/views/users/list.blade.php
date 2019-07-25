@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Users List') }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3>{{ __('Verified Users') }}</h3>
            <table id="users_list_verified" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </tfoot>
            </table>
            <h3>{{ __('Users Lacking Verification') }}</h3>
            <table id="users_list_not_verified" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
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
                <form method="POST" id="modal_form" class="ui form" action="{{ route('users.verify') }}">
                    @csrf
                    <input id="user_id" type="hidden" name="id" value="0" />
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
    function validateUser(id) {
        $('#modal_form').attr('action', '{{ route('users.verify') }}');
        $('#action_title').html("Validar Email do Utilizador nº"+id);
        $('#action_description').html("Esta ação irá validar o Email do Utilizador nº"+id+", evitando que o mesmo o faça através do email enviado.");
        $("#user_id").val(id);
        $('.modal').modal('show');
    }
    function deleteUser(id) {
        $('#modal_form').attr('action', '{{ route('users.delete') }}');
        $('#action_title').html("Eliminar Utilizador nº"+id);
        $('#action_description').html("Esta ação irá eliminar o Utilizador nº"+id+", este não poderá aceder à conta nem ser utilizado noutras funcionalidades.");
        $("#user_id").val(id);
        $('.modal').modal('show');
    }
    $(document).ready(function() {
        $('#users_list_verified').DataTable( {
            "ajax": { 
                "url": "{{ route('users.fetch.verified') }}",
                "dataSrc": function (json) {
                    json.data.forEach((element,index) => {
                        json.data[index]["actions"] = '<a href="#" onclick="deleteUser('+json.data[index]["id"]+')"><i class="fas fa-trash"></i></a>';
                    });
                    return json.data;
                }
            },   
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" },
                { "data": "actions" }
            ]   
        });
        $('#users_list_not_verified').DataTable( {
            "ajax": { 
                "url": "{{ route('users.fetch.not_verified') }}",
                "dataSrc": function (json) {
                    json.data.forEach((element,index) => {
                        json.data[index]["actions"] = '<a href="#" onclick="validateUser('+json.data[index]["id"]+')"><i class="fas fa-check"></i></a>';
                    });
                    return json.data;
                }
            },            
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" },
                { "data": "actions" }
            ]
        });
    });

</script>
@endsection