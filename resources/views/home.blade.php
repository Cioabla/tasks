@extends('layouts.base')

@section('content')
    <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
    </section>

        <!-- Main content -->
    <section class="content">
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- /.nav-tabs-custom -->

                    <!-- TO DO List -->
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">Tasks List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                            <ul class="todo-list">
                                <li id="example-li-element" style="display: none">
                                    <!-- drag handle -->
                                    <span class="handle">
                      </span>
                                    <span id="span-name" class="text"><b>Name:</b></span>
                                    <span id="span-description" class="text"><b>Description:</b></span>
                                    <span id="span-owned" class="text"><b>Owned by:</b></span>
                                    <span id="span-assigned" class="text"><b>Assigned to:</b></span>
                                    <small id="span-status" class="label label-danger"><i class="fa"></i><b>Status:</b></small>

                                </li>
                                @foreach($data['tasks'] as $task)
                                    <li id="li-{{$task->id}}">
                                        <!-- drag handle -->
                                        <span class="handle">
                      </span>
                                        <span class="text"><b>Name:</b> {{ $task->name }}</span>
                                        <span class="text"><b>Description:</b> {{ $task->description }}</span>
                                        <span class="text"><b>Owned by:</b> {{ $task->user->name }}</span>
                                        <span class="text"><b>Assigned to:</b> {{ $task->assignTo->name }}</span>
                                        @if($task->status == 0)
                                            <small class="label label-success"><i class="fa"></i><b>Status:</b> {{ $task->status }}</small>
                                        @else
                                            <small class="label label-danger"><i class="fa"></i><b>Status:</b> {{ $task->status }}</small>
                                        @endif
                                        <div class="tools">
                                            <i id="edit-task edit-{{$task->id}}" data-id="{{$task->id}}" class="fa fa-edit"></i>
                                            <i id="delete-task delete-{{$task->id}}" data-id="{{$task->id}}" class="fa fa-trash-o"></i>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="add-new-task" style="display: none" class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Create Task</h3>
                            </div>
                            <div class="box-body">
                                <div class="input-group">
                                    <input  style="display: block;width: 80%" id="name" type="text" placeholder="Task Name">
                                    <input style="display: block;width: 80%" id="description" type="text" placeholder="Task Description">
                                    <label style="display: block" for="status">Status</label>
                                    <input style="display: block" id="status" type="checkbox">
                                    <label style="display: block" for="assign">Assigned to:</label>
                                    <select style="display: block" id="assign" name="assign">
                                        @foreach($data['users'] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>


                                    <div class="input-group-btn">
                                        <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                                        <button id="cancel-new-event" type="button" class="btn btn-primary btn-flat">Cancel</button>
                                    </div>
                                    <!-- /btn-group -->
                                </div>
                                <!-- /input-group -->
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div id="add-new-task-button" class="box-footer clearfix no-border">
                            <button id="addTask" type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add task</button>
                        </div>
                    </div>

                </section>
            </div>
        </section>
@endsection

@section('script')
    <script>
        $(document).on('click', "#addTask", function (event) {
            event.preventDefault();

            $('#add-new-task').show();
            $('#add-new-task-button').hide();
        });

        $(document).on('click', "#cancel-new-event", function (event) {
            event.preventDefault();

            $('#add-new-task').hide();
            $('#add-new-task-button').show();
        });

        $(document).on('click', "#add-new-event", function (event) {
            event.preventDefault();

            $('#add-new-task').hide();
            $('#add-new-task-button').show();
            var name = $('#name').val();
            var description = $('#description').val();
            var assign = $('#assign').val();

            if ($('#status').is(":checked"))
            {
                var status = 1;
            }else{
                var status = 0;
            }

            $.ajax({
                type: 'post',
                url: '/add-task',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {name:name,description:description,assign:assign,status:status},
                success: function (data) {
                    var li = $('#example-li-element').html();
                    $('.todo-list').append('<li id="li-'+data.id+'">'+li+'</li>');
                    $('.todo-list>#li-'+data.id+'>#span-name').append(data.name);
                    $('.todo-list>#li-'+data.id+'>#span-description').append(data.description);
                    $('.todo-list>#li-'+data.id+'>#span-owned').append(data.user_id);
                    $('.todo-list>#li-'+data.id+'>#span-assign').append(data.assign);
                    $('.todo-list>#li-'+data.id+'>#span-status').append(data.status);
                    $('.todo-list>#li-'+data.id).append('<div class="tools">' +
                            '<i id="edit-task edit-'+data.id+'" data-id="'+data.id+'" class="fa fa-edit"></i>' +
                            '<i id="delete-task delete-'+data.id+'" data-id="'+data.id+'" class="fa fa-trash-o"></i>' +
                        '</div>');
                },
                dataType: 'json',
            });
        });

        $(document).on('click', ".fa-trash-o", function (event) {
            event.preventDefault();

            var id = $(this).attr('data-id');
        console.log(id);

            $.ajax({
                type: 'post',
                url: '/delete-task/' +id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    '_token': $(
                        'input[name = "_token"]').val(),
                    _method: 'delete'
                },

                success: function (data) {
                    $('.todo-list>#li-'+data.id).remove();
                },
                dataType: 'json',
            });
        });
    </script>
@endsection
