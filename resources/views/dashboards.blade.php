@extends('layouts.app')
@section('content')
<div class="container-fluid">
            <div class="email-wrap bookmark-wrap">
              <div class="row">
                <div class="col-xl-3 box-col-3">
                  <div class="md-sidebar"><a class="btn btn-primary md-sidebar-toggle" href="javascript:void(0)">task filter</a>
                    <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                      <div class="email-left-aside">
                        <div class="card">
                          <div class="card-body">
                            <div class="email-app-sidebar left-bookmark task-sidebar">
                              <div class="d-flex">
                                <div class="media-size-email"><img class="me-3 rounded-circle" src="https://admin.pixelstrap.net/crocs/assets/images/user/user.png" alt=""></div>
                                <div class="flex-grow-1">
                                  <h3>{{ Auth::user()->name }}</h3>
                                  <p>{{ Auth::user()->email }}</p>
                                </div>
                              </div>
                              <ul class="nav main-menu" role="tablist">
                                <li class="nav-item">
                                  <button class="badge-light-primary btn-block txt-primary btn-mail w-100" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="me-2" data-feather="check-circle"></i> New Task</button>
                                </li>
                                <li class="nav-item"><span class="main-title"> Views</span></li>
                                <li><a class="active" id="pills-created-tab" data-bs-toggle="pill" href="#pills-created" role="tab" aria-controls="pills-created" aria-selected="true"><span class="title"> Created by me</span></a></li>
                                
                                <li><a class="show" id="pills-assigned-tab" data-bs-toggle="pill" href="#pills-assigned" role="tab" aria-controls="pills-assigned" aria-selected="false"><span class="title">Assigned to me</span></a></li>
                                
                                  <hr>
                                </li>
                               
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-9 col-md-12 box-col-80">
                  <div class="email-right-aside bookmark-tabcontent">
                    <div class="card email-body radius-left">
                      <div class="ps-0">
                        <div class="tab-content">
                          <div class="tab-pane fade active show" id="pills-created" role="tabpanel" aria-labelledby="pills-created-tab">
                            <div class="card mb-0">
                             
                              <div class="card-body p-0">
                                <div class="taskadd">
                                  <div class="table-responsive custom-scrollbar">
                                  <form id="filterForm">
                                  <form id="filterForm">
    <select id="statusFilter">
        <option value="all">All</option>
        <option value="open">Open</option>
        <option value="closed">Closed</option>
    </select>
</form>

