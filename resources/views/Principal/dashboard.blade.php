@extends('Principal.layouts.admin')

@section('content')
<!-- School Header -->
<div class="school-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="mb-2"><strong>Welcome back, {{ Auth::guard('principal')->user()->name }}!</strong></h1>
            <p class="mb-0 opacity-75">Calingcaguing National High School - Principal Dashboard</p>
            <small class="opacity-50">{{ date('l, F j, Y') }}</small>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex justify-content-end align-items-center">
                <div class="me-3">
                    <i class="fas fa-school fa-3x text-white"></i>
                </div>
                <div>
                    <div class="fw-bold text-white">Academic Year</div>
                    <div class="small text-white">2024-2025</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-user-graduate text-primary fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalStudents ?? 0) }}</div>
                    <div class="stats-label">Enrolled Students</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-primary" style="width: {{ $studentCapacityPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $studentCapacityPercentage ?? 0 }}% of capacity</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalTeachers ?? 0) }}</div>
                    <div class="stats-label">Teaching Staff</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-success" style="width: {{ $teacherUtilizationPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $teacherUtilizationPercentage ?? 0 }}% actively teaching</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-door-open text-info fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($activeClasses ?? 0) }}</div>
                    <div class="stats-label">Active Classes</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: {{ $roomUtilizationPercentage ?? 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ $roomUtilizationPercentage ?? 0 }}% rooms occupied</small>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stats-icon">
                    <i class="fas fa-book text-warning fa-2x"></i>
                </div>
                <div class="text-end">
                    <div class="stats-number">{{ number_format($totalSubjects ?? 0) }}</div>
                    <div class="stats-label">Total Subjects</div>
                </div>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-warning" style="width: {{ ($totalSubjects ?? 0) > 0 ? min(round((($activeClasses ?? 0) / ($totalSubjects ?? 1)) * 100), 100) : 0 }}%"></div>
            </div>
            <small class="text-muted mt-2 d-block">{{ ($totalSubjects ?? 0) > 0 ? min(round((($activeClasses ?? 0) / ($totalSubjects ?? 1)) * 100), 100) : 0 }}% have assigned teachers</small>
        </div>
    </div>
</div>


