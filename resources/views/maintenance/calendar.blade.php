@extends('components.layouts.app')

@section('title', 'Onderhoudsplanning - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Onderhoudsplanning</h1>
        <p class="text-gray-600">Globale planning voor alle monteurs</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('maintenance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug naar Overzicht
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div id="calendar"></div>
</div>

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'nl',
            events: @json($events),
            eventClick: function(info) {
                window.location.href = '/maintenance/' + info.event.id;
            },
            eventDidMount: function(info) {
                // Tooltip voor extra info
                info.el.title = info.event.title + '\n' +
                              'Monteur: ' + info.event.extendedProps.technician + '\n' +
                              'Klant: ' + info.event.extendedProps.customer;
            }
        });
        calendar.render();
    });
</script>
@endsection

<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
    }
    .fc-button-primary {
        background-color: #FFD700 !important;
        border-color: #FFD700 !important;
        color: #000 !important;
    }
    .fc-button-primary:hover {
        background-color: #E6C200 !important;
        border-color: #E6C200 !important;
    }
    .fc-button-active {
        background-color: #CCAD00 !important;
        border-color: #CCAD00 !important;
    }
</style>
@endsection
