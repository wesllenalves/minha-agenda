<div wire:ignore>
    <div class="container mt-5" style="max-width: 900px">
        <h2 class="h2 text-center mb-5 border-bottom pb-3">Minha Agenda</h2>
        <div id='calendar'></div>

        <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agendamento</h5>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="form-group">
                                <label for="descricao" class="col-form-label">Descrição do evento:</label>
                                <input type="text" wire:model.defer="title" class="form-control" id="descricao">
                            </div>
                            <div class="form-group">
                                <label for="start" class="col-form-label">Inicio:</label>
                                <input type="datetime-local" wire:model.defer="start" class="form-control" id="start"/>
                            </div>
                            <div class="form-group">
                                <label for="end" class="col-form-label">Fim:</label>
                                <input type="datetime-local" wire:model.defer="end" class="form-control" id="end"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button class="btn btn-primary">Registar</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar agendamento</h5>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="update">
                            <div class="form-group">
                                <label for="descricao1" class="col-form-label">Descrição do evento:</label>
                                <input type="text" wire:model.defer="title" class="form-control" id="descricao1">
                            </div>
                            <div class="form-group">
                                <label for="start1" class="col-form-label">Inicio:</label>
                                <input type="datetime-local" wire:model.defer="start" class="form-control" id="start1"/>
                            </div>
                            <div class="form-group">
                                <label for="end1" class="col-form-label">Fim:</label>
                                <input type="datetime-local" wire:model.defer="end" class="form-control" id="end1"/>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-primary">Editar</button>
                                </div>
                                
                                <button class="btn btn-danger" wire:click.prevent="delete">Excluir</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>
@push('script')
    <script>
            $("#modalCreate").on("hidden.bs.modal", () => {
                @this.title = '';
                @this.start = '';
                @this.end = '';
            });
            $("#modalEdit").on("hidden.bs.modal", () => {
                @this.event_id = '';
                @this.title = '';
                @this.start = '';
                @this.end = '';
            });

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',                
                themeSystem: 'bootstrap',
                locale: 'pt-br',
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                select: ({startStr, endStr}) => {
                    @this.start = dayjs(startStr+"T00:00:00").format('YYYY-MM-DDTHH:mm:ss');
                    @this.end = dayjs(endStr+"T00:00:00").format('YYYY-MM-DDTHH:mm:ss');

                    $("#modalCreate").modal("toggle");                    
                },

                eventClick: ({event}) => {
                    $("#modalEdit").modal("toggle");
                    @this.event_id = event.id;
                    @this.title = event.title;
                    @this.start = dayjs(event.start).format('YYYY-MM-DDTHH:mm:ss');
                    @this.end = dayjs(event.end).format('YYYY-MM-DDTHH:mm:ss');
                },
                views: {
                    week: {
                        select: (event) => {
                            console.log("veio na semana");
                            console.log(event);                   
                        },
                    },
                },

            });

            calendar.addEventSource({
                url: '/api/calendar/events'
            });
            calendar.render();

            document.addEventListener("closeModalCreate", ({detail}) => {
                if (detail.close) {
                    $("#modalCreate").modal("toggle");
                }
            });
            document.addEventListener("closeModalEdit", ({detail}) => {
                if (detail.close) {
                    $("#modalEdit").modal("toggle");
                }
            });

            document.addEventListener("refreshEventCalendar", ({detail}) => {
                if (detail.refresh) {
                    calendar.refetchEvents();
                }
            });
    </script>
@endpush