@if($tasks->isEmpty())
    <p>No tasks available.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tasksTable">
            @forelse($tasks as $task)
                <tr data-status="{{ $task->status }}">
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->start_date }}</td>
                    <td>{{ $task->end_date }}</td>
                    <td>
                        <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()">
                                <option value="open" {{ $task->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $task->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editTaskModal-{{ $task->id }}" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>

                        <!-- Edit Task Modal -->
                        <div class="modal fade" id="editTaskModal-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel-{{ $task->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editTaskModalLabel-{{ $task->id }}">Edit Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description" required>{{ $task->description }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $task->start_date }}" required min="{{ \Illuminate\Support\Carbon::today()->toDateString() }}" />
                                            </div>

                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $task->end_date }}" required min="{{ \Illuminate\Support\Carbon::today()->toDateString() }}" />
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Button -->
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No tasks available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const tasksTable = document.getElementById('tasksTable');

    function filterTasks() {
        const selectedStatus = statusFilter.value.toLowerCase();
        const rows = tasksTable.getElementsByTagName('tr');

        for (let row of rows) {
            const rowStatus = row.getAttribute('data-status')?.toLowerCase();
            if (rowStatus && (selectedStatus === 'all' || rowStatus === selectedStatus)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    statusFilter.addEventListener('change', filterTasks);

    // Initialize filter
    filterTasks();
});
</script>


         
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-todaytask" role="tabpanel" aria-labelledby="pills-todaytask-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">Today's Tasks</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center">
                                  <div class="row" id="favouriteData"></div>
                                  <div class="no-favourite"><span>No task due today.</span></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-delayed" role="tabpanel" aria-labelledby="pills-delayed-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">Delayed Tasks</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">Upcoming Tasks</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-weekly" role="tabpanel" aria-labelledby="pills-weekly-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">This Week Tasks</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-monthly" role="tabpanel" aria-labelledby="pills-monthly-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">This Month Tasks</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-assigned" role="tabpanel" aria-labelledby="pills-assigned-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">Assigned to me</h4><a href="#"><i class="me-2"></i></a>
                              </div>
                              <div class="card-body p-0">
                                <div class="taskadd">
                                  <div class="table-responsive custom-scrollbar">
                                  @if($works->isEmpty())
        <p>No tasks available.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>

                    <th>start_date</th>
                    <th>end_date</th>
                    <th>Status</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($works as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->start_date}}</td>
                        <td>{{ $task->end_date}}</td>
                        <td>
                                <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="open" {{ $task->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ $task->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </form>
                         </td>

                      
                        <td>

                        <ul>
                       
                          </td>
                      </tr>
            @empty
                <tr>
                    <td colspan="4">No tasks available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endif
                          <div class="fade tab-pane" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab">
                            <div class="card mb-0">
                              <!-- <div class="card-header d-flex">
                                <h4 class="mb-0">Notification</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="fade tab-pane" id="pills-newsletter" role="tabpanel" aria-labelledby="pills-newsletter-tab">
                            <div class="card mb-0">
                              <div class="card-header d-flex">
                                <h4 class="mb-0">Newsletter</h4><a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div> -->
                              <div class="card-body">
                                <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                            </div>
                          </div>
                          <div class="modal fade modal-bookmark" id="createtag" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h3 class="modal-title">Create Tag</h3>
                                  <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <form class="form-bookmark needs-validation" novalidate="">
                                    <div class="row">
                                      <div class="mb-3 mt-0 col-md-12">
                                        <label>Tag Name</label>
                                        <input class="form-control" type="text" required="" autocomplete="off">
                                      </div>
                                      <div class="mt-0 col-md-12">
                                        <label>Tag color</label>
                                        <input class="form-color d-block" type="color" value="#5C61F2">
                                      </div>
                                    </div>
                                    <button class="btn btn-secondary" type="button">Save</button>
                                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
           
            <div class="modal fade modal-bookmark" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add Task</h3>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-bookmark needs-validation" id="bookmark-form" novalidate="" action="{{ route('task.store') }}" method="POST"> 
          @csrf <!-- CSRF token for security -->

          <div class="row">
            <!-- Task Title -->
            <div class="mb-3 mt-0 col-md-12">
              <label for="task-title">Task Title</label>
              <input class="form-control" id="task-title" type="text" name="title" required="" autocomplete="off">
            </div>

            <!-- Start Date and End Date -->
            <div class="mb-3 mt-0 col-md-12">
              <div class="d-flex date-details">
                <div class="d-inline-block me-2">
                  <label for="start_date">Start Date</label>
                  <input class="form-control" type="date" id="start_date" name="start_date" required min="{{ \Illuminate\Support\Carbon::today()->toDateString() }}" />
                </div>
                <div class="d-inline-block">
                  <label for="end_date">End Date</label>
                  <input class="form-control" type="date" id="end_date" name="end_date" required min="{{ \Illuminate\Support\Carbon::today()->toDateString() }}" />
                </div>
              </div>
            </div>
            <div class="mb-3 mt-0 col-md-6">


    <div style="max-height: 100px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
   

        @foreach($user as $item)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $item->id }}" id="user_{{ $item->id }}">
                <label  class="form-check-label" for="user_{{ $item->id }}">
                    {{ $item->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>


            <!-- Task Description -->
            <div class="mb-3 col-md-12 my-0">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" required autocomplete="off" placeholder="Description"></textarea>
            </div>
          </div>

          <!-- Hidden input and buttons -->
          <input id="index_var" type="hidden" value="6">
          <button class="btn btn-secondary" id="Bookmark" type="submit">Save</button>
          <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- javascript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="form-group"></div>
   @endsection