<!-- Calendar Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarModalLabel">School Events Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="text-center py-2 text-muted small">Click any date to create a new event.</div>
                <div id="school-calendar" style="height:80vh;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Recent School Announcements -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i>
                    School Announcements
                </h5>
                <a href="{{ route('principal.announcements.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Announcement
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
                        @foreach($recentAnnouncements->where('status', 'active') as $announcement)
                            <div class="list-group-item p-3 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-primary">{{ $announcement->title ?? 'Untitled' }}</h6>
                                        <p class="mb-2 text-muted">{{ Str::limit($announcement->content ?? '', 120) }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            @if($announcement->created_at)
                                                {{ $announcement->created_at->diffForHumans() }}
                                            @else
                                                Unknown date
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ ($announcement->status ?? 'draft') === 'active' ? 'success' : 'warning' }} ms-3">
                                        {{ ucfirst($announcement->status ?? 'draft') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="list-group-item p-3 border-0 text-center">
                            <div class="text-muted">
                                <i class="fas fa-bullhorn fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">No announcements yet</p>
                                <small>Create your first announcement to get started</small>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('principal.announcements.index') }}" class="btn btn-outline-primary btn-sm">
                        View All Announcements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Calendar -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('principal.announcements.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-bullhorn me-2"></i>Create Announcement
                    </a>
                    <a href="{{ route('principal.teachers.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-users me-2"></i>Manage Teachers
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-calendar-alt me-2"></i>School Calendar
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming School Events -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Upcoming Events
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="upcoming-events-container">
                    @include('Principal._upcoming_events_list', ['upcomingEvents' => $upcomingEvents])
                </div>
                <div class="text-center py-3">
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#calendarModal">
                        <i class="fas fa-calendar-alt me-2"></i>View Full Calendar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="eventForm">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="eventId">
          <div class="mb-3">
            <label for="eventTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="eventTitle" required>
          </div>
          <div class="mb-3">
            <label for="eventDescription" class="form-label">Description</label>
            <textarea class="form-control" id="eventDescription"></textarea>
          </div>
          <div class="mb-3">
            <label for="eventStart" class="form-label">Start</label>
            <input type="datetime-local" class="form-control" id="eventStart" required>
          </div>
          <div class="mb-3">
            <label for="eventEnd" class="form-label">End</label>
            <input type="datetime-local" class="form-control" id="eventEnd">
          </div>
          <div class="mb-3">
            <label for="eventColor" class="form-label">Color</label>
            <input type="color" class="form-control form-control-color" id="eventColor" value="#563d7c" title="Choose your color">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="deleteEventButton" style="display:none;">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveEventButton">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('styles')
@parent
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    /* Dashboard specific styles */

    .stats-card {
        background: white;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: none;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 600;
        color: #2c5aa0;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .stats-label {
        color: #6b7280;
        font-weight: 400;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-icon {
        opacity: 0.7;
        font-size: 2rem;
        color: #2c5aa0;
        margin-bottom: 10px;
    }

    /* Info cards */
    .info-card {
        background: white;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .info-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e0e6ed;
        padding: 15px 20px;
        font-weight: 500;
        color: #495057;
    }

    .info-card-body {
        padding: 20px;
    }

    /* List items */
    .list-group-item {
        border: 1px solid #e0e6ed;
        padding: 12px 15px;
        margin-bottom: 1px;
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Badges */
    .badge {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
    }

    /* Progress bars */
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 4px;
        background-color: #2c5aa0;
    }

    /* School header */
    .school-header {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-yellow) 100%);
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: var(--white);
    }

    .school-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue-light) 0%, var(--primary-blue) 100%);
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 20% 0);
        opacity: 0.9;
        z-index: 1;

    }

    .school-header h1 {
        font-size: 1.75rem;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--white);
    }

    .school-header p {
        font-size: 14px;
        margin-bottom: 5px;
        color: var(--white);
    }

    /* Quick action buttons */
    .btn-outline-primary,
    .btn-outline-success,
    .btn-outline-info,
    .btn-outline-warning {
        border-radius: 4px;
        font-size: 14px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background-color: #2c5aa0;
        border-color: #2c5aa0;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        border-color: #28a745;
    }

    /* Card footer */
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e0e6ed;
        padding: 15px 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .school-header {
            text-align: center;
            padding: 15px;
        }

        .stats-card {
            margin-bottom: 15px;
        }
    }

    #school-calendar {
        width: 100%;
        max-width: 900px;
        height: 80vh !important;
        min-height: 500px;
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin: 0 auto;
        border: 1px solid var(--gray-200);
    }
    .fc-toolbar-title {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--primary-blue);
    }
    .fc-daygrid-event {
        background: transparent !important;
        color: var(--text-dark) !important;
        border: none !important;
        border-radius: 3px !important;
        font-weight: 400;
        padding: 1px 4px;
        margin-bottom: 2px;
        box-shadow: none;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.3;
        text-align: left;
        display: flex;
        align-items: flex-start;
        min-height: auto;
    }
    .fc-event-time {
        display: none;
    }
    .fc-event-title {
        font-size: 0.8rem;
        line-height: 1.2;
        white-space: normal;
        word-wrap: break-word;
        flex-grow: 1;
        background: var(--primary-blue) !important;
        color: var(--accent-yellow) !important;
        padding: 2px 5px;
        border-radius: 3px;
        display: block;
        font-weight: 500;
    }
    .fc-daygrid-event .fc-event-main {
        flex-grow: 1;
        overflow: visible;
    }
    .fc-daygrid-event-dot {
        border-color: var(--primary-blue) !important;
    }
    .fc-daygrid-day-frame {
        min-height: 90px;
    }
    .fc .fc-button-primary {
        background: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        border-radius: 4px;
        font-weight: 500;
        color: var(--accent-yellow);
    }
    .fc .fc-button-primary:hover {
        background: var(--primary-blue-dark);
        border-color: var(--primary-blue-dark);
        color: var(--accent-yellow);
    }
    .fc-col-header-cell-cushion {
        font-weight: 600;
        color: var(--text-dark);
    }
    .fc-daygrid-day-number {
        font-weight: 500;
        color: var(--text-dark);
    }
    .modal-xl {
        max-width: 98vw;
    }
    .modal-body {
        padding: 0 !important;
    }

    /* Define system color variables if not already defined in a parent style section */
    :root {
        --primary-blue: #003399;
        --primary-blue-dark: #1a237e;
        --accent-yellow: #FFD600;
        --accent-orange: #FF9800;
        --white: #fff;
        --gray-50: #f8fafc;
        --gray-200: #e2e8f0;
        --gray-400: #94a3b8;
        --text-dark: #1e293b;
    }
</style>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
var calendar;
// Define modal and input variables in a scope accessible to FullCalendar callbacks
var eventModal;
var eventForm;
var deleteEventBtn;
var eventIdInput;
var eventTitleInput;
var eventDescriptionInput;
var eventStartInput;
var eventEndInput;
var eventColorInput;

function resetModal() {
    if (eventIdInput) eventIdInput.value = '';
    if (eventTitleInput) eventTitleInput.value = '';
    if (eventDescriptionInput) eventDescriptionInput.value = '';
    if (eventStartInput) eventStartInput.value = '';
    if (eventEndInput) eventEndInput.value = '';
    if (eventColorInput) eventColorInput.value = '#563d7c';
    if (deleteEventBtn) deleteEventBtn.style.display = 'none';
}

function initOrRefreshCalendar() {
    var calendarEl = document.getElementById('school-calendar');
    if (!calendar) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            titleFormat: { year: 'numeric', month: 'long' },
            themeSystem: 'standard',
            events: {
                url: '{{ route('principal.events.index') }}',
                method: 'GET',
                failure: function() {
                    console.error('Error fetching events!');
                    alert('There was an error while fetching events!');
                },
                eventDataTransform: function(eventData) {
                    if (eventData.color) {
                        eventData.backgroundColor = eventData.color;
                        eventData.borderColor = eventData.color;
                    }
                    return eventData;
                }
            },
            nowIndicator: true,
            selectable: true,
            editable: false,
            dateClick: function(info) {
                console.log('Date clicked:', info.dateStr);
                console.log('eventModal object:', eventModal);
                if (eventModal && typeof eventModal.show === 'function') {
                    resetModal();
                    if (eventStartInput) eventStartInput.value = info.dateStr + 'T08:00';
                    if (eventEndInput) eventEndInput.value = info.dateStr + 'T17:00';
                    eventModal.show();
                } else {
                    console.error('eventModal is not properly initialized or show method is missing.', eventModal);
                    alert('Calendar is not ready to create events. Please check console for details.');
                }
            },
            eventClick: function(info) {
                console.log('Event clicked:', info.event.id);
                console.log('eventModal object:', eventModal);
                if (eventModal && typeof eventModal.show === 'function') {
                    var event = info.event;
                    resetModal();
                    if (eventIdInput) eventIdInput.value = event.id;
                    if (eventTitleInput) eventTitleInput.value = event.title;
                    if (eventDescriptionInput) eventDescriptionInput.value = event.extendedProps.description || '';
                    if (eventStartInput) eventStartInput.value = event.start ? event.start.toISOString().slice(0,16) : '';
                    if (eventEndInput) eventEndInput.value = event.end ? event.end.toISOString().slice(0,16) : '';
              
                    if (deleteEventBtn) deleteEventBtn.style.display = 'inline-block';
                    eventModal.show();
                } else {
                    console.error('eventModal is not properly initialized or show method is missing.', eventModal);
                    alert('Calendar is not ready to edit events. Please check console for details.');
                }
            }
        });
        calendar.render();
    } else {
        calendar.updateSize();
        calendar.render();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal objects and input references once DOM is loaded
    eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    eventForm = document.getElementById('eventForm');
    deleteEventBtn = document.getElementById('deleteEventButton');
    eventIdInput = document.getElementById('eventId');
    eventTitleInput = document.getElementById('eventTitle');
    eventDescriptionInput = document.getElementById('eventDescription');
    eventStartInput = document.getElementById('eventStart');
    eventEndInput = document.getElementById('eventEnd');
    eventColorInput = document.getElementById('eventColor');

    var calendarModal = document.getElementById('calendarModal'); // This is the main FullCalendar display modal

    if (calendarModal) {
        calendarModal.addEventListener('shown.bs.modal', function () {
            initOrRefreshCalendar();
        });
    }

    if (eventForm) {
        eventForm.onsubmit = function(e) {
            e.preventDefault();
            var id = eventIdInput.value;
            var data = {
                title: eventTitleInput.value,
                description: eventDescriptionInput.value,
                start: eventStartInput.value,
                end: eventEndInput.value,
                color: eventColorInput.value
            };
            var url, method;
            if (id) {
                url = `/principal/events/${id}`;
                method = 'PUT';
            } else {
                url = `{{ route('principal.events.store') }}`;
                method = 'POST';
            }
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    // If response is not OK (e.g., 4xx or 5xx status)
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error occurred.');
                    });
                }
                return response.json();
            })
            .then(event => {
                eventModal.hide();
                calendar.refetchEvents();
                // Refresh the Upcoming Events list after saving an event
                fetch('{{ route('principal.dashboard.upcoming_events_html') }}')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('upcoming-events-container').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error refreshing upcoming events after save:', error);
                    });
            })
            .catch(error => {
                console.error('Error saving event:', error);
                alert('Error saving event: ' + error.message || 'An unknown error occurred.');
            });
        };
    }

    if (deleteEventBtn) {
        deleteEventBtn.onclick = function() {
            var id = eventIdInput.value;
            if (!id) return;
            if (!confirm('Are you sure you want to delete this event?')) return;
            fetch(`/principal/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error occurred during deletion.');
                    });
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    eventModal.hide();
                    calendar.refetchEvents();
                    // Refresh the Upcoming Events list after deleting an event
                    fetch('{{ route('principal.dashboard.upcoming_events_html') }}')
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('upcoming-events-container').innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error refreshing upcoming events after delete:', error);
                        });
                } else {
                    alert('Error deleting event: ' + (result.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('Error deleting event:', error);
                alert('Error deleting event: ' + error.message || 'An unknown error occurred.');
            });
        };
    }

    // AJAX setup for CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content')); // Log CSRF token

    // Handle Save Event button click
    document.getElementById('saveEventButton').addEventListener('click', function() {
        // ... existing code ...
    });
});

// FullCalendar Initialization for Modal
var calendarModalEl = document.getElementById('school-calendar');
var calendarModal = new FullCalendar.Calendar(calendarModalEl, {
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    initialView: 'dayGridMonth',
    events: '/principal/events',
    editable: true,
    selectable: true,
    dayMaxEvents: true,
    eventDidMount: function(info) {
        // Optional: Add tooltip or custom styling
    },
    eventClick: function(info) {
        var event = info.event;

        // Populate modal for viewing (read-only)
        document.getElementById('displayEventTitle').innerText = event.title;
        document.getElementById('displayEventDescription').innerText = event.extendedProps.description || 'N/A';
        document.getElementById('displayEventStart').innerText = moment(event.start).format('YYYY-MM-DD HH:mm');
        document.getElementById('displayEventEnd').innerText = event.end ? moment(event.end).format('YYYY-MM-DD HH:mm') : 'N/A';
        document.getElementById('displayEventColor').style.backgroundColor = event.backgroundColor;

        $('#eventModal').modal('show');
    }
});

// Render the calendar when the modal is shown
$('#calendarModal').on('shown.bs.modal', function() {
    calendarModal.render();
});
</script>
@endsection